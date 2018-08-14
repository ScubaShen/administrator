<?php

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\User;

class MaterialsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = app(Faker\Generator::class);

        $user_ids = User::all()->pluck('id')->toArray();

        $materials = factory(Material::class)
            ->times(500)
            ->make()
            ->each(function ($member, $index)
            use ($faker, $user_ids)
            {
                $member->user_id = $faker->randomElement($user_ids);

                $member->company_id = User::find($member->user_id)->company_id;
            });

        Material::insert($materials->toArray());
    }

}

