<?php

namespace AttendCheck;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    /**
     * Get the name of type.
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
