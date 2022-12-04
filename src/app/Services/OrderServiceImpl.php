<?php

namespace App\Services;

use App\Jobs\SendLowStockEmailJob;
use App\Models\Ingredient;
use App\Models\Order;
use App\Repositories\IngredientRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Response;

class OrderServiceImpl implements OrderService
{
    private OrderRepository $orderRepository;

    private ProductRepository $productRepository;

    private IngredientRepository $ingredientRepository;

    private UserRepository $userRepository;

    public function __construct(OrderRepository $orderRepository, ProductRepository $productRepository, IngredientRepository $ingredientRepository, UserRepository $userRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->ingredientRepository = $ingredientRepository;
        $this->userRepository = $userRepository;
    }

    public function addOrder($request)
    {
        $request = $request->all();
        $ingredientsToCheck = collect([]);
        // as the requirement did not include handling customer, so getting a test/default customer and adding order for it
        $defaultCustomer = $this->getDefaultCustomer();
        $orderData = [
            'user_id' => $defaultCustomer->id,
        ];

        $order = $this->orderRepository->create($orderData);
        $finalTotal = 0;

        foreach ($request['products'] as $orderProduct) {
            $product = $this->productRepository->get($orderProduct['product_id']);

            if (empty($product)) {
                abort(Response::HTTP_BAD_REQUEST, 'Product with id : '.$orderProduct['product_id'].' does not exist in our records');
            }

            $orderProductData = [];
            $orderProductData['order_id'] = $order->id;
            $orderProductData['product_id'] = $orderProduct['product_id'];
            $orderProductData['quantity'] = $orderProduct['quantity'];
            $orderProductData['total'] = $product->price * $orderProduct['quantity'];

            $this->orderRepository->addOrderProduct($orderProductData);
            $isStockLow = $this->updateIngredientsStock($product, $orderProduct['quantity'], $ingredientsToCheck);

            if ($isStockLow) {
                $orderData = [
                    'status' => Order::STATUS_FAILED,
                    'total' => $finalTotal,
                ];

                $this->orderRepository->update($order->id, $orderData);
                abort(Response::HTTP_BAD_REQUEST, 'Stock is low for '.$product->name."'s ingredients. Order failed");
            }

            $finalTotal += $orderProductData['total'];
        }

        $orderData = [
            'status' => Order::STATUS_COMPLETED,
            'total' => $finalTotal,
        ];

        $this->orderRepository->update($order->id, $orderData);

        if (! empty($ingredientsToCheck)) {
            dispatch(new SendLowStockEmailJob($ingredientsToCheck, $this->ingredientRepository));
        }

        return $order->fresh();
    }

    private function updateIngredientsStock($product, $orderedQuantity, &$ingredientsToCheck)
    {
        $notEnoughStock = false;

        foreach ($product->ingredients as $productIngredient) {
            $ingredient = $this->ingredientRepository->get($productIngredient->ingredient_id);

            $ingredientQuantity = $productIngredient->quantity * $orderedQuantity;
            $ingredientQuantityInKgs = (float) ($ingredientQuantity / 1000.0);

            if ($ingredientQuantityInKgs > $ingredient->current_stock || $ingredient->current_stock == 0) {
                $notEnoughStock = true;
                break;
            }

            $updatedStock = $ingredient->current_stock - $ingredientQuantityInKgs;
            $ingredientData = [
                'current_stock' => $updatedStock,
            ];

            $this->ingredientRepository->update($ingredient->id, $ingredientData);

            $ingredient->fresh();
            // checking if the stock is below low and we need to send an email to merchant for this ingredient
            if ($this->checkIfNeedToSendLowStockEmail($ingredient)) {
                if ($ingredientsToCheck->contains($ingredient)) {
                    $ingredientsToCheck->replace($ingredient);
                } else {
                    $ingredientsToCheck->push($ingredient);
                }
            }
        }

        return $notEnoughStock;
    }

    private function checkIfNeedToSendLowStockEmail($ingredient)
    {
        $isStockLow = false;

        $stockThreashold = (Ingredient::LOW_STOCK_THRESHOLD / 100.0) * ($ingredient->initial_stock);

        if ($ingredient->low_stock_email_sent == 0 && ($ingredient->current_stock < $stockThreashold)) {
            $isStockLow = true;
        }

        return $isStockLow;
    }

    private function getDefaultCustomer()
    {
        $defaultCustomer = $this->userRepository->getDefaultCustomer();

        return $defaultCustomer;
    }
}
