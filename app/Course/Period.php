<?php

namespace AttendCheck\Course;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
