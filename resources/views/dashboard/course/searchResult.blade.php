@extends('layouts.dashboard')

@section('title', 'เพิ่มรายวิชาใหม่')

@section('content')
<h1 class="dashboard-title">เพิ่มรายวิชาใหม่</h1>

<ol class="breadcrumb">
	<li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
	<li class="active">เพิ่มรายวิชาใหม่</li>
</ol>

<div class="panel panel-info dashboard-panel">

	<div class="panel-heading">
		<span>เพิ่มรายวิชาใหม่</span>
	</div>

	<div class="panel-body">
		<form action="/dashboard/course/store" method="POST" 
		class="form-horizontal dashboard-form">
			{{ csrf_field() }}
			<div class="row">
				<div class="col-sm-4 form-legend">
					<legend class="text-right">ผลการค้นหารายวิชา</legend>
				</div>
			</div>

			<div class="form-group">
				<label for="code" class="col-sm-3 control-label">รหัสวิชา</label>
				<div class="col-sm-4">
					<p class="form-control-static">{{ $course->COURSECODE }}</p>
					<input type="hidden" name="code" value="{{ $course->COURSECODE }}">
				</div>
			</div>

			<div class="form-group">
				<label for="code" class="col-sm-3 control-label">ชื่อวิชา</label>
				<div class="col-sm-4">
					<p class="form-control-static">{{ $course->COURSENAME }}</p>
					<input type="hidden" name="name" value="{{ $course->COURSENAME }}">
				</div>
			</div>

			<div class="form-group">
				<label for="section" class="col-sm-3 control-label">Section</label>
				<div class="col-sm-4">
					<p class="form-control-static">{{ $course->SUBDETAIL[0]->SECTION }}</p>
					<input type="hidden" name="section" value="{{ $course->SUBDETAIL[0]->SECTION }}">
				</div>
			</div>

			<div class="form-group">
				<label for="semester" class="col-sm-3 control-label">ภาคการศึกษา</label>
				<div class="col-sm-4">
					<p class="form-control-static">{{ getSemester($course->SEMESTER) }}</p>
					<input type="hidden" name="semester" value="{{ getSemester($course->SEMESTER) }}">
				</div>
			</div>

			<div class="form-group">
				<label for="semester" class="col-sm-3 control-label">ปีการศึกษา</label>
				<div class="col-sm-4">
					<p class="form-control-static">{{ $course->ACADYEAR }}</p>
					<input type="hidden" name="year" value="{{ $course->ACADYEAR }}">
				</div>
			</div>

			@foreach($course->SUBDETAIL as $schedule)
			
			<div class="form-group">
				@if ($loop->first)
				<label for="semester" class="col-sm-3 control-label">เวลาเรียน</label>
				@else
				<label for="semester" class="col-sm-3 control-label"></label>
				@endif
				<div class="col-sm-4">
					<p class="form-control-static">
						{{ $schedule->WEEKDAYNAME }} {{ $schedule->TIME_STR }} {{ $schedule->ROOMCODE }}
					</p>
					<input type="hidden" name="schedules[]" value="{{ $schedule->WEEKDAYNAME }} {{ $schedule->TIME_STR }} {{ $schedule->ROOMCODE }}">
				</div>
			</div>

			@endforeach
			
			<div class="row">
				<div class="col-sm-5 form-legend">
					<legend class="text-right">ขั้นที่ 2: กรอกข้อมูลรายวิชาเพิ่มเติม</legend>
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-3 form-legend">
					<legend class="text-right">ข้อมูลเวลาเรียน</legend>
				</div>
			</div>

			<div class="form-group">
				<label for="start_date" class="col-sm-3 control-label">วันที่เริ่มเรียนคาบแรก</label>
				<div class="col-sm-4">
					<div class='input-group date' id="start_date">
                		<input type='text' class="form-control" name="start_date" placeholder="วันที่เริ่มเรียนคาบแรก" />
                		<span class="input-group-addon">
                    		<span class="fa fa-calendar">
                    		</span>
                		</span>
            		</div>
				</div>
			</div>

			<div class="form-group">
				<label for="end_date" class="col-sm-3 control-label">วันที่เริ่มเรียนคาบสุดท้าย</label>
				<div class="col-sm-4">
					<div class='input-group date' id="end_date">
                		<input type='text' class="form-control" name="end_date" placeholder="วันที่เริ่มเรียนคาบสุดท้าย"/>
                		<span class="input-group-addon">
                    		<span class="fa fa-calendar">
                    		</span>
                		</span>
            		</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-3 form-legend">
					<legend class="text-right">Option รายวิชา</legend>
				</div>
			</div>
			
			<div class="form-group">
				<label for="start_date" class="col-sm-3 control-label">รูปแบบการสุ่มรายชื่อ</label>
				<div class="col-sm-4">
					<div class="radio">
						<label>
							<input type="radio" name="random_method" value="1" checked>
							สุ่มเฉพาะคนที่โดนสุ่มน้อยที่สุด
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="random_method" value="2">
							สุ่มแบบปกติ
						</label>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label for="late_time" class="col-sm-3 control-label">เวลาที่เข้าสายได้ (นาที)</label>
				<div class="col-sm-4">
					<input type="number" name="late_time" placeholder="เวลาที่เข้าสายได้ (นาที)" 
					value="15" class="form-control" required>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-3 col-sm-offset-3">
					<button type="submit" class="btn btn-raised-success">
						เพิ่มรายวิชา
					</button>
					<a href="/dashboard/course/add">ค้นหารายวิชาอื่น</a>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection

@section('js')
<script src="/js/form-createcourse.js"></script>
@endsection
