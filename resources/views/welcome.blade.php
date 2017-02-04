<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet">
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        
        <!-- Styles -->
        <style>
            html, body {
                background: linear-gradient(141deg, #009e6c 0%, #00d1b2 71%, #00e7eb 100%);
                color: #fff;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #fff;
                padding: 0 25px;
                font-family: 'Prompt', sans-serif;
                font-size: 18px;
                font-weight: 300;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    <i class="fa fa-check-square-o"></i> AttendCheck
                </div>

                <div class="links">
                    <a href="https://laravel-news.com">ข่าวสาร</a>
                    <a href="{{ url('/login') }}">เข้าสู่ระบบ</a>
                    <a href="{{ url('/register') }}">ลงทะเบียน</a>
                </div>
            </div>
        </div>
    </body>
</html>
