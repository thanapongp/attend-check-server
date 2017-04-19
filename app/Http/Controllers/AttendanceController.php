<?php

namespace AttendCheck\Http\Controllers;

use AttendCheck\User;
use Illuminate\Http\Request;
use AttendCheck\Course\Course;
use AttendCheck\Course\Schedule;
use AttendCheck\Course\Attendance;

class AttendanceController extends Controller
{
    public function attendClass(Request $request)
    {
        $user = User::find($request->userID);
        $schedule = Schedule::find($request->scheduleID);

        if ($request->has('type')) {
            return $user->attend($schedule, $request->type);
        }

        return $user->attend($schedule);
    }
}
