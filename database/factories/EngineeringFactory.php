<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(App\Models\Engineering::class, function (Faker $faker) {
    $now = Carbon::now()->toDateTimeString();

    // 随机取一个月以内的时间
    $finish_at = $faker->dateTimeThisMonth();

    // 传参为生成最大时间不超过，创建时间永远比更改时间要早
    $start_at = $faker->dateTimeThisMonth($finish_at);
    return [
        'name' => $faker->slug,
        'description' => $faker->text,
        'start_at' => $start_at,
        'finish_at' => $finish_at,
        'created_at' => $now,
        'updated_at' => $now,
    ];
});
