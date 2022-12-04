<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;

    public function Ingredients()
    {
        return $this->hasMany(Ingredients::class, 'merchant_id');
    }
}
