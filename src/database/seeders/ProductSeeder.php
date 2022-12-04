<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use App\Models\ProductIngredient;
use File;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get('./database/data/products.json');
        $products = json_decode($json, true);

        $json = File::get('database/data/products.json');
        $products = json_decode($json, true);

        if (! empty($products['products'])) {
            foreach ($products['products'] as $product) {
                $newProduct = new Product();
                $newProduct->name = $product['name'];
                $newProduct->price = $product['price'];

                $newProduct->save();
                $ingredients = $product['ingredients'];

                if (! empty($ingredients)) {
                    foreach ($ingredients as $ingredient) {
                        $orgIngredient = Ingredient::where('name', '=', $ingredient['name'])->first();

                        if (! empty($orgIngredient)) {
                            $productIngredient = new ProductIngredient();

                            $productIngredient->product_id = $newProduct->id;
                            $productIngredient->ingredient_id = $orgIngredient->id;
                            $productIngredient->quantity = $ingredient['quantity'];
                            $productIngredient->unit = $ingredient['unit'];

                            $productIngredient->save();
                        }
                    }
                }
            }
        }
    }
}
