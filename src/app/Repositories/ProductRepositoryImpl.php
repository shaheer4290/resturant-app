<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepositoryImpl implements ProductRepository
{
    public function get($id)
    {
        return Product::find($id);
    }
}
