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
	<li class="active">{{$course->code}} {{$course->name}} ({{$course->year}} {{$course->semester}} Sec.{{$course->section}})</li>
</ol>

<div class="panel panel-info dashboard-panel">

	<div class="panel-heading">
		<span>จัดการรายวิชา</span>
	</div>

	<div class="panel-body">

		{{-- tabs list --}}
		<ul class="nav nav-tabs" id="coursetab">
			<li class="active">
				<a href="#schedule" aria-controls="schedule" role="tab" data-toggle="tab">
					การเช็คชื่อ
				</a>
			</li>
			<li>
				<a href="#students" aria-controls="students" role="tab" data-toggle="tab">
					รายชื่อนักศึกษา
				</a>
			</li>
			<li>
				<a href="#info" aria-controls="info" role="tab" data-toggle="tab">
					ข้อมูลรายวิชา
				</a>
			</li>
		</ul>
		{{-- tabs list --}}

		{{-- tabs content --}}
		@inject('record', '\AttendCheck\Services\AttendanceRecordService')
		<div class="tab-content dashboard-tab">
			@if(session('status'))
			<div class="alert alert-info alert-dismissible" role="alert" style="margin: 1rem">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				{{session('status')}}
			</div>
			@endif
			<div class="tab-pane fade in active" id="schedule">
				@include('dashboard.course.partials.schedulelist')
			</div>
			<div class="tab-pane fade in" id="students">
				@include('dashboard.course.partials.studentlist')
			</div>
			<div class="tab-pane fade in" id="info">
				@include('dashboard.course.partials.info')
			</div>
		</div>
		{{-- tabs content --}}
	</div>
</div>

<form id="exportForm" action="/dashboard/course/{{$course->url()}}/export" 
	method="POST" style="display: none;">
	{{csrf_field()}}
</form>
@endsection

@section('js')
<script src="/js/clickablerow.js"></script>
<script>
$(document).ready(() => {
	$('#studentstable').DataTable({
		'language' : {
			'url' : '//cdn.datatables.net/plug-ins/1.10.13/i18n/Thai.json'
		}
	});

	$('.export-btn').click(e => { $('#exportForm').submit(); });

	$('.date-pick').datetimepicker({
		locale: 'th',
		format: 'D MMM YYYY',
		useCurrent: false
	});

	$('.time').datetimepicker({
		locale: 'th',
		format: 'H:mm',
		useCurrent: false
	});

	$('#start_time').on('dp.change', function (e) {
		$('#end_time').data('DateTimePicker').minDate(e.date);
	});

	$('#end_time').on('dp.change', function (e) {
		$('#start_time').data('DateTimePicker').maxDate(e.date);
	});
});
</script>
@endsection