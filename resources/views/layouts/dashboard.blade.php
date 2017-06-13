<!DOCTYPE html>
<html lang="en" style="height: 100%; padding-bottom: 10px">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') | AttendCheck</title>
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    @yield('css')
</head>
<body class="dashboard" style="position: relative; min-height: 100%; padding-bottom: 10rem;">
    @include('layouts.navbar')
    
    <div class="container dashboard-content">
        @yield('content')
    </div>
    <div style="position: absolute; bottom: 0; left: 0; right: 0;">
        <p class="text-center text-muted">
            Developed with <i class="fa fa-heart"></i> by Thanapong Prathumchat.
        </p>
        <p class="text-center text-muted">
            &copy; 2016 - {{date("Y")}} Faculty of Science. Ubon Ratchathani University.
        </p>
    </div>
    
    <script src="{{ mix('/js/app.js') }}"></script>
    @yield('js')
</body>
</html>
