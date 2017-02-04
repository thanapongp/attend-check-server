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
		<form action="#" method="POST" enctype="multipart/form-data" 
		class="form-horizontal dashboard-form">
			
			<div class="row">
				<div class="col-sm-3 form-legend">
					<legend class="text-right">ข้อมูลทั่วไป</legend>
				</div>
			</div>

			<div class="form-group">
				<label for="code" class="col-sm-3 control-label">รหัสวิชา</label>
				<div class="col-sm-4">
					<input type="text" name="code" placeholder="รหัสวิชา" 
					class="form-control" required>
				</div>
			</div>

			<div class="form-group">
				<label for="name" class="col-sm-3 control-label">ชื่อวิชา</label>
				<div class="col-sm-4">
					<input type="text" name="name" placeholder="ชื่อวิชา" 
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
					<select name="semester" class="form-control">
						@for($i  = ((int) date("Y")) + 543 - 1; 
							 $i <= ((int) date("Y")) + 543; 
							 $i++)
						<option value="{{$i}}">{{$i}}</option>
						@endfor
					</select>
				</div>
			</div>

			<div class="form-group">
				<label for="studentsList" class="col-sm-3 control-label">รายชื่อนักศึกษา</label>
				<div class="col-sm-4">
					<label class="btn btn-raised-default" style="display: inline-block;">
    					เลือกไฟล์ <input type="file" 
    					accept=".xls,.xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" 
    					style="display: none;">
					</label>
					<span id="filename">ยังไม่ได้เลือกไฟล์</span>
					<span class="help-block">
					สามารถอัพโหลดภายหลังได้ 
					<a href="#">ดูรูปแบบไฟล์ Excel ที่เหมาะสม</a></span>
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-3 form-legend">
					<legend class="text-right">ข้อมูลเวลาเรียน</legend>
				</div>
			</div>

			<div class="form-group">
				<label for="times" class="col-sm-3 control-label">เวลาเรียน</label>
				<div class="col-sm-9">
					<div class="form-inline">
						<div class="form-group">
							<label for="day">วัน</label>
							<select name="day[]" class="form-control">
								@foreach(getDaysOfWeek() as $value => $day)
								<option value="{{$value}}">{{$day}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							เวลา 
							<select name="start_time[]" class="form-control">
								@foreach(hoursRange(800, 1800) as $value => $time)
								<option value="{{$value}}">{{$time}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							ถึง
							<select name="end_time[]" class="form-control">
								@foreach(hoursRange(900, 2000) as $value => $time)
								<option value="{{$value}}">{{$time}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							ห้อง
							<select name="room[]" class="form-control">
								<option value="SC412">SC412</option>
							</select>
						</div>
					</div>

					<span class="help-block">
						<a href="#">+ เพิ่มเวลาเรียน</a>
					</span>
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
			</div>

			<div class="row">
				<div class="col-sm-3 col-sm-offset-3">
					<button type="submit" class="btn btn-raised-success">
						เพิ่มรายวิชา
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