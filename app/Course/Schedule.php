<?php

namespace AttendCheck\Course;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'start_date',
        'end_date',
    ];

    public function url()
    {
        return $this->start_date->format('d-m-Y-H-i');
    }

    public function inProgress()
    {
        return checkCurrentTimeInrange(
            $this->start_date->toDateTimeString(), 
            $this->end_date->toDateTimeString(),
            date('Y-m-d H:i:s')
        );
    }

    public function course()
    {
        return $this->belongsTo('AttendCheck\Course\Course');
    }

    public function attendances()
    {
        return $this->belongsToMany(
            'AttendCheck\User', 'attendances', 'schedule_id', 'student_id'
        )->withPivot('type', 'in_time')->using('AttendCheck\Course\Attendance');
    }

    public function checkIns()
    {
        return $this->hasMany('AttendCheck\Course\CheckIn');
    }

    /**
     * Scope a query to order schedule to make 
     * passed date comes after upcoming date.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderByRaw("
            (CASE WHEN end_date > NOW()
                THEN 1
                ELSE 0
                END) DESC, end_date ASC
            ");
    }

    public function scopeAlreadyStarted($query)
    {
        return $query->whereRaw('end_date < NOW()');
    }
}
