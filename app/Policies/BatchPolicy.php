<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Batch;

class BatchPolicy extends Policy
{
    public function own(User $user, Batch $batch)
    {
        return $batch->user_id == $user->id;
    }

    public function ownCompany(User $user, Batch $batch)
    {
        return $batch->company_id == $user->company_id;
    }
}
