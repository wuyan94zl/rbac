<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Eachdemo\Rbac\Models\RbacAdmin;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(RbacAdmin::class, function (Faker $faker) {
    return [
        'name' => '无言',
        'email' => 'admin@wuyan.com',
        'password' => '$2y$10$XwUPcvSSW81o7jflyDjv2em2sP7NlzgLLsstxxXjz6.7nIGYGM5Pa', // password
    ];
});
