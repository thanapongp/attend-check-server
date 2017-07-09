<?php

namespace AttendCheck;

use Illuminate\Notifications\Notifiable;
use AttendCheck\Services\AttendanceCheckService;
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
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['attendances', 'device'];

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
        )->withPivot('in_time', 'out_time', 'type')->using('AttendCheck\Course\Attendance');
    }

    public function device()
    {
        return $this->hasMany('AttendCheck\Device', 'owner_id');
    }

    public function token()
    {
        return $this->hasOne('AttendCheck\ChangeToken');
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

    public function isAdmin()
    {
        return $this->type == 'admin';
    }

    public function enroll($course)
    {
        if (! $this->enrollments->contains('id', $course->id)) {
            $this->enrollments()->attach($course->id);
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

    public function attend($schedule, $type = null)
    {
        return (new AttendanceCheckService($this))->check($schedule, $type);
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
            case 4:
                return 'ลา';
        }
    }

    public function owns($course)
    {
        return $this->isAdmin() ? true : $course->teacher_id == $this->id;
    }

    public function scopeNeedReviewByAdmin($query)
    {
        return $query->where([
            ['type_id', '!=', '4'],
            ['active', '!=', true]
        ]);
    }

    public function scopeNeedReviewByFacAdmin($query)
    {
        return $query->where([
            ['type_id', '!=', '2'],
            ['type_id', '!=', '4'],
            ['active', '!=', true]
        ]);
    }
}
