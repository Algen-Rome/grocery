<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grocery Admin Dashboard</title>
    @if(app()->environment('local'))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://tailwindcss.com"></script>
</head>
<body class="bg-slate-50 antialiased">

    @yield('content')

</body>
</html>
