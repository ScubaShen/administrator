<?php

namespace App\Models;

class Engineering extends Model
{
    protected $fillable = [
        'name', 'description', 'supervision_id',
    ];

    public function supervision()
    {
        return $this->belongsTo(Supervision::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
