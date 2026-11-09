<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://bunny.net">
    <link href="https://bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Routing Scripts -->
    @routes

    <!-- Asset Compiler Engine Bundle -->
    @if(isset($page))
        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.tsx', "resources/js/Pages/{$page['component']}.tsx"])
        @inertiaHead
    @endif
</head>

<body class="font-sans antialiased">
    @if(View::hasSection('content'))
        @yield('content')
    @else
        <div id="app-root">
            @inertia
        </div>
    @endif
</body>

</html>