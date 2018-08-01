<?php

namespace App\Models;

class Batch extends Model
{
    protected $fillable = [
        'name', 'engineering_id', 'range', 'longitude', 'latitude', 'safe_distance', 'start_at', 'finish_at', 'description',
        'groups', 'materials', 'finished'
    ];

    protected $casts = [
        'groups' => 'json',
        'materials' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function engineering()
    {
        return $this->belongsTo(Engineering::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
