<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Engineering;

class EngineeringPolicy extends Policy
{
    public function update(User $user, Engineering $engineering)
    {
        return $engineering->user_id == $user->id;
    }

    public function destroy(User $user, Engineering $engineering)
    {
        return $engineering->user_id == $user->id;
    }
}
