<?php

namespace App\Services;

use App\Interfaces\OrderServiceInterface;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrderService implements OrderServiceInterface
{
    public function createOrderWithItems(int $userId, array $orderData, array $itemsData): Order
    {
        return DB::transaction(function () use ($userId, $orderData, $itemsData) {
            $totalAmount = 0;
            foreach ($itemsData as $item) {
                $totalAmount += $item['quantity'] * $item['price_per_unit'];
            }

            $order = Order::create([
                'user_id' => $userId,
                'total_amount' => $totalAmount,
                'status' => $orderData['status'] ?? 'pending',
            ]);

            foreach ($itemsData as $item) {
                $order->items()->create([
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'price_per_unit' => $item['price_per_unit'],
                ]);
            }

            return $order->load('items');
        });
    }

    public function getOrderById(int $orderId): ?Order
    {
        return Order::with('items', 'user')->find($orderId);
    }

    public function getOrdersByUserId(int $userId): Collection
    {
        return Order::with('items')->where('user_id', $userId)->get();
    }
}
