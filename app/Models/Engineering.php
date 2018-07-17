<?php

namespace App\Models;

class Engineering extends Model
{
    protected $fillable = [
        'name', 'description', 'supervision_id', 'data', 'start_at', 'finish_at',
    ];

    public function supervision()
    {
        return $this->belongsTo(Supervision::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
