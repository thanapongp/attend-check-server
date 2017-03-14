<?php

namespace AttendCheck;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the user's type
     * 
     * @return \AttendCheck\UserType
     */
    public function type()
    {
        return $this->belongsTo('AttendCheck\UserType');
    }

    /**
     * Get the user's faculty
     * 
     * @return \AttendCheck\Faculty
     */
    public function faculty()
    {
        return $this->belongsTo('AttendCheck\Faculty');
    }

    public function courses()
    {
        return $this->hasMany('AttendCheck\Course\Course', 'teacher_id');
    }

    public function enrollments()
    {
        return $this->belongsToMany(
            'AttendCheck\Course\Course', 'enrollments', 'student_id'
        );
    }

    public function attendances()
    {
        return $this->belongsToMany(
            'AttendCheck\Course\Schedule', 'attendances', 'student_id', 'schedule_id'
        )->withPivot('late', 'in_time')->using('AttendCheck\Course\Attendance');
    }

    public function fullname()
    {
        return $this->title . $this->name . ' ' . $this->lastname;
    }

    public function approve()
    {
        if (! $this->active) {
            $this->active = true;
            $this->save();
        }
    }

    public function isAttended($schedule)
    {
        return $this->attendances->contains(function ($attendance) use ($schedule)  {
            return $attendance->pivot->schedule_id == $schedule->id;
        });
    }

    public function attend($schedule)
    {
        if ($this->isAttended($schedule)) {
            return $this->attendances()->detach($schedule->id);
        }

        $now = \Carbon\Carbon::now();
        $starttime = $schedule->start_date;
        $latetime = $schedule->course->latetime;

        $isLate = ($now->diffInMinutes($starttime->addMinute($latetime))) > $latetime;
        $in_time = $now->toDateTimeString();

        $this->attendances()->attach($schedule->id, [
            'late' => $isLate,
            'in_time' => $in_time
        ]);
    }

    public function attendStatus($schedule)
    {
        if (! $this->isAttended($schedule)) {
            return 'ยังไม่เข้าเรียน';
        }

        return $this->attendances->where('id', $schedule->id)->first()->pivot->late 
                ? 'สาย' : 'เข้าเรียน';
    }

    public function enroll($course)
    {
        $this->enrollments()->attach($course->id);
    }
}
