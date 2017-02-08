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
		<form action="/dashboard/course/add/search" method="GET" enctype="multipart/form-data" 
		class="form-horizontal dashboard-form">
			
			<div class="row">
				<div class="col-sm-6 form-legend">
					<legend class="text-right">ขั้นที่ 1: ค้นหารายวิชาผ่านระบบ UBU TQF</legend>
				</div>
			</div>

			<div class="form-group">
				<label for="code" class="col-sm-3 control-label">รหัสวิชา</label>
				<div class="col-sm-4">
					<input type="text" name="course" placeholder="รหัสวิชา" 
					class="form-control" required>
				</div>
			</div>


			<div class="form-group">
				<label for="section" class="col-sm-3 control-label">Section</label>
				<div class="col-sm-4">
					<input type="number" name="section" placeholder="Section" 
					class="form-control" required>
				</div>
			</div>

			<div class="form-group">
				<label for="semester" class="col-sm-3 control-label">ภาคการศึกษา</label>
				<div class="col-sm-4">
					<select name="semester" class="form-control">
						<option value="1">ภาคต้น</option>
						<option value="2">ภาคปลาย</option>
						<option value="3">ภาคฤดูร้อน</option>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label for="semester" class="col-sm-3 control-label">ปีการศึกษา</label>
				<div class="col-sm-4">
					<select name="year" class="form-control">
						@for($i  = ((int) date("Y")) + 543 - 1; 
							 $i <= ((int) date("Y")) + 543; 
							 $i++)
						<option value="{{$i}}">{{$i}}</option>
						@endfor
					</select>
				</div>
			</div>

			
			<!-- <div class="row">
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
							สุ่มแบบคละ
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
			</div> -->

			<div class="row">
				<div class="col-sm-3 col-sm-offset-3">
					<button type="submit" class="btn btn-raised-success">
						ค้นหารายวิชา
					</button>
					<a href="#">ยกเลิก</a>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection

@section('js')
<script src="/js/form-createcourse.js"></script>
@endsection