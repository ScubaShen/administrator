<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Material;

class MaterialPolicy extends Policy
{
    public function own(User $user, Material $material)
    {
        return $material->user_id == $user->id;
    }

    public function ownCompany(User $user, Material $material)
    {
        return $material->company_id == $user->company_id;
    }
}
