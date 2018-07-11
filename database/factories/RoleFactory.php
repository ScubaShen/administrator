<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(App\Models\Role::class, function (Faker $faker) {
    $now = Carbon::now()->toDateTimeString();

    return [
        'name' => $faker->name,
        'created_at' => $now,
        'updated_at' => $now,
    ];
});
