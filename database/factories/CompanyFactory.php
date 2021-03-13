<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Dao\Models\Company;
use Faker\Generator as Faker;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'company_contact_name' => $faker->company,
        'company_contact_description' => $faker->text(300),
        'company_contact_address' => $faker->address,
        'company_contact_email' => $faker->email,
        'company_contact_phone' => $faker->phoneNumber,
        'company_contact_person' => $faker->name,
    ];
});
