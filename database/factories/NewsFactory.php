<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\News::class, function (Faker $faker) {
    return [
        'page_uid' => $faker->uuid,
        'title' => $faker->words(3, true),
        'snippet' => $faker->words(100, true),
        'full_text' => $faker->paragraph,
    ];
});
