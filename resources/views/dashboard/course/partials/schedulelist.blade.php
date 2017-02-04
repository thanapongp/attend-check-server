<div>
	<button class="btn btn-raised-primary pull-right">
		<i class="fa fa-download"></i> Export ทั้งหมด
	</button>
	<div class="clearfix"></div>
</div>
<table class="table table-hover">
	<thead>
		<th>วันที่</th>
		<th>เข้าเรียน/สาย/ขาด</th>
		<th></th>
		<th>ดูข้อมูล</th>
	</thead>
	<tbody>
		<tr class="success clickable-row" 
		data-href="{{ url('/dashboard/course/1106209-59/1') }}">
			<td>29 พ.ค. 2017 9:00</td>
			<td>43/2/2</td>
			<td><span class="text-success">กำลังทำการเรียน</span></td>
			<td>
				<a href="{{ url('/dashboard/course/1106209-59/1') }}" class="btn btn-raised-primary">
					ดูข้อมูล
				</a>
			</td>
		</tr>
	</tbody>
</table>