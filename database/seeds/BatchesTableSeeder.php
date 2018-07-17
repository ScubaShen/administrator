<?php

use Illuminate\Database\Seeder;
use App\Models\Batch;
use App\Models\Engineering;

class BatchesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = app(Faker\Generator::class);

        $engineering_ids = Engineering::all()->pluck('id')->toArray();

        $batches = factory(Batch::class)
            ->times(50)
            ->make()
            ->each(function ($batch, $index)
            use($faker, $engineering_ids)
            {
                $batch->engineering_id = $faker->randomElement($engineering_ids);
            });

        Batch::insert($batches->toArray());
    }

}

