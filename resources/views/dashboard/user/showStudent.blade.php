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
            ประเภท: {{ $user->type->label }} <br>
            คณะ: {{ $user->faculty }} <br>
            E-mail: {{ $user->email }}
            </small>
        </h3>

        <h3>รายวิชาที่ลงทะเบียนในระบบ</h3>

        <table class="table table-stripped">
            <thead>
                <th>รหัสวิชา</th>
                <th>ชื่อวิชา</th>
                <th>ปีการศึกษา</th>
                <th>เข้าเรียน (ครั้ง)</th>
                <th>สาย (ครั้ง)</th>
                <th>ขาด (ครั้ง)</th>
                <th>% การขาด</th>
            </thead>
            @if(! $user->enrollments->isEmpty())
            @inject('record', '\AttendCheck\Services\AttendanceRecordService')
            <tbody>
            @foreach($user->enrollments as $course)
                <tr>
                    <td>{{$course->code}}</td>
                    <td>{{$course->name}} (Section {{$course->section}})</td>
                    <td>{{$course->year}} ({{$course->semester}})</td>
                    <td>{{$record->attendanceCount($course, $user)}}</td>
                    <td>{{$record->lateCount($course, $user)}}</td>
                    <td>{{$record->missingCount($course, $user)}}</td>
                    <td>{{$record->missingPercentage($course, $user)}} %</td>
                </tr>
            @endforeach
            </tbody>
            @endif
        </table>
        @if($user->enrollments->isEmpty())
        <h3 class="text-center">
            นักศึกษาคนนี้ยังไม่มีรายวิชาในระบบ
        </h3>
        @endif
    </div>
</div>
@endsection