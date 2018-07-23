<?php

use Illuminate\Database\Seeder;
use App\Models\Engineering;
use App\Models\Supervision;
use App\Models\User;

class EngineeringsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = app(Faker\Generator::class);

        $supervision_ids = Supervision::all()->pluck('id')->toArray();

        $user_ids = User::all()->pluck('id')->toArray();

        $engineerings = factory(Engineering::class)
            ->times(50)
            ->make()
            ->each(function ($engineering, $index)
            use ($faker, $supervision_ids, $user_ids)
            {
                $engineering->supervision_id = $faker->randomElement($supervision_ids);
                $engineering->user_id = $faker->randomElement($user_ids);
                $engineering->data = json_encode([
                    'technicians' => $faker->randomElements($user_ids, 3),
                    'custodians'  => $faker->randomElements($user_ids, 3),
                    'safety_officers' => $faker->randomElements($user_ids, 3)
                ]);
            });
        Engineering::insert($engineerings->toArray());
    }
}
