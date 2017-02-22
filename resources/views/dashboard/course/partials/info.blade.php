<form class="form-horizontal dashboard-form">
			
	<div class="row">
		<div class="col-sm-3 form-legend">
			<legend class="text-right">ข้อมูลทั่วไป</legend>
		</div>
	</div>

	<div class="form-group">
		<label for="code" class="col-sm-3 control-label">รหัสวิชา</label>
		<div class="col-sm-4">
			<p class="form-control-static">{{$course->code}}</p>
		</div>
	</div>

	<div class="form-group">
		<label for="name" class="col-sm-3 control-label">ชื่อวิชา</label>
		<div class="col-sm-4">
			<p class="form-control-static">{{$course->name}}</p>
		</div>
	</div>

	<div class="form-group">
		<label for="section" class="col-sm-3 control-label">Section</label>
		<div class="col-sm-4">
			<p class="form-control-static">{{$course->section}}</p>
		</div>
	</div>

	<div class="form-group">
		<label for="semester" class="col-sm-3 control-label">ภาคการศึกษา</label>
		<div class="col-sm-4">
			<p class="form-control-static">{{$course->semester}}</p>
		</div>
	</div>

	<div class="form-group">
		<label for="semester" class="col-sm-3 control-label">ปีการศึกษา</label>
		<div class="col-sm-4">
			<p class="form-control-static">{{$course->year}}</p>
		</div>
	</div>

	<div class="form-group">
		<label for="studentsList" class="col-sm-3 control-label">รายชื่อนักศึกษา</label>
		<div class="col-sm-4">
			<p class="form-control-static"><a href="#students">ดูรายชื่อนักศึกษา</a></p>
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
			@foreach($course->periods as $period)
			<div class="form-inline">
				<p class="form-control-static">
				วัน {{day($period->day)}} 
				เวลา {{date('G:i', strtotime($period->start_time))}} 
				ถึง {{date('G:i', strtotime($period->end_time))}} 
				ห้อง {{$period->room}}</p>
			</div>
			@endforeach
		</div>
	</div>

	{{ \Jenssegers\Date\Date::setLocale('th') }}

	<div class="form-group">
		<label for="start_date" class="col-sm-3 control-label">วันที่เริ่มเรียนคาบแรก</label>
		<div class="col-sm-4">
			<p class="form-control-static">
			{{(new \Jenssegers\Date\Date($course->start_date))->format('j F Y')}}
			</p>
		</div>
	</div>

	<div class="form-group">
		<label for="end_date" class="col-sm-3 control-label">วันที่เริ่มเรียนคาบสุดท้าย</label>
		<div class="col-sm-4">
			<p class="form-control-static">
				{{(new \Jenssegers\Date\Date($course->end_date))->format('j F Y')}}
			</p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-3 form-legend">
			<legend class="text-right">Option รายวิชา</legend>
		</div>
	</div>

	<div class="form-group">
		<label for="late_time" class="col-sm-3 control-label">เวลาที่เข้าสายได้ (นาที)</label>
		<div class="col-sm-4">
			<p class="form-control-static">{{$course->late_time}}</p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-3 col-sm-offset-3">
			<a href="#" class="btn btn-raised-success">
				<i class="fa fa-edit"></i> แก้ไข
			</a>
		</div>
	</div>
</form>