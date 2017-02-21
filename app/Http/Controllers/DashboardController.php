<?php

namespace AttendCheck\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function showMainPage()
    {
        switch ((string) Auth::user()->type) {
            case 'admin':
                return $this->showTeacherDashboard();
            case 'teacher':
                return $this->showTeacherDashboard();
            case 'student':
                return $this->showStudentDashboard();
        }
    }

    public function showTeacherDashboard()
    {
        return view('dashboard.main', [
            'courses' => Auth::user()->courses()->get()
        ]);
    }
}
