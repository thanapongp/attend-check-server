<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AttendCheck | Register</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="auth">
    <div class="logo">
        <i class="fa fa-check-square-o"></i> AttendCheck
    </div>

    <form action="/register" method="POST" class="panel panel-default auth-panel register-panel">
        {{ csrf_field() }}
        <legend class="form-header">ลงทะเบียน</legend>
        
        @if(count($errors) > 0)
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>มีข้อผิดพลาดเกิดขึ้น!</strong> กรุณาแก้ไข้ข้อมูลแล้วลองใหม่อีกครั้ง
        </div>
        @endif

        <legend>ข้อมูลส่วนตัว</legend>

        <div class="form-group{{ ($errors->has('title')) ? ' has-error has-feedback' : '' }}">
            <label for="title">คำนำหน้าชื่อ</label>
            <input type="text" name="title" class="form-control" placeholder="คำนำหน้าชื่อ" value="{{ old('title') }}" required>
            @if ($errors->has('title'))
            <span class="form-control-feedback" aria-hidden="true">
                <i class="fa fa-times"></i>
            </span>
            <span class="help-block">{{ $errors->first('title') }}</span>
            @endif
        </div>

        <div class="form-group{{ ($errors->has('name')) ? ' has-error has-feedback' : '' }}">
            <label for="name">ชื่อ</label>
            <input type="text" name="name" class="form-control" placeholder="ชื่อ" value="{{ old('name') }}" required>
            @if ($errors->has('name'))
            <span class="form-control-feedback" aria-hidden="true">
                <i class="fa fa-times"></i>
            </span>
            <span class="help-block">{{ $errors->first('name') }}</span>
            @endif
        </div>

        <div class="form-group{{ ($errors->has('lastname')) ? ' has-error has-feedback' : '' }}">
            <label for="lastname">นามสกุล</label>
            <input type="text" name="lastname" class="form-control" placeholder="นามสกุล" value="{{ old('lastname') }}" required>
            @if ($errors->has('lastname'))
            <span class="form-control-feedback" aria-hidden="true">
                <i class="fa fa-times"></i>
            </span>
            <span class="help-block">{{ $errors->first('lastname') }}</span>
            @endif
        </div>

        <div class="form-group{{ ($errors->has('type')) ? ' has-error has-feedback' : '' }}">
            <label for="type">ประเภทผู้ใช้งาน</label>
            <select name="type" class="form-control" required>
                <option>โปรดเลือก</option>
                <option value="3">อาจารย์</option>
                <option value="2">ผู้ดูแลข้อมูลประจำคณะ</option>
            </select>
            @if ($errors->has('type'))
            <span class="help-block">{{ $errors->first('type') }}</span>
            @endif
        </div>

        <div class="form-group{{ ($errors->has('faculty')) ? ' has-error has-feedback' : '' }}">
            <label for="faculty">คณะที่สังกัด</label>
            <select name="faculty" class="form-control" required readonly>
                <option value="11" selected>วิทยาศาสตร์</option>
            </select>
            @if ($errors->has('faculty'))
            <span class="help-block">{{ $errors->first('faculty') }}</span>
            @endif
        </div>

        <legend>ข้อมูลการเข้าระบบ</legend>

        <div class="form-group{{ ($errors->has('email')) ? ' has-error has-feedback' : '' }}">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
            @if ($errors->has('email'))
            <span class="form-control-feedback" aria-hidden="true">
                <i class="fa fa-times"></i>
            </span>
            <span class="help-block">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <div class="form-group{{ ($errors->has('username')) ? ' has-error has-feedback' : '' }}">
            <label for="username">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Username" value="{{ old('username') }}" required>
            @if ($errors->has('username'))
            <span class="form-control-feedback" aria-hidden="true">
                <i class="fa fa-times"></i>
            </span>
            <span class="help-block">{{ $errors->first('username') }}</span>
            @endif
        </div>

        <div class="form-group{{ ($errors->has('password')) ? ' has-error has-feedback' : '' }}">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            @if ($errors->has('password'))
            <span class="form-control-feedback" aria-hidden="true">
                <i class="fa fa-times"></i>
            </span>
            <span class="help-block">{{ $errors->first('password') }}</span>
            @endif
        </div>

        <div class="form-group{{ ($errors->has('password_confirmation')) ? ' has-error has-feedback' : '' }}">
            <label for="password_confirmation">Password (อีกครั้ง)</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Password (อีกครั้ง)" required>
            @if ($errors->has('password_confirmation'))
            <span class="form-control-feedback" aria-hidden="true">
                <i class="fa fa-times"></i>
            </span>
            <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
            @endif
        </div>

        <button type="submit" name="submit" class="btn btn-raised-success btn-lg btn-block">
            ลงทะเบียน
        </button>
    </form>
    <a href="/login" class="outside-panel-link">เข้าสู่ระบบ</a>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
