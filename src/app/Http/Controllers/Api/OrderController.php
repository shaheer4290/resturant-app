<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use App\Utils\ResponseUtils;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function create(CreateOrderRequest $request)
    {
        $order = $this->orderService->addOrder($request);

        if (! empty($order)) {
            return ResponseUtils::sendResponseWithSuccess('Order created successfully', new OrderResource($order), Response::HTTP_CREATED);
        } else {
            return ResponseUtils::sendResponseWithError('Something went wrong, Unable to create order', Response::HTTP_UNAUTHORIZED);
        }
    }
}
