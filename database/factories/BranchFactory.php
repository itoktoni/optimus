<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Dao\Models\Branch;
use App\Dao\Models\Company;
use Faker\Generator as Faker;

$company = Company::all()->random()->first();
$factory->define(Branch::class, function (Faker $faker) use($company) {
    return [
        'branch_name' => 'Branch ' . $faker->company,
        'branch_description' => $faker->text(200),
        'branch_company_id' => $company->company_id,
        'branch_address' => $faker->address,
        'branch_email' => $faker->companyEmail,
        'branch_phone' => $faker->phoneNumber,
        'branch_person' => $faker->name,
    ];
});
