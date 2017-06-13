<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') | AttendCheck</title>
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    @yield('css')
</head>
<body class="dashboard">
    @include('layouts.navbar')
    
    <div class="container dashboard-content">
        @yield('content')
    </div>
    <p class="text-center text-muted">
        Developed with <i class="fa fa-heart"></i> by Thanapong Prathumchat.
    </p>
    <p class="text-center text-muted">
        &copy; 2016 - {{date("Y")}} Faculty of Science. Ubon Ratchathani University.
    </p>
    
    <script src="{{ mix('/js/app.js') }}"></script>
    @yield('js')
</body>
</html>
