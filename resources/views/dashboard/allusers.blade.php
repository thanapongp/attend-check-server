@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<h1 class="dashboard-title">Dashboard</h1>

<div class="panel panel-info dashboard-panel">

    <div class="panel-heading">
        <span>รายชื่อผู้ใช้ที่ยังไม่ยืนยัน</span>
        {{-- @if(current_user()->isAdmin())
        <a href="/dashboard/course/all" class="btn btn-raised-info pull-right">
            วิชาในระบบทั้งหมด
        </a>
        @endif --}}
        <a href="/dashboard" class="btn btn-raised-info pull-right" 
        style="margin-right: 1em">
            ผู้ใช้ที่ต้องยืนยัน
        </a>
        <div class="clearfix"></div>
    </div>

    <div class="panel-body">

        @if(session('status'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <br> 
            {{ session('status') }}
        </div>
        @endif

        <table class="table table-hover" id="allusers">
            <thead>
                <th>ชื่อสกุล</th><th>ประเภท</th><th>E-mail</th>
            </thead>
            @if($users->isNotEmpty())
            <tbody>
            @foreach($users as $user)
                <tr class="clickable-row" 
                data-href="{{  
                    (string) $user->type == 'student'
                    ? url('/dashboard/student/'. $user->username)
                    : url('/dashboard/user/'. $user->id)
                }}">
                    <td>{{$user->fullname()}}</td>
                    <td>{{$user->type->label}}</td>
                    <td>{{$user->email}}</td>
                </tr>
            @endforeach
            </tbody>
            @endif
        </table>
        @if($users->isEmpty())
        <h3 class="text-center">
            ยังไม่มีผู้ใช้ที่ต้องยืนยัน
        </h3>
        @endif
    </div>
</div>
@endsection

@section('js')
<script src="/js/clickablerow.js"></script>
<script>
$(document).ready(() => {
    $('#allusers').DataTable({
        'language' : {
            'url' : '//cdn.datatables.net/plug-ins/1.10.13/i18n/Thai.json'
        }
    });
});
</script>
@endsection