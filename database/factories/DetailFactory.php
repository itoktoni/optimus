<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Item\Dao\Models\ProductDetail;

$factory->define(ProductDetail::class, function (Faker $faker) {

    return [
        'item_detail_price' => $faker->numberBetween(10000, 20000),
    ];
});
