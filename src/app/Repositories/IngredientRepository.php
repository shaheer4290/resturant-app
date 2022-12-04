<?php

namespace App\Repositories;

interface IngredientRepository
{
    public function get($id);

    public function update($id, $data);
}
