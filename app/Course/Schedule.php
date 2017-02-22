<?php

namespace AttendCheck\Course;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
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
        return $this->start_date->format('d-m-Y-H-m');
    }
}
