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
		<span>คาบ {{(new \Jenssegers\Date\Date($schedule->start_date))->format('j F Y H:i')}} ห้อง {{$schedule->room}}</span>
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
					<td data-stuid="{{$student->id}}">{{$student->attendStatus($schedule)}}</td>
					<td>
						<a href="#" onclick="manualCheck(event, {{$student->id}}, {{$schedule->id}})">
							<span class="text-{{$student->isAttended($schedule) ? 'success' : 'danger'}}">
								<i class="check-button fa fa-2x{{$student->isAttended($schedule) ? ' fa-check' : ' fa-times'}}" 
								data-stuid="{{$student->id}}"></i>
							</span>
						</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
function manualCheck(e, studentID, scheduleID) {
	e.preventDefault();

	axios.post('/dashboard/manual-check', {
		userID: studentID,
		scheduleID: scheduleID
	})
	.then(function (response) {
		changeStatusText(response, studentID);
	})
	.catch(function (error) {
		console.log(error);
	});
}

function changeStatusText(response, studentID) {
	if (response.data.includes("check")) {
		var innertext = 'เข้าเรียน';
	}

	if (response.data.includes("late")) {
		var innertext = 'สาย';
	}

	if (response.data == "uncheck") {
		var innertext = 'ยังไม่เข้าเรียน';
	}

	$("td[data-stuid="+studentID+"]").html(innertext);

	$("i[data-stuid="+studentID+"]").toggleClass("fa-check fa-times")
	.parent()
	.toggleClass("text-success text-danger");
}
</script>
@endsection