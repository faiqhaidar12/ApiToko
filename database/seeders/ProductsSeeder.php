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

        foreach (range(1, 20) as $index) {
            DB::table('produk')->insert([
                'nama_produk' => $faker->word(10),
                'deskripsi' => $faker->text(50),
                'harga' => $faker->numberBetween(1000, 100000),
                'gambar' => ''
            ]);
        }
    }
}
