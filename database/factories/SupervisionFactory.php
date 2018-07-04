<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(App\Models\Supervision::class, function (Faker $faker) {
    $now = Carbon::now()->toDateTimeString();

    return [
        'name' => $faker->company,
        'description' => $faker->sentence(),
        'created_at' => $now,
        'updated_at' => $now,
    ];
});
