<x-app-layout>
    <x-slot name="title">
        Reports & Analytics
    </x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,600;0,700;1,400&family=Noto+Sans:wght@300;400;500;600&display=swap');
        :root{
            --primary:#0b2040;
            --primary-mid:#133060;
            --accent:#00a8c8;
            --accent-light:#e6f7fb;
            --ink:#0d1f2d;
            --mist:#f0f4f8;
            --white:#fff;
            --border:#c8d4e0;
            --success:#1a8a5a;
            --error:#c0392b;
            --radius:4px;
            --radius-lg:6px;
            --shadow:0 4px 32px rgba(11,32,64,.10)
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Noto Sans',sans-serif;background:var(--mist);color:var(--ink)}
        .shell{max-width:1280px;margin:0 auto;padding:2.5rem 2rem 4rem}
        
        .page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem}
        .page-header-left{display:flex;align-items:center;gap:1rem}
        .badge{width:48px;height:48px;background:var(--primary);border-radius:6px;display:flex;align-items:center;justify-content:center}
        .badge svg{width:24px;height:24px;stroke:var(--accent);fill:none}
        .eyebrow{font-family:'Noto Serif',serif;font-size:.68rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--primary-mid);margin-bottom:.2rem}
        .page-title{font-family:'Noto Serif',serif;font-size:1.4rem;font-weight:800;color:var(--primary)}
        
        .category-section{margin-bottom:3rem}
        .category-header{font-family:'Noto Serif',serif;font-size:1rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--primary);margin-bottom:1.5rem;padding-bottom:.5rem;border-bottom:2px solid var(--border)}
        
        .reports-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(380px,1fr));gap:1.25rem}
        
        .report-card{background:var(--white);border-radius:var(--radius-lg);box-shadow:var(--shadow);border:1px solid var(--border);padding:1.5rem;transition:all .2s;cursor:pointer}
        .report-card:hover{transform:translateY(-2px);box-shadow:0 8px 40px rgba(11,32,64,.15);border-color:var(--accent)}
        
        .report-header{display:flex;justify-content:space-between;align-items:start;margin-bottom:.75rem}
        .report-title{font-family:'Noto Serif',serif;font-size:1rem;font-weight:700;color:var(--primary);line-height:1.3}
        .report-badge{font-size:.68rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;padding:.3rem .75rem;background:var(--accent-light);color:var(--primary);border-radius:3px}
        
        .report-description{font-size:.85rem;color:#6a7a8a;line-height:1.5;margin-bottom:1.25rem}
        
        .report-actions{display:flex;gap:.5rem;flex-wrap:wrap}
        
        .btn{display:inline-flex;align-items:center;gap:.45rem;padding:.65rem 1.4rem;border-radius:var(--radius);font-family:'Noto Serif',serif;font-size:.85rem;font-weight:700;cursor:pointer;border:none;text-decoration:none;transition:all .2s}
        .btn svg{width:16px;height:16px}
        .btn-primary{background:var(--primary);color:#fff}
        .btn-primary:hover{background:var(--primary-mid);transform:translateY(-1px)}
        .btn-sm{padding:.5rem 1rem;font-size:.78rem}
        .btn-secondary{background:var(--mist);color:var(--primary);border:1.5px solid var(--border)}
        .btn-secondary:hover{background:var(--white);border-color:var(--primary-mid)}
        
        .empty-state{text-align:center;padding:4rem 2rem;background:var(--white);border-radius:var(--radius-lg);box-shadow:var(--shadow)}
        .empty-state svg{width:64px;height:64px;stroke:var(--border);fill:none;margin-bottom:1.5rem}
        .empty-state h3{font-family:'Noto Serif',serif;font-size:1.1rem;color:var(--primary);margin-bottom:.5rem}
        .empty-state p{color:#8a9aaa;font-size:.9rem;margin-bottom:1.5rem}
        
        .recent-section{margin-top:3rem;background:var(--white);border-radius:var(--radius-lg);box-shadow:var(--shadow);border:1px solid var(--border);padding:1.5rem}
        .recent-header{font-family:'Noto Serif',serif;font-size:.9rem;font-weight:700;color:var(--primary);margin-bottom:1rem;letter-spacing:.04em}
        .recent-item{display:flex;justify-content:space-between;align-items:center;padding:.75rem 0;border-bottom:1px solid var(--border)}
        .recent-item:last-child{border-bottom:none}
        .recent-name{font-size:.875rem;color:var(--ink)}
        .recent-meta{font-size:.75rem;color:#8a9aaa}
    </style>
    @endpush

    <div class="shell">

        <div class="page-header">
            <div class="page-header-left">
                <div class="badge">
                    <svg viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                </div>
                <div>
                    <div class="eyebrow">Reports</div>
                    <h1 class="page-title">Reports & Analytics</h1>
                </div>
            </div>
        </div>

        @if($reports->isEmpty() || $reports->flatten()->isEmpty())
            <div class="empty-state">
                <svg viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3>No Reports Available</h3>
                <p>No reports are configured yet.</p>
            </div>
        @else
            @foreach($reports as $category => $categoryReports)
                <div class="category-section">
                    <h2 class="category-header">{{ ucfirst($category) }} Reports</h2>
                    
                    <div class="reports-grid">
                        @foreach($categoryReports as $report)
                            <div class="report-card">
                                <div class="report-header">
                                    <h3 class="report-title">{{ $report->name }}</h3>
                                    <span class="report-badge">{{ ucfirst($report->category ?? 'general') }}</span>
                                </div>
                                
                                <p class="report-description">{{ $report->description }}</p>
                                
                                <div class="report-actions">
                                    <a href="{{ route('reports.execute', $report->code) }}" class="btn btn-primary btn-sm">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View Report
                                    </a>
                                    
                                    <a href="{{ route('reports.export', ['code' => $report->code, 'format' => 'csv']) }}" class="btn btn-secondary btn-sm">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Export CSV
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif

        {{-- Recent Executions --}}
        @if($recentExecutions->count() > 0)
            <div class="recent-section">
                <h4 class="recent-header">Recent Executions</h4>
                @foreach($recentExecutions as $execution)
                    <div class="recent-item">
                        <span class="recent-name">{{ $execution->reportQuery->name ?? 'Unknown Report' }}</span>
                        <span class="recent-meta">
                            {{ $execution->executed_at->diffForHumans() }} 
                            · {{ $execution->result_count }} rows 
                            · {{ $execution->execution_time_ms }}ms
                        </span>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-app-layout>