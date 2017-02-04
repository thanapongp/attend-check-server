<?php

namespace AttendCheck\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function showMainPage()
    {
        return view('dashboard.main');
    }
}
