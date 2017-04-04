<?php

namespace AttendCheck\Http\Controllers;

use Illuminate\Http\Request;
use AttendCheck\Course\Schedule;

class ScheduleController extends Controller
{
    public function generateFirstCheckCode(Request $request)
    {
        $schedule = Schedule::find($request->scheduleID);

        if (! $schedule->checkin_code) {
            $schedule->checkin_code = bin2hex(openssl_random_pseudo_bytes(ceil(5 / 2)));
            $schedule->save();
        }

        return $schedule->fresh()->checkin_code;
    }
}
