<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\ProcessHistory::class, function (Faker $faker) {
    return [
        //
        "date"=>$faker->dateTimeBetween('-100 day'),
    ];
});
