<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\NewSymbol::class, function (Faker $faker) {
    return [
        "name"=>"Test",
        "exchange"=>"test"
    ];
});
