<?php

namespace App\Models;

class Company extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function engineerings()
    {
        return $this->hasMany(Engineering::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}
