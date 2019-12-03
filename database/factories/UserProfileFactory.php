<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\{User,UserProfile};
use Faker\Generator as Faker;

$factory->define(UserProfile::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'bio' => $faker->paragraph
    ];
});
