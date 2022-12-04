<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderProduct;

class OrderRepositoryImpl implements OrderRepository
{
    public function create($data)
    {
        return $order = Order::create($data);
    }

    public function update($id, $data)
    {
        return Order::find($id)->update($data);
    }

    public function addOrderProduct($data)
    {
        $orderProduct = OrderProduct::create($data);
    }
}
