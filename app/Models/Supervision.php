<?php

namespace App\Models;

class Supervision extends Model
{
    protected $fillable = ['name', 'description'];

    public function engineerings()
    {
        return $this->hasMany(Engineering::class);
    }
}
