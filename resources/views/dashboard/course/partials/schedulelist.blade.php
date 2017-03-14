<div>
	<button class="btn btn-raised-primary pull-right">
		<i class="fa fa-download"></i> Export ทั้งหมด
	</button>
	<div class="clearfix"></div>
</div>
<table class="table table-hover" id="schedulestable">
	<thead>
		<th>วันที่</th>
		<th>เข้าเรียน/สาย/ขาด</th>
		<th></th>
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
			<td></td>
			<td></td>
			<td>
				<a href="{{ url('/dashboard/course/'. $course->url() .'/'. $schedule->url()) }}" class="btn btn-raised-primary">
					ดูข้อมูล
				</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>