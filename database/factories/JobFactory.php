<?php

$factory->define(App\Job::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence(),
    ];
});
