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

                $user_group_ids = User::where('company_id', $batch->company_id)->select('id', 'role_id')->get()->toArray();
                foreach($user_group_ids as $user){
                    $users_array[$user['role_id']][] = (String)$user['id'];
                }

                $batch->groups = json_encode([
                    'technicians' => $faker->randomElements($users_array[1], 3),
                    'custodians'  => $faker->randomElements($users_array[2], 3),
                    'safety_officers' => $faker->randomElements($users_array[3], 3),
                    'powdermen' => $faker->randomElements($users_array[4], 3),
                    'manager' => $faker->randomElement($users_array[1]),
                ]);
                $batch->materials = json_encode([
                    'detonator' => $faker->numberBetween(5, 25),
                    'dynamite' => $faker->numberBetween(5, 25),
                ]);
            });

        Batch::insert($batches->toArray());
    }

}

