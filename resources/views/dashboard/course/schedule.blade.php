@extends('layouts.dashboard')

@section('title', 'จัดการรายวิชา 1106209-59')

@section('content')
<h1 class="dashboard-title">
	จัดการรายวิชา
	<br>
	<small>1106209 Information System Security (2559 ภาคปลาย Sec.1)</small>
</h1>

<ol class="breadcrumb">
	<li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
	<li><a href="{{ url('/dashboard/course/1106209-59') }}">1106209 Information System Security (2559 ภาคปลาย Sec.1)</a></li>
	<li class="active">คาบ 29 พ.ค. 2017 9:00</li>
</ol>

<div class="panel panel-info dashboard-panel">

	<div class="panel-heading">
		<span>คาบ 29 พ.ค. 2017 9:00</span>
	</div>

	<div class="panel-body">
		<table class="table table-hover">
			<thead>
				<th>ชื่อ</th>
				<th>สถานะ</th>
				<th>เช็คชื่อ</th>
			</thead>
			<tbody>
				<tr>
					<td><a href="{{ url('/dashboard/student/5611400924') }}">5611400924 นายธนะพงศ์ ประทุมชาติ</a></td>
					<td><span class="text-success">เข้าเรียน</span></td>
					<td>
						<span class="text-success">
							<i class="check-button fa fa-2x fa-check" data-stuid="5611400924"></i>
						</span>
					</td>
				</tr>
				<tr>
					<td><a href="{{ url('/dashboard/student/5611400924') }}">5611400939 นายธนัช ประทุมชาติ</a></td>
					<td><span class="text-danger">ยังไม่เข้าเรียน</span></td>
					<td>
						<span class="text-danger">
							<i class="check-button fa fa-2x fa-times" data-stuid="5611400924"></i>
						</span>
					</td>
				</tr>
				<tr>
					<td><a href="{{ url('/dashboard/student/5611400924') }}">5611400946 นายธัชตะวัน จันทร์จำลอง</a></td>
					<td><span class="text-warning">สาย</span></td>
					<td>
						<span class="text-success">
							<i class="check-button fa fa-2x fa-check" data-stuid="5611400924"></i>
						</span>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
@endsection

@section('js')
@endsection