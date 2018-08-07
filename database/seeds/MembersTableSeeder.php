<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
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

        $company_ids = Company::all()->pluck('id')->toArray();

        $role_ids = Role::all()->pluck('id')->toArray();

        $members = factory(Member::class)
            ->times(500)
            ->make()
            ->each(function ($member, $index)
            use ($faker, $company_ids, $role_ids)
            {
                $member->role_id = $faker->randomElement($role_ids);

                $member->company_id = $faker->randomElement($company_ids);
            });
        Member::insert($members->toArray());
    }
}
