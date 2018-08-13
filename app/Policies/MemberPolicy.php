<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Member;

class MemberPolicy extends Policy
{
    public function own(User $user, Member $member)
    {
        return $member->user_id == $user->id;
    }
}
