{{-- <div>
	<button class="btn btn-raised-primary pull-right">
		<i class="fa fa-download"></i> Export ทั้งหมด
	</button>
	<div class="clearfix"></div>
</div> --}}
<table class="table table-hover" id="studentstable">
	<thead>
		<th>ชื่อ</th>
		<th>เข้าเรียน</th>
		<th>สาย</th>
		<th>ขาด</th>
		<th>% การขาด</th>
		<th>ดูข้อมูล</th>
	</thead>
	<tbody>
		@inject('record', '\AttendCheck\Services\AttendanceRecordService')
		@foreach($course->students as $student)
		<tr class="clickable-row" 
		data-href="{{ url('/dashboard/student/'. $student->username) }}">
			<td>{{$student->username}} {{$student->fullname()}}</td>
			<td>{{$record->attendanceCount($course, $student)}}</td>
			<td>{{$record->lateCount($course, $student)}}</td>
			<td>{{$record->missingCount($course, $student)}}</td>
			<td><span class="{{
				$record->missingPercentage($course, $student) >= 80 ? 'text-danger' : 'text-success' 
				}}">
				{{$record->missingPercentage($course, $student)}} %
			</span></td>
			<td>
				<a href="{{ url('/dashboard/student/'. $student->username) }}" class="btn btn-raised-primary">
					ดูข้อมูล
				</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>