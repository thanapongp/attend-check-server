@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<h1 class="dashboard-title">Dashboard</h1>

<div class="panel panel-info dashboard-panel">

	<div class="panel-heading">
		<span>รายชื่อวิชาที่สอน</span>
		<a href="/dashboard/course/add" class="btn btn-raised-success pull-right">
			เพิ่มรายวิชาใหม่
		</a>
		<div class="clearfix"></div>
	</div>

	<div class="panel-body">

	@if(session('status'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ไม่สามารถ Login ได้
            </strong> 
            <br> 
            {{ session('status') }}
        </div>
        @endif

		<table class="table">
			<thead>
				<th>รหัสวิชา</th><th>ชื่อวิชา</th><th>ปีการศึกษา</th><th>จัดการ</th>
			</thead>
			@if(!$courses->isEmpty())
			<tbody>
			@foreach($courses as $course)
				<tr>
					<td>{{$course->code}}</td><td>{{$course->name}} (Section {{$course->section}})</td>
					<td>{{$course->year}} ({{$course->semester}})</td>
					<td>
						<a href="/dashboard/course/{{$course->url()}}" class="btn btn-raised-primary">
							จัดการ
						</a>
					</td>
				</tr>
			@endforeach
			</tbody>
			@endif
		</table>
		@if($courses->isEmpty())
		<h3 class="text-center">
			คุณยังไม่มีรายวิชาในระบบ
		</h3>
		@endif
	</div>
</div>
@endsection