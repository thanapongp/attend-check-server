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
		<span class="pull-right schedule-option dropdown">
			<a id="courseOption" href="#" data-toggle="dropdown">
				<i class="fa fa-cog"></i>
			</a>

			<ul class="dropdown-menu" aria-labelledby="courseOption">
				<li>
					<a href="#" onclick="getFirstToken(event,{{$schedule->id}})">
						แสดง Token สำหรับเช็คชื่อครั้งแรก
					</a>
				</li>
			</ul>
		</span>
	</div>

	<div class="panel-body">
		<div class="row" style="margin-bottom: 1em;">
			<div class="col-sm-6">
				<button class="btn btn-raised-info" 
				data-toggle="modal" 
				data-target="#randomModal" onclick="clearCandidate()">
					<i class="fa fa-random"></i> สุ่มตอบคำถาม
				</button>
			</div>
			<div class="col-sm-6">
				<input class="form-control" type="text" placeholder="ค้นหา" 
				onkeyup="search(this.value)" style="width: 250px; float: right;">
			</div>
		</div>
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
						<a href="#" onclick="manualCheck(event, {{$student->id}}, {{$schedule->id}})" class="manualCheck">
							<span class="text-{{getTextClass($student->isAttended($schedule))}}">
								<i class="check-button fa fa-2x 
								{{getIconClass($student->isAttended($schedule))}}" 
								data-stuid="{{$student->id}}"></i>

								<div class="dropdown" style="display: inline-block;">
									<a id="checkOption{{$student->id}}" href="#"
										data-toggle="dropdown">
										<i class="fa fa-caret-down"></i>
									</a>

									<ul class="dropdown-menu" 
									aria-labelledby="checkOption{{$student->id}}">
										<li>
											<a href="#" 
											onclick="manualCheckWithType(event, {{$student->id}}, {{$schedule->id}}, 2)">
												สาย
											</a>
										</li>
										<li>
											<a href="#" onclick="manualCheckWithType(event, {{$student->id}}, {{$schedule->id}}, 4)">
												ลา
											</a>
										</li>
										<li>
											<a href="#" onclick="manualCheckWithType(event, {{$student->id}}, {{$schedule->id}}, 3)">
												ยกเลิกเช็คชื่อ
											</a>
										</li>
									</ul>
								</div>
							</span>
						</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>


<div id="randomModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">
					สุ่มตอบคำถาม
				</h4>
			</div>

			<div class="modal-body">
				<h1 id="nameDisplay" class="text-center">
					--
				</h1>

				<div>
					<div class="checkbox text-center">
						<label>
							<input type="checkbox" id="chooseMinOnly"> เลือกเฉพาะคนที่ไม่เคยโดนสุ่ม / โดนสุ่มน้อย
						</label>
					</div>

					<div class="text-center">
						<button class="btn btn-info" 
						onclick="randomStudent({{$schedule->id}})">
							<i class="fa fa-random"></i> สุ่ม
						</button>
						<button class="btn btn-info" 
						onclick="addPointToStudent()">
							+1
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<style>
	.manualCheck:hover, .manualCheck:active {
		text-decoration: none;
	}
</style>
<script type="text/javascript">
var firstToken = '';
var currentCandidate = 0;

function getFirstToken(e, scheduleID) {
	e.preventDefault();
	
	if (firstToken == '') {
		axios.post('/dashboard/enable-firstcheck', {
			scheduleID: scheduleID
		})
		.then(function (response) {
			firstToken = response.data;
			swal(firstToken, 'Token สำหรับเช็คชื่อครั้งแรก');
		})
		.catch(function (error) {
			console.log(error);
		});
	} else {
		swal(firstToken, 'Token สำหรับเช็คชื่อครั้งแรก');
	}
}


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

function manualCheckWithType(e, studentID, scheduleID, type) {
	e.preventDefault();

	axios.post('/dashboard/manual-check', {
		userID: studentID,
		scheduleID: scheduleID,
		type: type
	})
	.then(function (response) {
		changeStatusText(response, studentID);
	})
	.catch(function (error) {
		console.log(error);
	});
}

function changeStatusText(response, studentID) {
	if (response.data.includes("check") && response.data != "uncheck") {
		var innertext = 'เข้าเรียน';
		var textClass = 'text-success';
		toastr.success('เช็คชื่อสำเร็จ!');
	}

	if (response.data.includes("late")) {
		var innertext = 'สาย';
		var textClass = 'text-warning';
		toastr.success('เช็คชื่อสำเร็จ!');
	}

	if (response.data.includes("off")) {
		var innertext = 'ลา';
		var textClass = 'text-info';
		toastr.success('เช็คลาสำเร็จ!');

		$("td[data-stuid="+studentID+"]").html(innertext);
		$("i[data-stuid="+studentID+"]").removeClass("fa-check fa-times")
		.addClass("fa-times")
		.parent()
		.removeClass("text-success text-danger text-info")
		.addClass(textClass);
		return;
	}

	if (response.data == "uncheck") {
		var innertext = 'ยังไม่เข้าเรียน';
		var textClass = 'text-info';
		toastr.warning('ยกเลิกการเช็คชื่อสำเร็จ!');
		$("td[data-stuid="+studentID+"]").html(innertext);
		$("i[data-stuid="+studentID+"]").removeClass("fa-check fa-times")
		.addClass("fa-times")
		.parent()
		.removeClass("text-success text-danger text-info")
		.addClass(textClass);
		return;
	}

	$("td[data-stuid="+studentID+"]").html(innertext);

	$("i[data-stuid="+studentID+"]").toggleClass("fa-check fa-times")
	.parent()
	.removeClass("text-success text-danger text-info")
	.addClass(textClass);
}

function search(value) {
	$('td:first-child').each(function () {
		if ($(this).text().includes(value)) {
			$(this).parent().show();
		} else {
			$(this).parent().hide();
		}
	});
}

function randomStudent(id) {
	var randomEndPoint = "/dashboard/random-student?schedule=" + id;

	if ($('#chooseMinOnly').is(':checked')) {
		randomEndPoint = randomEndPoint + '&getLowest=true';
	}

	console.log('randomEndPoint: ' + randomEndPoint);

	axios.get(randomEndPoint)
	.then(function (response) {
		$('#nameDisplay').html(response.data.name);
		currentCandidate = response.data.id;
	});
}

function clearCandidate() {
	$('#nameDisplay').html('--');
	currentCandidate = 0;
}

function addPointToStudent() {
	if (currentCandidate == 0) { return; }
	axios.post('/dashboard/add-point-student', {
		id: currentCandidate
	})
	.then(function (request) {
		toastr.success('+1 ให้ ' + $('#nameDisplay').html());
	});
}
</script>
@endsection