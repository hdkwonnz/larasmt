<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Part;
use Faker\Generator as Faker;

$factory->define(Part::class, function (Faker $faker) {
    return [
        'own_partnumber' => $faker->text(15),
        'vendor_partnumber' => $faker->text(20),
        'description' => $faker->text(30),
        'value' => $faker->text(10),        
    ];
});