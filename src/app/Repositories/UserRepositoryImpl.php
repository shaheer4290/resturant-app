<?php

namespace App\Repositories;

use App\Models\User;

class UserRepositoryImpl implements UserRepository
{
    public function getDefaultCustomer()
    {
        return User::where('email', '=', 'test_customer@foodics.com')->first();
    }

    public function createDefaultCustomer($data)
    {
        return User::create($data);
    }
}
