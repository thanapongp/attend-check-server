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
        )->withPivot('in_time', 'type')->using('AttendCheck\Course\Attendance');
    }

    public function device()
    {
        return $this->hasMany('AttendCheck\Device', 'owner_id');
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
        $attended = $this->attendances->contains(function ($attendance) use ($schedule)  {
            return $attendance->pivot->schedule_id == $schedule->id;
        });

        if (! $attended) {
            return false;
        }

        return $this->attendances()
                    ->where('schedule_id', $schedule->id)
                    ->first()
                    ->pivot->type;
    }

    public function attend($schedule)
    {
        $now = \Carbon\Carbon::now();
        $starttime = $schedule->start_date;
        $latetime = $schedule->course->latetime;

        $isLate = $now < $starttime ? false : 
        ($now->diffInMinutes($starttime->addMinute($latetime))) > $latetime;

        if ($type = $this->isAttended($schedule)) {
            $attendance = $this->attendances()->where('schedule_id', $schedule->id)->first();

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

        $in_time = $now->toDateTimeString();

        $this->attendances()->attach($schedule->id, [
            'type' => $isLate ? 2 : 1,
            'in_time' => $in_time
        ]);

        $lastInsertID = \DB::getPdo()->lastInsertId();

        return $isLate ? 'late'.$lastInsertID : 'check'.$lastInsertID;
    }

    public function attendStatus($schedule)
    {
        switch ($this->isAttended($schedule))
        {
            case false:
                return 'ยังไม่เข้าเรียน';
            
            case 1:
                return 'เข้าเรียน';
            case 2:
                return 'สาย';
            case 3:
                return 'ยังไม่เข้าเรียน'; 
        }
    }

    public function enroll($course)
    {
        $this->enrollments()->attach($course->id);
    }
}
