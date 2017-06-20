@if(session('status'))
<div class="alert alert-info alert-dismissible" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	<span aria-hidden="true">&times;</span></button>
		{{session('status')}}
</div>
@endif

<div style="margin-bottom: 2rem;">
	<button class="btn btn-raised-primary" type="button" data-toggle="modal" 
	data-target="#newScheduleModal">
		<i class="fa fa-plus"></i> เพิ่มคาบเรียน
	</button>
	<button class="btn btn-raised-primary pull-right export-btn" type="button">
		<i class="fa fa-download"></i> Export ทั้งหมด
	</button>
	<div class="clearfix"></div>
</div>
<table class="table table-hover" id="schedulestable">
	<thead>
		<th>วันที่</th>
		<th>เข้าเรียน (คน)</th>
		<th>สาย (คน)</th>
		<th>ขาด (คน)</th>
		<th>ดูข้อมูล</th>
	</thead>
	<tbody>
		@foreach($course->schedules()->ordered()->get() as $schedule)
		<tr class="{{$schedule->inProgress() ? 'success ' : ''}}clickable-row" 
		data-href="{{ url('/dashboard/course/'. $course->url() .'/'. $schedule->url()) }}">
			{{ \Jenssegers\Date\Date::setLocale('th') }}
			<td>
			{{(new \Jenssegers\Date\Date($schedule->start_date))->format('j F Y H:i')}} ห้อง {{$schedule->room}}
			</td>
			<td>{{$record->scheduleAttendanceCount($schedule)}}</td>
			<td>{{$record->scheduleLateCount($schedule)}}</td>
			<td>{{$record->scheduleMissingCount($schedule)}}</td>
			<td>
				<a href="{{ url('/dashboard/course/'. $course->url() .'/'. $schedule->url()) }}" class="btn btn-raised-primary">
					ดูข้อมูล
				</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

<div class="modal fade" id="newScheduleModal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>

				<h4 class="modal-title" id="myModalLabel">เพิ่มคาบเรียน</h4>
			</div>

			<div class="modal-body">
				<form method="post" 
				action="{{ url("/dashboard/course/{$course->url()}/addschedule") }}">
					<div class="form-group">
						<label for="room">ห้อง</label>
						<input type="text" class="form-control" name="room" placeholder="ห้อง"
						required >
					</div>
					<div class="form-group">
						<label for="end_date">วันที่</label>
						<div class='input-group date date-pick'>
							<input type='text' class="form-control" name="date" 
							placeholder="วันที่" required />
							<span class="input-group-addon">
								<span class="fa fa-calendar">
								</span>
							</span>
						</div>
					</div>
					<div class="form-group">
						<label for="end_date">ตั้งแต่เวลา</label>
						<div class='input-group date time' id="start_time">
							<input type='text' class="form-control" name="start_time" 
							placeholder="ตั้งแต่เวลา" required />
							<span class="input-group-addon">
								<span class="fa fa-clock-o">
								</span>
							</span>
						</div>
					</div>
					<div class="form-group">
						<label for="end_date">ถึงเวลา</label>
						<div class='input-group date time' id="end_time">
							<input type='text' class="form-control" name="end_time" 
							placeholder="ถึงเวลา" required />
							<span class="input-group-addon">
								<span class="fa fa-clock-o">
								</span>
							</span>
						</div>
					</div>
					{{csrf_field()}}
					<button type="button" class="btn btn-default" data-dismiss="modal">
						ยกเลิก
					</button>
					<button type="submit" class="btn btn-success">
						<i class="fa fa-plus"></i> เพิ่มคาบ
					</button>
				</form>
			</div>
		</div>
	</div>
</div>