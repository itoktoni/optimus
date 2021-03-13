<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use bheller\ImagesGenerator\ImagesGeneratorProvider;
use Faker\Generator as Faker;
use Modules\Item\Dao\Models\Product;
use Modules\Item\Dao\Models\ProductDetail;

$factory->define(Product::class, function (Faker $faker) {

    $name = $faker->name;
    $slug = $faker->slug;

    $faker->addProvider(new ImagesGeneratorProvider($faker));
    $image = $faker->imageGenerator('public/files/product', 300, 400, 'jpg', false, $name, $faker->hexcolor, $faker->hexcolor);
    return[
        'item_product_slug' => $slug,
        'item_product_min_stock' => 1,
        'item_product_max_stock' => 1,
        'item_product_sku' => $faker->numberBetween(10000, 20000),
        'item_product_buy' => $faker->numberBetween(10000, 20000),
        'item_product_sell' => $faker->numberBetween(10000, 20000),
        'item_product_discount' => $faker->numberBetween(10000, 20000),
        'item_product_price' => $faker->numberBetween(100000, 700000),
        'item_product_stroke' => $faker->numberBetween(600000, 900000),
        'item_product_image' => $image,
        'item_product_item_sub_category_id' => null,
        'item_product_item_category_id' => $faker->randomElement(['1', '2', '3', '4', '5', '6']),
        'item_product_item_brand_id' => $faker->randomElement(['1', '2', '3']),
        'item_product_item_tag_json' => json_encode($faker->randomElements([
            'sepatu',
            'tshirt',
            'snickers',
            'bantal',
            'kasur',
            'komputer',
            'mainan',
            'motor',
            'meja',
            'galon',
            'sofa',
            'kabel',
            'usb',
            'handphone',
            'android',
            'iphone',
        ], 4)),
        'item_product_name' => $name,
        'item_product_description' => $faker->text(600),
        'item_product_updated_at' => $faker->date('Y-m-d H:i:s'),
        'item_product_created_at' => $faker->date('Y-m-d H:i:s'),
        'item_product_deleted_at' => null,
        'item_product_updated_by' => 'faker',
        'item_product_created_by' => 'faker',
        'item_product_counter' => $faker->randomDigit,
        'item_product_status' => 1,
        'item_product_weight' => $faker->numberBetween(100, 900),
        'item_product_display' => 1,
        'item_product_stock' => $faker->numberBetween(100, 500),
        'item_product_min_order' => $faker->numberBetween(1, 10),
        'item_product_max_order' => 0,
        'item_product_flag_name' => $faker->word,
        'item_product_flag_color' => $faker->hexcolor,
        'item_product_flag_background' => $faker->hexcolor,
        'item_product_page_content_1' => $faker->text(1000),
        'item_product_page_content_2' => $faker->text(1000),
        'item_product_page_content_3' => $faker->text(1000),
        'item_product_page_name_1' => $faker->sentence(1),
        'item_product_page_name_2' => $faker->sentence(1),
        'item_product_page_name_3' => $faker->sentence(1),
        'item_product_page_active_3' => 1,
        'item_product_page_active_2' => 1,
        'item_product_page_active_1' => 1,
        'item_product_page_seo' => $faker->sentence(10),
        'item_product_sold' => $faker->numberBetween(50, 100),
        'item_product_is_variant' => 0,
    ];
});