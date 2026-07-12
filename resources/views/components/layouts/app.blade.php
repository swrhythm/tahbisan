@props(['title' => null])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Tahbisan' }} — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-cream font-sans text-[#2b241f]">
    {{ $slot }}

    @if (session('toast'))
        <div data-toast class="fixed bottom-7 left-1/2 z-[100] -translate-x-1/2 rounded-md bg-maroon-dark px-6 py-3.5 text-sm font-medium text-cream shadow-xl">
            {{ session('toast') }}
        </div>
    @endif
</body>
</html>
