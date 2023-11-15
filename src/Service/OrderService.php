<?php

declare(strict_types = 1);

namespace App\Service;

use App\Entity\Manager;
use App\Entity\Order;
use App\Repository\OrderRepository;

class OrderService
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
    ) {
    }

    public function create(
        Manager $manager,
        string $name,
    ): Order {
        $order = new Order();
        $order->setManager($manager);
        $order->setName($name);

        return $this->orderRepository->add($order);
    }
}
