<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Japan Shop'))</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/sidebar-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/client-layout.css') }}">

    @isset($slot)

        <script src="https://cdn.tailwindcss.com/3.4.17?plugins=forms"></script>
        <script>tailwind.config = { corePlugins: { preflight: false } }</script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    @endisset

    @stack('styles')
</head>
<body>

    @include('layouts.sidebar')

    <div class="main-wrapper">

        {{-- layouts/navigation.blade.php (bên trong gọi tiếp layouts/header.blade.php) --}}
        @include('layouts.navigation')

        @isset($header)
            <div class="bg-white border-bottom">
                <div class="container-fluid px-3 px-lg-4 py-3">{{ $header }}</div>
            </div>
        @endisset

        <main>
            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset
        </main>

        @include('layouts.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @includeIf('partials.chatbox')
    @stack('scripts')
</body>
</html>
