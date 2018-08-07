<?php

namespace App\Models;

class Member extends Model
{
    protected $fillable = ['name', 'role_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
