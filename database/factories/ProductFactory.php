<?php

use App\Models\Product;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {

    return [
        'name'        => $faker->word,
        'description' => $faker->sentence,
        'creator_id'  => function () {
            return User::factory()->create()->id;
        },
    ];
});
