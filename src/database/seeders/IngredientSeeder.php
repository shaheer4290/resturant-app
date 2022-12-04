<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Merchant;
use File;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ingredient::truncate();

        $json = File::get('./database/data/ingredients.json');
        $ingredients = json_decode($json, true);
        $merchant = Merchant::where('email', '=', 'test_merchat@foodics.com')->first();

        if (! empty($ingredients['ingredients'])) {
            foreach ($ingredients['ingredients'] as $ingredient) {
                $newIngredient = Ingredient::where('name', '=', $ingredient['name'])->first();

                if (empty($newIngredient)) {
                    $newIngredient = new Ingredient();
                }

                $newIngredient->name = $ingredient['name'];
                $newIngredient->current_stock = $ingredient['current_stock'];
                $newIngredient->initial_stock = $ingredient['initial_stock'];
                $newIngredient->unit = $ingredient['unit'];
                $newIngredient->merchant_id = $merchant->id;

                $newIngredient->save();
            }
        }
    }
}
