<?php

namespace AttendCheck\Course;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Attendance extends Pivot
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
    protected $dates = ['in_time', 'out_time'];

    public function schedule()
    {
        return $this->belongsTo('\AttendCheck\Course\Schedule');
    }
}
