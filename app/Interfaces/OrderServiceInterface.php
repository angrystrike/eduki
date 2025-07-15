<?php

namespace App\Interfaces;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

interface OrderServiceInterface
{
    public function createOrderWithItems(int $userId, array $orderData, array $itemsData): Order;
    public function getOrderById(int $orderId): ?Order;
    public function getOrdersByUserId(int $userId): Collection;
}
