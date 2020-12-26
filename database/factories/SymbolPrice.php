<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\SymbolPrice::class, function (Faker $faker) {
    return [
        //
        "date"=>$faker->dateTimeBetween('-2 month'),
        "symbol"=>"Test",
        "price"=>$faker->numberBetween(1,100),
        "volume"=>$faker->numberBetween(1,100)

    ];
});
