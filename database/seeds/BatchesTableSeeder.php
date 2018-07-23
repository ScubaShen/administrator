<?php

use Illuminate\Database\Seeder;
use App\Models\Batch;
use App\Models\Engineering;
use App\Models\User;

class BatchesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = app(Faker\Generator::class);

        $engineering_ids = Engineering::all()->pluck('id')->toArray();

        $user_ids = User::all()->pluck('id')->toArray();

        $batches = factory(Batch::class)
            ->times(2000)
            ->make()
            ->each(function ($batch, $index)
            use($faker, $engineering_ids, $user_ids)
            {
                $batch->engineering_id = $faker->randomElement($engineering_ids);
                $batch->user_id = $faker->randomElement($user_ids);
                $batch->company_id = User::find($batch->user_id)->company_id;

                $user_group_ids =  User::where('company_id', $batch->company_id)->pluck('id')->toArray();

                $batch->groups = json_encode([
                    'technicians' => $faker->randomElements($user_group_ids, 3),
                    'custodians'  => $faker->randomElements($user_group_ids, 3),
                    'safety_officers' => $faker->randomElements($user_group_ids, 3),
                    'powdermen' => $faker->randomElements($user_group_ids, 3),
                    'manager' => $faker->randomElements($user_group_ids, 1),
                ]);
                $batch->materials = json_encode([
                    'detonator' => $faker->numberBetween(5, 25),
                    'dynamite' => $faker->numberBetween(5, 25),
                ]);
            });

        Batch::insert($batches->toArray());
    }

}

