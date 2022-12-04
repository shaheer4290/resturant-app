<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // default customer
        $defaultUser = User::where('email', '=', 'test_customer@foodics.com')->first();

        if (empty($defaultUser)) {
            $defaultUser = new User();
            $defaultUser->name = 'Test Customer';
            $defaultUser->email = 'test_customer@foodics.com';
            $defaultUser->password = Hash::make('password');
            $defaultUser->save();
        }
    }
}
