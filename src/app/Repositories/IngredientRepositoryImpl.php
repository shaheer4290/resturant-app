<?php

namespace App\Repositories;

use App\Models\Ingredient;

class IngredientRepositoryImpl implements IngredientRepository
{
    public function get($id)
    {
        return Ingredient::find($id);
    }

    public function update($id, $data)
    {
        Ingredient::find($id)->update($data);
    }
}
