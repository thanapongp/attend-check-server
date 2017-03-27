<?php

namespace AttendCheck;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uid'];

    public function user()
    {
        return $this->belongsTo('AttendCheck\User');
    }
}
