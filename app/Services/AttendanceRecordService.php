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

        return $schedulesCount == 0 ? 0 : ($missingCount / $schedulesCount) * 100;
    }
}
