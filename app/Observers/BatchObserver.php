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

    }
}