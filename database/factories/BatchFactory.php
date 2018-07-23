<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(App\Models\Batch::class, function (Faker $faker) {
    $now = Carbon::now()->toDateTimeString();

    // 随机取一个月以内的时间
    $finish_at = $faker->dateTimeThisMonth();

    // 传参为生成最大时间不超过，创建时间永远比更改时间要早
    $start_at = $faker->dateTimeThisMonth($finish_at);
    return [
        'name' => $faker->slug,
        'range' => $faker->numberBetween(25, 100),
        'longitude' => $faker->longitude,
        'latitude' => $faker->latitude,
        'safe_distance' => $faker->numberBetween(25, 100),
        'start_at' => $start_at,
        'finish_at' => $finish_at,
        'description' => $faker->text(50),
        'finished' => 0,
        'created_at' => $now,
        'updated_at' => $now,
    ];
});
