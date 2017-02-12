<?php

namespace AttendCheck\Course;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function semester()
    {
        switch ($this->semester) {
            case 'ภาคต้น':
                return '1';
            case 'ภาคปลาย':
                return '2';
            case 'ภาคฤดูร้อน':
                return '1';
        }
    }
}
