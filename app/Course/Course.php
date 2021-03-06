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

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['schedules'];

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

    public function semesterValue()
    {
        switch ($this->semester) {
            case '1':
                return 'ภาคต้น';
            case '2':
                return 'ภาคปลาย';
            case '3':
                return 'ภาคฤดูร้อน';
        }
    }

    public function url()
    {
        $code = $this->code;
        $semester = $this->semester();
        $year = substr($this->year, -2);
        $section = $this->section;

        return "$code-$semester-$year-$section";
    }

    public function periods()
    {
        return $this->hasMany('AttendCheck\Course\Period');
    }

    public function schedules()
    {
        return $this->hasMany('AttendCheck\Course\Schedule');
    }

    public function students()
    {
        return $this->belongsToMany(
            'AttendCheck\User', 'enrollments', 'course_id', 'student_id'
        );
    }
}
