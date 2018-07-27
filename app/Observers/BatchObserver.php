<?php

namespace App\Observers;

use App\Models\Batch;
use Illuminate\Http\Request;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class BatchObserver
{
    public function creating(Batch $batch)
    {
        //
    }

    public function saving(Batch $batch)
    {
        $attributes = $batch->getAttributes();

        $technicians = $attributes['technicians'];
        $custodians = $attributes['custodians'];
        $safety_officers = $attributes['safety_officers'];
        $powdermen = $attributes['powdermen'];
        $manager = is_array($attributes['manager']) ? $attributes['manager'] : [$attributes['manager']];

        $detonator = $attributes['detonator'];
        $dynamite = $attributes['dynamite'];

        $batch->groups = json_encode(compact('technicians', 'custodians', 'safety_officers', 'powdermen', 'manager'));
        $batch->materials = json_encode(compact('detonator', 'dynamite'));

        unset($batch->technicians, $batch->custodians, $batch->safety_officers, $batch->powdermen, $batch->manager, $batch->detonator, $batch->dynamite);

    }
}