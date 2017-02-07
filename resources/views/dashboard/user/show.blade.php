@extends('layouts.dashboard')

@section('title', 'จัดการรายวิชาผู้ใช้')

@section('content')
<h1 class="dashboard-title">
    จัดการรายผู้ใช้
</h1>

<ol class="breadcrumb">
    <li><a href="{{ url('/dashboard/user') }}">Dashboard</a></li>
    <li class="active">จัดการผู้ใช้</li>
</ol>

<div class="panel panel-info dashboard-panel">

    <div class="panel-heading">
        <span>จัดการผู้ใช้</span>
    </div>

    <div class="panel-body">
        <h3 style="margin-top: 0">ข้อมูลผู้ใช้</h3>
        <hr>
        <h2>
            ชื่อ: {{ $user->title }} {{ $user->name }} {{ $user->lastname }} <br>
            <small>
            สถานะ: {!! 
            $user->active ? '<span class="text-success">ยืนยันแล้ว</span>' 
            : '<span class="text-danger">ยังไม่ยืนยัน</span>'
            !!} <br>
            ประเภท: {{ $user->type }} <br>
            คณะ: {{ $user->faculty }} <br>
            E-mail: {{ $user->email }}
            </small>
        </h2>
        <br>
        <form action="{{ action('UserController@approve', ['user' => $user->id]) }}" method="POST">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-raised-success" name="active">
                ยืนยันข้อมูล
            </button>
            <a href="{{ url('/dashboard/user') }}"> กลับหน้าหลัก </a>
        </form>
    </div>
</div>
@endsection