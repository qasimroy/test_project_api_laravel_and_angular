<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get all categories and tags
        $categories = DB::table('categories')->pluck('id')->toArray();
        $tags = DB::table('tags')->pluck('id')->toArray();

        // Create products and associate them with random categories and tags
        for ($i = 0; $i < 10; $i++) {
            $productId = DB::table('products')->insertGetId([
                'product_name' => $faker->word . ' ' . $faker->randomElement(['Pro', 'X', 'Plus', 'Ultra']),
                'description1' => $faker->sentence(),
                'description2' => $faker->paragraph(),
                'link' => $faker->url,
                'photo' => $faker->imageUrl(640, 480, 'product', true),
                'rating' => $faker->randomFloat(2, 1, 5),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Attach random categories
            $randomCategories = $faker->randomElements($categories, rand(1, 3));
            foreach ($randomCategories as $categoryId) {
                DB::table('category_product')->insert([
                    'category_id' => $categoryId,
                    'product_id' => $productId,
                ]);
            }

            // Attach random tags
            $randomTags = $faker->randomElements($tags, rand(1, 3));
            foreach ($randomTags as $tagId) {
                DB::table('product_tag')->insert([
                    'tag_id' => $tagId,
                    'product_id' => $productId,
                ]);
            }
        }
    }
}
