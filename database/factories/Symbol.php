<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Symbol::class, function (Faker $faker) {
    return [
        //
        "name"=>"Test",
        "company"=>"test"
    ];
});
