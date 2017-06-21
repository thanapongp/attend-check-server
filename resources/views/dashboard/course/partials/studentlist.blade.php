<div style="margin-bottom: 2rem">
	<div class="dropdown pull-right">
		<button class="btn btn-raised-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			จัดการรายชื่อนศ.
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
			<li>
				<a href="#addForm" data-toggle="modal" data-target="#addForm">
					เพิ่มรายชื่อนศ. เอง
				</a>
			</li>
			<li>
				<a href="#">
					เพิ่มรายชื่อนศ. จากไฟล์
				</a>
			</li>
			<li>
				<a href="{{url("/dashboard/course/{$course->url()}/syncstudents")}}"
				onclick="event.preventDefault(); document.getElementById('syncform').submit();">
					Sync รายชื่อนศ. กับระบบ TQF
				</a>
			</li>
		</ul>
	</div>

	<form action="{{url("/dashboard/course/{$course->url()}/syncstudents#students")}}" method="POST"
	id="syncform">
		{{csrf_field()}}
	</form>

	{{-- addForm --}}
	<div class="modal fade" id="addForm" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">เพิ่มรายชื่อนศ. เอง</h4>
				</div>
				<div class="modal-body">
					<form action="{{url("/dashboard/course/{$course->url()}/addstudent#students")}}" method="POST" class="form-horizontal">
						<div class="form-group">
							<label for="username" class="col-sm-3 control-label">รหัสนศ.</label>
							<div class="col-sm-9">
								<input type="number" class="form-control" name="username" 
								placeholder="รหัสนศ." required>
							</div>
						</div>

						<div class="form-group">
							<label for="title" class="col-sm-3 control-label">คำนำหน้าชื่อ</label>
							<div class="col-sm-9">
								<select name="title" class="form-control" required>
									<option value="">โปรดเลือก</option>
									<option value="นาย">นาย</option>
									<option value="นางสาว">นางสาว</option>
									<option value="นาง">นาง</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="name" class="col-sm-3 control-label">ชื่อ</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="name" 
								placeholder="ชื่อ" required>
							</div>
						</div>

						<div class="form-group">
							<label for="lastname" class="col-sm-3 control-label">นามสกุล</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="lastname" 
								placeholder="นามสกุล" required>
							</div>
						</div>

						{{csrf_field()}}

						<div class="form-group">
							<label class="col-sm-3"></label>
							<div class="col-sm-9">
								<button type="button" class="btn btn-default" 
								data-dismiss="modal">
									ยกเลิก
								</button>
								<button type="submit" class="btn btn-success">
									<i class="fa fa-plus"></i> เพิ่ม
								</button>
							</div>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>

	<button class="btn btn-raised-primary pull-right export-btn" type="button"
	style="margin-right: 1rem">
		<i class="fa fa-download"></i> Export ทั้งหมด
	</button>
	<div class="clearfix"></div>
</div>
<table class="table table-hover" id="studentstable">
	<thead>
		<th>ชื่อ</th>
		<th>เข้าเรียน (ครั้ง)</th>
		<th>สาย (ครั้ง)</th>
		<th>ขาด (ครั้ง)</th>
		<th>% การขาด</th>
		<th>ดูข้อมูล</th>
	</thead>
	<tbody>
		@foreach($course->students as $student)
		<tr class="clickable-row" 
		data-href="{{ url("/dashboard/course/{$course->url()}/student/{$student->username}") }}">
			<td>{{$student->username}} {{$student->fullname()}}</td>
			<td>{{$record->attendanceCount($course, $student)}}</td>
			<td>{{$record->lateCount($course, $student)}}</td>
			<td>{{$record->missingCount($course, $student)}}</td>
			<td><span class="{{
				$record->missingPercentage($course, $student) >= 80 
				? 'text-danger' 
				: 'text-success' 
				}}">
				{{$record->missingPercentage($course, $student)}} %
			</span></td>
			<td>
				<a href="
				{{ url("/dashboard/course/{$course->url()}/student/{$student->username}") }}" 
				class="btn btn-raised-primary">
					ดูข้อมูล
				</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>