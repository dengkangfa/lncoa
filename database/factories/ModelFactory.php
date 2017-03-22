<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;
    $identicon = new \Identicon\Identicon();
    $date_time = $faker->date.' '.$faker->time;
    $name = $faker->name;

    return [
        'name' => $name,
        'avatar' => $identicon->getImageDataUri($name, 80),
        'email' => $faker->unique()->safeEmail,
        'status' => true,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'created_at' => $date_time,
        'updated_at' => $date_time,
    ];
});

$factory->define(App\Role::class, function(Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'display_name' => $faker->name,
        'description' => '这个是描述',
    ];
});

$factory->define(App\Permission::class, function(Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'display_name' => $faker->name,
        'description' => '这个是描述',
    ];
});
