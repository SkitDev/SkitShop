<?php

use App\Product;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        for ($i=0; $i < 30; $i++) {
            Product::create([
                'title' => $faker->sentence(3),
                'slug' => $faker->slug,
                'subtitle' => $faker->sentence(4),
                'description' => $faker->text,
                'price' => $faker->numberBetween(100, 100000),
                'image' => 'products/May2020/C5xfDeBlZvw8oMuBcayp.png',
            ])->categories()->attach([
                rand(1,5),
                rand(1,5)
            ]);
        }
    }
}
