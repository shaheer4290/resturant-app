<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use App\Models\Merchant;
use App\Models\Product;
use App\Models\ProductIngredient;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function testsRequiresProducts()
    {
        $payload = [];
        $response = $this->postJson('/api/orders', $payload);

        $response
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'products' => ['The products field is required.'],
                ],
            ]);
    }

    public function testsOrderCreatedSuccessfully()
    {
        $user = User::factory()->create([
            'email' => 'test.user@user.com',
            'password' => bcrypt('123456'),
        ]);

        $merchant = Merchant::factory()
                        ->create([
                            'name' => 'Test Merchant',
                            'email' => 'testMerchant@gmail.com',
                        ]);

        $ingredient = Ingredient::factory()
                        ->create([
                            'name' => 'Test Product',
                            'current_stock' => 10,
                            'initial_stock' => 10,
                            'unit' => 'kg',
                            'merchant_id' => $merchant->id,
                        ]);

        $product = Product::factory()
                        ->create([
                            'name' => 'Test Product',
                            'price' => 100,
                        ]);

        $productIngredient = ProductIngredient::factory()
                        ->create([
                            'product_id' => $product->id,
                            'ingredient_id' => $ingredient->id,
                            'quantity' => 5,
                            'unit' => 'g',
                        ]);

        $payload = [
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                ],
            ],
        ];

        $response = $this->postJson('/api/orders', $payload);

        $response
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Order created successfully',
            ]);
    }

    public function testsOrderFailedDueToLowStock()
    {
        $merchant = Merchant::factory()
                        ->create([
                            'name' => 'Test Merchant',
                            'email' => 'testMerchant@gmail.com',
                        ]);

        $ingredient = Ingredient::factory()
                        ->create([
                            'name' => 'Test Product',
                            'current_stock' => 0.004,
                            'initial_stock' => 10,
                            'unit' => 'kg',
                            'merchant_id' => $merchant->id,
                        ]);

        $product = Product::factory()
                        ->create([
                            'name' => 'Test Product',
                            'price' => 100,
                        ]);

        $productIngredient = ProductIngredient::factory()
                        ->create([
                            'product_id' => $product->id,
                            'ingredient_id' => $ingredient->id,
                            'quantity' => 5,
                            'unit' => 'g',
                        ]);

        $payload = [
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                ],
            ],
        ];

        $response = $this->postJson('/api/orders', $payload);

        $response
            ->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Stock is low for '.$product->name."'s ingredients. Order failed",
            ]);
    }

    public function testsOrderFailedDueToNonExistantProduct()
    {
        $payload = [
            'products' => [
                [
                    'product_id' => 9999,
                    'quantity' => 1,
                ],
            ],
        ];

        $response = $this->postJson('/api/orders', $payload);

        $response
            ->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Product with id : 9999 does not exist in our records',
            ]);
    }
}
