<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Engineering extends Model
{
    protected $fillable = [
        'name', 'description', 'supervision_id','data', 'excerpt',  'start_at', 'finish_at', 'finished',
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