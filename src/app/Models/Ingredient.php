<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    // low stock threshold in percent
    public const LOW_STOCK_THRESHOLD = 50;

    protected $fillable = [
        'current_stock',
        'low_stock_email_sent',
        'initial_stock',
    ];

    public function Merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }
}
