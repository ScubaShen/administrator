<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Engineering;

class EngineeringPolicy extends Policy
{
    public function own(User $user, Engineering $engineering)
    {
        return $engineering->user_id == $user->id;
    }
}
