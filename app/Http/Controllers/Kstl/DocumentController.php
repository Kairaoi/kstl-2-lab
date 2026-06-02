<?php

namespace App\Http\Controllers\Kstl;

use App\Http\Controllers\Controller;
use App\Models\Kstl\Document;
use App\Models\Kstl\DocumentVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /** Roles permitted to manage (create/upload/delete) controlled documents. */
    private const MANAGER_ROLES = ['reception', 'analyst', 'director', 'admin', 'super_admin'];

    private function canManage(): bool
    {
        return Auth::user()?->hasAnyRole(self::MANAGER_ROLES) ?? false;
    }

    private function abortUnlessManager(): void
    {
        abort_unless($this->canManage(), 403,
            'You do not have permission to manage controlled documents.');
    }

    // ── Repository index — categories + documents (all staff) ──────
    public function index(Request $request)
    {
        $category = $request->query('category');

        $query = Document::with(['currentVersion', 'createdBy'])
            ->orderBy('category')
            ->orderBy('title');

        if ($category && array_key_exists($category, Document::CATEGORY_LABELS)) {
            $query->where('category', $category);
        }

        $documents = $query->get()->groupBy('category');

        // Counts per category for the cards.
        $counts = Document::selectRaw('category, COUNT(*) as c')
            ->groupBy('category')
            ->pluck('c', 'category')
            ->toArray();

        return view('kstl.staff.documents.index', [
            'documents'  => $documents,
            'counts'     => $counts,
            'categories' => Document::CATEGORY_LABELS,
            'active'     => $category,
            'canManage'  => $this->canManage(),
        ]);
    }

    // ── Show one document + its full version history (all staff) ───
    public function show(string $id)
    {
        $document = Document::with(['versions.uploadedBy', 'currentVersion', 'createdBy'])
            ->findOrFail($id);

        return view('kstl.staff.documents.show', [
            'document'  => $document,
            'canManage' => $this->canManage(),
        ]);
    }

    // ── Create a new document with its first version (managers) ────
    public function store(Request $request)
    {
        $this->abortUnlessManager();

        $validated = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'category'       => ['required', 'in:sop,manual,assessment_record,template'],
            'subcategory'    => ['nullable', 'string', 'max:255'],
            'reference_code' => ['nullable', 'string', 'max:60'],
            'description'    => ['nullable', 'string', 'max:2000'],
            'file'           => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,csv,txt', 'max:20480'],
            'change_note'    => ['nullable', 'string', 'max:500'],
        ]);

        $document = DB::transaction(function () use ($request, $validated) {
            $document = Document::create([
                'title'          => $validated['title'],
                'category'       => $validated['category'],
                'subcategory'    => $validated['subcategory']    ?? null,
                'reference_code' => $validated['reference_code'] ?? null,
                'description'    => $validated['description']    ?? null,
                'created_by'     => Auth::id(),
            ]);

            $version = $this->storeVersion($document, $request->file('file'), 1, $validated['change_note'] ?? 'Initial version');

            $document->update(['current_version_id' => $version->id]);

            return $document;
        });

        Log::info('Controlled document created', [
            'document_id' => $document->id,
            'title'       => $document->title,
            'category'    => $document->category,
            'by'          => Auth::id(),
        ]);

        return redirect()->route('staff.documents.show', $document->id)
            ->with('success', "Document “{$document->title}” added to the repository.");
    }

    // ── Upload a new version (supersede) (managers) ────────────────
    public function uploadVersion(Request $request, string $id)
    {
        $this->abortUnlessManager();

        $document = Document::findOrFail($id);

        $validated = $request->validate([
            'file'        => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,csv,txt', 'max:20480'],
            'change_note' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($request, $document, $validated) {
            $next    = $document->next_version_number;
            $version = $this->storeVersion($document, $request->file('file'), $next, $validated['change_note'] ?? null);

            // Supersede: point the document at the new version (old files stay on disk).
            $document->update(['current_version_id' => $version->id]);
        });

        Log::info('Controlled document new version uploaded', [
            'document_id' => $document->id,
            'by'          => Auth::id(),
        ]);

        return redirect()->route('staff.documents.show', $document->id)
            ->with('success', 'New version uploaded. The previous version is retained in the history.');
    }

    // ── Download a specific version (all staff) ────────────────────
    public function download(string $versionId)
    {
        $version = DocumentVersion::findOrFail($versionId);

        abort_unless(Storage::disk('private')->exists($version->file_path), 404, 'File not found.');

        Log::info('Document version downloaded', [
            'version_id'  => $version->id,
            'document_id' => $version->document_id,
            'user_id'     => Auth::id(),
        ]);

        return Storage::disk('private')->download($version->file_path, $version->original_filename);
    }

    // ── Delete an entire document + all versions (managers) ────────
    public function destroy(string $id)
    {
        $this->abortUnlessManager();

        $document = Document::with('versions')->findOrFail($id);

        DB::transaction(function () use ($document) {
            // Detach current pointer first so the version rows can go.
            $document->update(['current_version_id' => null]);

            foreach ($document->versions as $version) {
                if (Storage::disk('private')->exists($version->file_path)) {
                    Storage::disk('private')->delete($version->file_path);
                }
                $version->delete();
            }

            $document->delete(); // soft delete the logical record
        });

        Log::info('Controlled document deleted', [
            'document_id' => $document->id,
            'title'       => $document->title,
            'by'          => Auth::id(),
        ]);

        return redirect()->route('staff.documents.index')
            ->with('success', "Document “{$document->title}” removed from the repository.");
    }

    // ── Internal: store an uploaded file as a new version row ──────
    private function storeVersion(Document $document, $file, int $versionNumber, ?string $changeNote): DocumentVersion
    {
        $stored = 'doc_' . $document->id . '_v' . $versionNumber . '_' . time()
                . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs('documents/' . $document->id, $stored, 'private');

        return DocumentVersion::create([
            'document_id'       => $document->id,
            'version_number'    => $versionNumber,
            'original_filename' => $file->getClientOriginalName(),
            'file_path'         => $path,
            'mime_type'         => $file->getClientMimeType(),
            'file_size'         => $file->getSize(),
            'change_note'       => $changeNote,
            'uploaded_by'       => Auth::id(),
        ]);
    }
}