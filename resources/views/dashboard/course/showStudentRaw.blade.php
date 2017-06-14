@extends('layouts.dashboard')

@section('title', 'จัดการรายวิชาผู้ใช้')

@section('content')
<h1 class="dashboard-title">
    สถิติการเช็คชื่อ
</h1>

<ol class="breadcrumb">
    <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li>
        <a href="{{ url("/dashboard/course/{$course->url()}#students") }}">
            จัดการรายวิชา {{$course->code}}
        </a>
    </li>
    <li class="active">สถิติการเช็คชื่อของ {{$user->fullname()}}</li>
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

        <h3>สถิติการเช็คชื่อวิชา {{$course->code}} {{$course->name}}</h3>

        <table class="table table-stripped">
            <thead>
                <th>วันที่</th>
                <th>เข้าเรียน</th>
                <th>สาย</th>
                <th>ขาด</th>
            </thead>
            @inject('record', '\AttendCheck\Services\AttendanceRecordService')
            {{ \Jenssegers\Date\Date::setLocale('th') }}
            <tbody>
            @foreach($course->schedules as $schedule)
            @php
            $attendance = $user->attendances->where('id', $schedule->id)->flatten()->first();
            @endphp
                <tr>
                    <td>
                    {{(new \Jenssegers\Date\Date($schedule->start_date))->format('j F Y H:i')}}
                    </td>
                    <td>
                        @if($attendance && 
                           ($attendance->pivot->type == 1 || $attendance->pivot->type == 2))
                        <i class="fa fa-check"></i>
                        @endif
                    </td>
                    <td>
                        @if($attendance && $attendance->pivot->type == 2)
                        <i class="fa fa-check"></i>
                        @endif
                    </td>
                    <td>
                        @if(!$attendance || ($attendance->pivot->type == 3 || $attendance->pivot->type == 4))
                            <i class="fa fa-check"></i>
                        @endif
                    </td>
                </tr>
            @endforeach
                <tr>
                    <td>สรุป</td>
                    <td>{{$record->attendanceCount($course, $user)}} ครั้ง</td>
                    <td>{{$record->lateCount($course, $user)}} ครั้ง</td>
                    <td>{{$record->missingCount($course, $user)}} ครั้ง 
                    ({{$record->missingPercentage($course, $user)}} %)</td>
                </tr>
            </tbody>
        </table>
        @if($user->attendances->isEmpty())
        <h3 class="text-center">
            นักศึกษาคนนี้ยังไม่มีรายวิชาในระบบ
        </h3>
        @endif
    </div>
</div>
@endsection