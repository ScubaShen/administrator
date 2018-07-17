<?php

namespace App\Models;

class Batch extends Model
{
    protected $fillable = [
        'name', 'range', 'engineering_id', 'range', 'longitude', 'latitude', 'safe_distance', 'start_at', 'finish_at', 'remark', 'finished',
    ];
}
