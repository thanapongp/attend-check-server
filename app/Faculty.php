<?php

namespace AttendCheck;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    /**
     * Get the name of faculty.
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
