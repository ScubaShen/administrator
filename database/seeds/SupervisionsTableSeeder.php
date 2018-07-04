<?php

use Illuminate\Database\Seeder;
use App\Models\Supervision;

class SupervisionsTableSeeder extends Seeder
{
    public function run()
    {
        $supervisions = factory(Supervision::class)->times(10)->make();
        Supervision::insert($supervisions->toArray());
    }

}

