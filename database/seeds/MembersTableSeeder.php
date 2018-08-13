<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Company;
use App\Models\Member;

class MembersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = app(Faker\Generator::class);

        $user_ids = User::all()->pluck('id')->toArray();

        $role_ids = Role::all()->pluck('id')->toArray();

        $members = factory(Member::class)
            ->times(500)
            ->make()
            ->each(function ($member, $index)
            use ($faker, $user_ids, $role_ids)
            {
                $member->role_id = $faker->randomElement($role_ids);

                $member->user_id = $faker->randomElement($user_ids);

                $member->company_id = User::find($member->user_id)->company_id;
            });
        Member::insert($members->toArray());
    }
}
