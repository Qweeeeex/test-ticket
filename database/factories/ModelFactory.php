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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->defineAs(App\User::class, 'admin', function (Faker\Generator $faker) {
    return [
        'name' => 'admin',
        'email' => 'admin@admin.com',
        'password' => bcrypt('admin'),
        'remember_token' => str_random(10),
        'is_admin' => 1,
    ];
});

$factory->defineAs(App\User::class, 'user', function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('user'),
        'remember_token' => str_random(10),
        'is_admin' => 0,
    ];
});
$factory->defineAs(App\User::class, 'user_pass', function (Faker\Generator $faker) {
    return [
        'name' => 'user',
        'email' => 'user@user.com',
        'password' => bcrypt('user'),
        'remember_token' => str_random(10),
        'is_admin' => 0,
    ];
});
$factory->defineAs(App\Category::class, 'categories1', function (Faker\Generator $faker) {
    return [
        'name' => 'First',
    ];
});
$factory->defineAs(App\Category::class, 'categories2', function (Faker\Generator $faker) {
    return [
        'name' => 'Third',
    ];
});
$factory->defineAs(App\Category::class, 'categories3', function (Faker\Generator $faker) {
    return [
        'name' => 'Third',
    ];
});
