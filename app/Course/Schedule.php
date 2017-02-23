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
            (CASE WHEN DATE(start_date) > DATE(NOW())
                THEN 1
                ELSE 0
                END) DESC, start_date ASC
            ");
    }
}
