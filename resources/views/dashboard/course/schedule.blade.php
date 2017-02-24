@extends('layouts.dashboard')

@section('title', 'จัดการรายวิชา 1106209-59')

@section('content')
<h1 class="dashboard-title">
	จัดการรายวิชา
	<br>
	<small>{{$course->code}} {{$course->name}} ({{$course->year}} {{$course->semester}} Sec.{{$course->section}})</small>
</h1>

<ol class="breadcrumb">
	<li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
	<li><a href="{{ url('/dashboard/course/'. $course->url()) }}">{{$course->code}} {{$course->name}} ({{$course->year}} {{$course->semester}} Sec.{{$course->section}})</a></li>
	
	{{ \Jenssegers\Date\Date::setLocale('th') }}
	<li class="active">คาบ {{(new \Jenssegers\Date\Date($schedule->start_date))->format('j F Y H:i')}}</li>
</ol>

<div class="panel panel-info dashboard-panel">

	<div class="panel-heading">
		<span>คาบ {{(new \Jenssegers\Date\Date($schedule->start_date))->format('j F Y H:i')}}</span>
	</div>

	<div class="panel-body">
		<table class="table table-hover">
			<thead>
				<th>ชื่อ</th>
				<th>สถานะ</th>
				<th>เช็คชื่อ</th>
			</thead>
			<tbody>
				@foreach($course->students as $student)
				<tr>
					<td>
						<a href="{{ url('/dashboard/student/'. $student->username) }}">
						{{$student->username}} {{$student->fullname()}}
						</a>
					</td>
					<td>{{-- <span class="text-success">เข้าเรียน</span> --}}</td>
					<td>
						<span class="text-success">
							<i class="check-button fa fa-2x fa-check" 
							data-stuid="5611400924"></i>
						</span>
					</td>
				</tr>
				@endforeach
{{-- 				<tr>
					<td><a href="{{ url('/dashboard/student/5611400924') }}">5611400939 นายธนัช ประทุมชาติ</a></td>
					<td><span class="text-danger">ยังไม่เข้าเรียน</span></td>
					<td>
						<span class="text-danger">
							<i class="check-button fa fa-2x fa-times" data-stuid="5611400924"></i>
						</span>
					</td>
				</tr>
				<tr>
					<td><a href="{{ url('/dashboard/student/5611400924') }}">5611400946 นายธัชตะวัน จันทร์จำลอง</a></td>
					<td><span class="text-warning">สาย</span></td>
					<td>
						<span class="text-success">
							<i class="check-button fa fa-2x fa-check" data-stuid="5611400924"></i>
						</span>
					</td>
				</tr> --}}
			</tbody>
		</table>
	</div>
</div>
@endsection

@section('js')
@endsection