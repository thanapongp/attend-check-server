<?php

namespace AttendCheck\Http\Controllers;

use AttendCheck\User;
use Illuminate\Http\Request;
use AttendCheck\Course\Schedule;

class ScheduleController extends Controller
{
    public function generateFirstCheckCode(Request $request)
    {
        $schedule = Schedule::find($request->scheduleID);

        if (! $schedule->checkin_code) {
            $schedule->checkin_code = bin2hex(openssl_random_pseudo_bytes(ceil(2)));
            $schedule->save();
        }

        return $schedule->fresh()->checkin_code;
    }

    public function getRandomStudent(Request $request)
    {
        $attendees = Schedule::with('attendances')
                    ->get()
                    ->find($request->schedule)
                    ->attendances
                    ->filter(function ($item) {
                        return $item->pivot->type != 3;
                    });

        if ($request->has('getLowest')) {
            $lowestCount = $attendees->min('pickcount');

            $attendees = $attendees->filter(function ($item) use ($lowestCount) {
                return $item->pickcount <= $lowestCount;
            });
        }

        $candidate = $attendees->random();

        return [
            'id' => $candidate->id,
            'name' => $candidate->name . ' ' . $candidate->lastname
        ];
    }

    public function addPointToCandidate(Request $request)
    {
        $candidate = User::findOrFail($request->id);

        $candidate->pickcount = ++$candidate->pickcount;
        $candidate->save();

        return 'OK';
    }
}
