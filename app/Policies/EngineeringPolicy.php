<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Engineering;
use Illuminate\Auth\Access\HandlesAuthorization;

class EngineeringPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function update(User $user, Engineering $engineering)
    {
        return $engineering->user_id == $user->id;
    }
}
