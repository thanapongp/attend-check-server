<?php

namespace AttendCheck\Services;

class AttendanceRecordService
{
    /**
     * Get attendance count of specified course from user.
     * 
     * @param  \AttendCheck\Course\Course $course
     * @param  \AttendCheck\User $user
     * @return int
     */
    public function attendanceCount($course, $user)
    {
        return $user->attendances->filter(function ($attendance) use ($course)  {
            return ($attendance->course_id == $course->id) && 
                   ($attendance->pivot->type == 1 || $attendance->pivot->type == 2
                    || $attendance->pivot->type == 4);
        })->count();
    }

    public function attendancePercentage($course, $user)
    {
        $schedulesCount = $course->schedules()->alreadyStarted()->get()->count();
        $attendanceCount = $this->attendanceCount($course, $user);

        return $schedulesCount == 0 
               ? 0 
               : round(($attendanceCount / $schedulesCount) * 100);
    }

    /**
     * Get late count of specified course from user.
     * 
     * @param  \AttendCheck\Course\Course $course
     * @param  \AttendCheck\User $user
     * @return int
     */
    public function lateCount($course, $user)
    {
        return $user->attendances->filter(function ($attendance) use ($course)  {
            return ($attendance->course_id == $course->id) && 
                   ($attendance->pivot->type == 2);
        })->count();
    }

    /**
     * Get missing count of specified course from user.
     * 
     * @param  \AttendCheck\Course\Course $course
     * @param  \AttendCheck\User $user
     * @return int
     */
    public function missingCount($course, $user)
    {
        return max($course->schedules()->alreadyStarted()->get()->count() 
                - $this->attendanceCount($course, $user), 0);
    }

    /**
     * Get missing percentage of specified course from user.
     * 
     * @param  \AttendCheck\Course\Course $course
     * @param  \AttendCheck\User $user
     * @return int | float
     */
    public function missingPercentage($course, $user)
    {
        $schedulesCount = $course->schedules()->alreadyStarted()->get()->count();
        $missingCount = $this->missingCount($course, $user);
        
        return $schedulesCount == 0 
               ? 0 
               : round(($missingCount / $schedulesCount) * 100);
    }

    /**
     * Get attendance record data for using in excel document.
     * 
     * @param  \AttendCheck\Course\Course $course
     * @param  \AttendCheck\User $user 
     * @return \Illuminate\Support\Collection Collection contains 'yes' or 'no' or 'late'.
     */
    public function getExcelFormat($course, $user)
    {
        return $course->schedules->map(function ($schedule) use ($user)  {
            if (! $user->attendances->contains('pivot.schedule_id', $schedule->id)) {
                return 'no';
            }

            $schedule = $user->attendances
                             ->where('pivot.schedule_id', $schedule->id)
                             ->first();

            if ($schedule->pivot->type == '3') {
                return 'no';
            }

            if ($schedule->pivot->type == '4') {
                return 'absence';
            }

            return $schedule->pivot->type == '2' ? 'late' : 'yes';
        })->merge([
            $this->attendanceCount($course, $user),
            $this->lateCount($course, $user),
            $this->missingCount($course, $user),
            $this->attendancePercentage($course, $user) . ' %',
            $this->missingPercentage($course, $user) . ' %',
        ])->prepend([
            $user->username,
            $user->fullname(),
        ])->flatten();
    }

    /**
     * Get attendance count of specified schedule.
     * 
     * @param  \AttendCheck\Course\Schedule $course
     * @return int
     */
    public function scheduleAttendanceCount($schedule)
    {
        return $schedule->attendances()->get()
            ->filter(function ($attendance) use ($schedule)  {
                return ($attendance->pivot->schedule_id == $schedule->id) && 
                   ($attendance->pivot->type == 1 || $attendance->pivot->type == 2);
            })->count();
    }

    /**
     * Get late count of specified schedule.
     * 
     * @param  \AttendCheck\Course\Schedule $course
     * @return int
     */
    public function scheduleLateCount($schedule)
    {
        return $schedule->attendances()->get()
            ->filter(function ($attendance) use ($schedule)  {
                return ($attendance->pivot->schedule_id == $schedule->id) && 
                   ($attendance->pivot->type == 2);
            })->count();
    }

    /**
     * Get missing count of specified schedule.
     * 
     * @param  \AttendCheck\Course\Schedule $course
     * @return int
     */
    public function scheduleMissingCount($schedule)
    {
        $studentsCount = $schedule->course->students->count();
        $attendanceCount = $this->scheduleAttendanceCount($schedule);

        return $studentsCount - $attendanceCount;
    }
}
