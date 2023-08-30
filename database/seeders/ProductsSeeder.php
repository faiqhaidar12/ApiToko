<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 50) as $index) {
            DB::table('products')->insert([
                'title' => $faker->word(10),
                'description' => $faker->text(120),
                'price' => $faker->numberBetween(1000, 100000),
                'image' => ''
            ]);
        }
    }
}
