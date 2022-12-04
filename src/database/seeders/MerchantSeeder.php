<?php

namespace Database\Seeders;

use App\Models\Merchant;
use Illuminate\Database\Seeder;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchant = Merchant::where('email', '=', 'test_merchat@foodics.com')->first();

        if (empty($merchant)) {
            $merchant = new Merchant();
            $merchant->name = 'Test Merchant';
            $merchant->email = 'test_merchat@foodics.com';
            $merchant->save();
        }
    }
}
