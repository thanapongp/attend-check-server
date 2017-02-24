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
		<table class="table table-hover" id="studentstable">
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
					<td>{{$student->attendStatus($schedule)}}</td>
					<td>
						<form action="{{ url('/dashboard/manual-check') }}" method="POST">
						{{csrf_field()}}
						<a href="#" onclick="$(this).closest('form').submit(); return false;">
							<span class="text-{{$student->isAttended($schedule) ? 'success' : 'danger'}}">
								<i class="check-button fa fa-2x{{$student->isAttended($schedule) ? ' fa-check' : ' fa-times'}}" 
								data-stuid="5611400924"></i>
							</span>
						</a>
						<input type="hidden" name="userID" value="{{$student->id}}">
						<input type="hidden" name="scheduleID" value="{{$schedule->id}}">
						</form>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function(){
    $('#studentstable').DataTable({
    	'language' : {
    		'url' : '//cdn.datatables.net/plug-ins/1.10.13/i18n/Thai.json'
    	}
    });
});
</script>
@endsection