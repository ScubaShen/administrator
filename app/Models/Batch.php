<?php

namespace App\Models;

class Batch extends Model
{
    protected $fillable = [
        'name', 'engineering_id', 'range', 'longitude', 'latitude', 'safe_distance', 'start_at', 'finish_at', 'description',
        'groups', 'materials', 'finished'
    ];

    protected $appends = ['technicians', 'custodians', 'safety_officers', 'powdermen', 'manager', 'detonator', 'dynamite'];

    public function getTechniciansAttribute()
    {
        return $this->attributes['technicians'] = json_decode($this->groups)->technicians;
    }

    public function getCustodiansAttribute()
    {
        return $this->attributes['custodians'] = json_decode($this->groups)->custodians;
    }

    public function getSafetyOfficersAttribute()
    {
        return $this->attributes['safety_officers'] = json_decode($this->groups)->safety_officers;
    }

    public function getPowdermenAttribute()
    {
        return $this->attributes['powdermen'] = json_decode($this->groups)->powdermen;
    }

    public function getManagerAttribute()
    {
        return $this->attributes['manager'] = json_decode($this->groups)->manager[0];
    }

    public function getDetonatorAttribute()
    {
        return $this->attributes['detonator'] = json_decode($this->materials)->detonator;
    }

    public function getDynamiteAttribute()
    {
        return $this->attributes['dynamite'] = json_decode($this->materials)->dynamite;
    }

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
