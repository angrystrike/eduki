<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Interfaces\OrderServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected OrderServiceInterface $orderService;

    public function __construct(OrderServiceInterface $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->createOrderWithItems(
            Auth::id(),
            $request->only('status'),
            $request->input('items')
        );

        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $order = $this->orderService->getOrderById((int)$id);

        if (!$order || $order->user_id !== Auth::id()) {
            return response()->json(['message' => 'Order not found or unauthorized'], 404);
        }

        return response()->json(['order' => $order]);
    }

    public function userOrders(): JsonResponse
    {
        $orders = $this->orderService->getOrdersByUserId(Auth::id());

        return response()->json(['orders' => $orders]);
    }

    public function index(): JsonResponse
    {
        return $this->userOrders();
    }
}
