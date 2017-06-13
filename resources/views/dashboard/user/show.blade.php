@extends('layouts.dashboard')

@section('title', 'จัดการรายวิชาผู้ใช้')

@section('content')
<h1 class="dashboard-title">
    จัดการรายผู้ใช้
</h1>

<ol class="breadcrumb">
    <li><a href="{{ previousLink() }}">Dashboard</a></li>
    <li class="active">ข้อมูลผู้ใช้</li>
</ol>

<div class="panel panel-info dashboard-panel">

    <div class="panel-heading">
        <span>ข้อมูลผู้ใช้</span>
    </div>

    <div class="panel-body">
        <h3>
            ชื่อ: {{ $user->fullname() }} <br>
            <small>
            สถานะ: {!! 
            $user->active ? '<span class="text-success">ยืนยันแล้ว</span>' 
            : '<span class="text-danger">ยังไม่ยืนยัน</span>'
            !!} <br>
            ประเภท: {{ $user->type->label }} <br>
            คณะ: {{ $user->faculty }} <br>
            E-mail: {{ $user->email }}
            </small>
        </h3>

        <h3>รายวิชาในระบบ</h3>

        @if((string) $user->type == 'teacher')
        <table class="table">
            <thead>
                <th>รหัสวิชา</th><th>ชื่อวิชา</th><th>ปีการศึกษา</th>
            </thead>
            @if(! $user->courses->isEmpty())
            <tbody>
            @foreach($user->courses as $course)
                <tr>
                    <td>{{$course->code}}</td><td>{{$course->name}} (Section {{$course->section}})</td>
                    <td>{{$course->year}} ({{$course->semester}})</td>
                    </td>
                </tr>
            @endforeach
            </tbody>
            @endif
        </table>
        @if($user->courses->isEmpty())
        <h3 class="text-center">
            อาจารย์ท่านนี้ยังไม่มีรายวิชาในระบบ
        </h3>
        @endif
        @endif
    </div>
</div>
@endsection