<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Engineering;
use App\Models\Company;

class EngineeringPolicy extends Policy
{
    public function own(User $user, Engineering $engineering)
    {
        return $engineering->user_id == $user->id;
    }

    public function ownCompany(User $user, Engineering $engineering)
    {
        return $engineering->company_id == $user->company_id;
    }
}
