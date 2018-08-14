<?php

namespace App\Models;

class Material extends Model
{
    protected $fillable = ['name'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
