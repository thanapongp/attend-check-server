<div style="margin-bottom: 2rem">
	<div class="dropdown pull-right">
		<button class="btn btn-raised-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			จัดการรายชื่อนศ.
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
			<li><a href="#">เพิ่มรายชื่อนศ. เอง</a></li>
			<li><a href="#">เพิ่มรายชื่อนศ. จากไฟล์</a></li>
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