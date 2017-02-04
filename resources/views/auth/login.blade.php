<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AttendCheck | Login</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="auth">
    <div class="logo">
        <i class="fa fa-check-square-o"></i> AttendCheck
    </div>

    <form action="/login" method="POST" class="panel panel-default auth-panel">
        {{ csrf_field() }}
        <legend class="form-header">เข้าสู่ระบบ</legend>

        <div class="form-group{{ ($errors->has('username')) ? ' has-error has-feedback' : '' }}">
            <label for="username">Username</label>
            <input type="text" class="form-control input-lg" placeholder="Username">
            @if ($errors->has('username'))
            <span class="form-control-feedback" aria-hidden="true">
                <i class="fa fa-times"></i>
            </span>
            <span class="help-block">{{ $errors->first('username') }}</span>
            @endif
        </div>

        <div class="form-group{{ ($errors->has('password')) ? ' has-error has-feedback' : '' }}">
            <label for="password">Password</label>
            <input type="text" class="form-control input-lg" placeholder="Password">
            @if ($errors->has('password'))
            <span class="form-control-feedback" aria-hidden="true">
                <i class="fa fa-times"></i>
            </span>
            <span class="help-block">{{ $errors->first('password') }}</span>
            @endif
        </div>

        <button type="submit" class="btn btn-raised-success btn-lg btn-block">
            เข้าสู่ระบบ
        </button>
        <a href="/password/reset" class="inside-panel-link">ลืมรหัสผ่าน</a>
    </form>
    <a href="/register" class="outside-panel-link">ลงทะเบียนเข้าใช้ระบบ</a>
    
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>