<?php

namespace App\Repositories;

interface OrderRepository
{
    public function create($data);

    public function update($id, $data);

    public function addOrderProduct($data);
}
