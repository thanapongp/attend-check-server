<?php

namespace AttendCheck;

use Illuminate\Database\Eloquent\Model;

class ChangeToken extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['token', 'expired'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'expired',
    ];

    public function expired()
    {
        return $this->expired->lte(\Carbon\Carbon::now());
    }
}
