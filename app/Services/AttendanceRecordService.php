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
                   ($attendance->pivot->type == 1 || $attendance->pivot->type == 2);
        })->count();
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
        $cancelCheckCount = $user->attendances
            ->filter(function ($attendance) use ($course)  {
            return ($attendance->course_id == $course->id) && 
                   ($attendance->pivot->type == 3);
            })->count();

        return max($course->schedules()->alreadyStarted()->get()->count() 
                - $this->attendanceCount($course, $user), 0) + $cancelCheckCount;
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

        return $schedulesCount == 0 ? 0 : ($missingCount / $schedulesCount) * 100;
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
