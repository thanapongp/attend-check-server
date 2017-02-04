<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') | AttendCheck</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="dashboard">
    @include('layouts.navbar')
    
    <div class="container dashboard-content">
        @yield('content')
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
    @yield('js')
</body>
</html>