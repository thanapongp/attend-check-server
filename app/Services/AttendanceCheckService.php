<?php

namespace AttendCheck\Services;

use Carbon\Carbon;
use AttendCheck\User;
use AttendCheck\Course\Schedule;
use Illuminate\Support\Facades\DB;

class AttendanceCheckService
{
    /**
     * Instance of user.
     * 
     * @var \AttendCheck\User
     */
    protected $user;

    /**
     * Create new instance of class.
     * 
     * @param \AttendCheck\User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Initialize variables before actually perform check.
     * 
     * @param  \AttendCheck\Course\Schedule $schedule
     * @return String
     */
    public function check(Schedule $schedule)
    {
        $isLate = $this->checkIfLate($schedule);

        if ($type = $this->user->isAttended($schedule)) {
            return $this->changeAttendanceType($type, $isLate, $schedule);
        }

        $lastInsertID = $this->performCheck($isLate, $schedule);

        return $isLate ? 'late'.$lastInsertID : 'check'.$lastInsertID;
    }

    /**
     * Check if user enter the class late.
     * 
     * @param  \AttendCheck\Course\Schedule $schedule
     * @return boolean
     */
    private function checkIfLate(Schedule $schedule)
    {
        $now = Carbon::now();

        $starttime = $schedule->start_date;
        $latetime  = $schedule->course->latetime;

        $timeAfterClassStarted = $now->diffInMinutes($starttime->addMinute($latetime));

        return $now < $starttime ? false : $timeAfterClassStarted > $latetime;
    }

    /**
     * Change attendance type if the user has already attended the class.
     * 
     * @param  int      $type
     * @param  boolean  $isLate
     * @param  \AttendCheck\Course\Schedule $schedule
     * @return String
     */
    private function changeAttendanceType($type, $isLate, Schedule $schedule)
    {
        $attendance = $this->user->attendances()
                                 ->where('schedule_id', $schedule->id)
                                 ->first();

        if ($type == 1 || $type == 2) {
            $attendance->pivot->type = 3;
            $attendance->pivot->save();

            return 'uncheck';
        } else {
            $attendance->pivot->type = $isLate ? 2 : 1;
            $attendance->pivot->save();

            return $isLate ? 'late': 'check';
        }
    }

    /**
     * Actually perform check.
     * 
     * @param  boolean   $isLate
     * @param  \AttendCheck\Course\Schedule $schedule
     * @return String
     */
    private function performCheck($isLate, Schedule $schedule)
    {
        $in_time = Carbon::now()->toDateTimeString();

        $this->user->attendances()->attach($schedule->id, [
            'type' => $isLate ? 2 : 1,
            'in_time' => $in_time
        ]);

        return DB::getPdo()->lastInsertId();
    }
}
