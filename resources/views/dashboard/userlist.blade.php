@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<h1 class="dashboard-title">Dashboard</h1>

<div class="panel panel-info dashboard-panel">

    <div class="panel-heading">
        <span>รายชื่อผู้ใช้ที่ยังไม่ยืนยัน</span>
        @if(current_user()->isAdmin())
        <a href="/dashboard/course/add" class="btn btn-raised-info pull-right">
            วิชาในระบบทั้งหมด
        </a>
        @endif
        <a href="/dashboard/course/add" class="btn btn-raised-info pull-right" 
        style="margin-right: 1em">
            ผู้ใช้ทั้งหมด
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

        <table class="table">
            <thead>
                <th>ชื่อสกุล</th><th>ประเภท</th><th>E-mail</th><th>จัดการ</th>
            </thead>
            @if($users->isNotEmpty())
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{$user->fullname()}}</td>
                    <td>{{$user->type->label}}</td>
                    <td>{{$user->email}}</td>
                    <td>
                        <form action="/dashboard/user/{{$user->id}}/approve" method="POST" 
                        style="display: inline;"> 
                            {{csrf_field()}}
                            <button type="submit" class="btn btn-raised-success">
                                <i class="fa fa-check"></i> ยืนยัน
                            </button>
                        </form>
                        <form action="/dashboard/user/{{$user->id}}/delete" method="POST" 
                        style="display: inline;">
                            {{csrf_field()}}
                            <button type="submit" class="btn btn-raised-danger">
                                <i class="fa fa-trash"></i> ลบ
                            </button>
                        </form>
                    </td>
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