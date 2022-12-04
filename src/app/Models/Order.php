<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'user_id',
        'status',
        'total',
    ];

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function OrderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'order_id');
    }
}
