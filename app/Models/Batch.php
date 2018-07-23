<?php

namespace App\Models;

class Batch extends Model
{
    protected $fillable = [
        'name', 'engineering_id', 'range', 'longitude', 'latitude', 'safe_distance', 'start_at', 'finish_at', 'description',
        'groups', 'meterials', 'finished',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function engineering()
    {
        return $this->belongsTo(Engineering::class);
    }
}
