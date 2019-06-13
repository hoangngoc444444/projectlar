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

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Category::class, function (Faker\Generator $faker) {
    $name = $faker->name;
    return [
        'name' => $name,
        'slug' => str_slug($name),
        'order' => rand(-100, 100),
        'parent' => 0
    ];
});

$factory->define(App\Tag::class, function (Faker\Generator $faker) {
    $name = $faker->name;
    return [
        'name' => $name,
        'slug' => str_slug($name)
    ];
});

$factory->define(App\Order::class, function (Faker\Generator $faker) {
    return [
        'user_id' => rand(1,50),
        'address' => $faker->text(80),
        'email' => $faker->email,
        'phone' => $faker->phoneNumber
    ];
});

$factory->define(App\Product::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'code' => strtoupper(str_random(6)),
        'content' => $faker->text,
        'regular_price' => rand(2001,3000),
        'sale_price' => rand(1001,2000),
        'original_price' => rand(1,1000),
        'quantity' => rand(1,100),
        'attributes' => '',
        'image' => '',
        'user_id' => rand(1,50),
        'category_id' => rand(1,50),
    ];
});
