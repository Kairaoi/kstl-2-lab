{{-- resources/views/kstl/client/consent/invalid.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invalid Link — KSTL</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen">

    <div class="bg-blue-900 text-white py-4 px-6">
        <div class="max-w-2xl mx-auto flex items-center gap-3">
            <div class="w-8 h-8 bg-blue-700 rounded-lg flex items-center justify-center text-xs font-bold">KL</div>
            <div>
                <p class="font-semibold text-sm">Kiribati Seafood Toxicology Laboratory</p>
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-16 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-3">Invalid or Expired Link</h1>
        <p class="text-gray-500 text-sm mb-8">{{ $reason }}</p>

        @if(isset($lab_email))
            <p class="text-sm text-gray-500">
                Please contact us directly at
                <a href="mailto:{{ $lab_email }}" class="text-blue-600 underline">{{ $lab_email }}</a>
                to record your decision.
            </p>
        @endif
    </div>

</body>
</html>