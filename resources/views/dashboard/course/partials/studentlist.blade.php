{{-- <div>
	<button class="btn btn-raised-primary pull-right">
		<i class="fa fa-download"></i> Export ทั้งหมด
	</button>
	<div class="clearfix"></div>
</div> --}}
<table class="table table-hover">
	<thead>
		<th>ชื่อ</th>
		<th>เข้าเรียน/สาย/ขาด</th>
		<th>% การขาด</th>
		<th>ดูข้อมูล</th>
	</thead>
	<tbody>
		<tr class="clickable-row" 
		data-href="{{ url('/dashboard/student/5611400924') }}">
			<td>5611400924 นายธนะพงศ์ ประทุมชาติ</td>
			<td>3/0/1</td>
			<td><span class="text-success">20%</span></td>
			<td>
				<a href="{{ url('/dashboard/student/5611400924') }}" class="btn btn-raised-primary">
					ดูข้อมูล
				</a>
			</td>
		</tr>
	</tbody>
</table>