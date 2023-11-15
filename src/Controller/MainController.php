<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Tasks\TaskOne;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class MainController extends AbstractController
{
    public function __construct(private readonly OrderRepository $orderRepository)
    {
    }

    #[Route(path: '/orders', name: 'get_orders')]
    public function getOrders(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $offset = ($page - 1) * OrderRepository::ORDER_PER_PAGE;

        $paginator = $this->orderRepository->getOrdersByPage(
            offset: $offset,
        );

        $countItems = count($paginator);

        $totalPages = ceil($countItems / OrderRepository::ORDER_PER_PAGE);

        return $this->render(
            'base.html.twig',
            [
                'orders' => $paginator,
                'previous' => $page - 1,
                'next' => $page + 1,
                'totalPages' => $totalPages,
            ]
        );
    }

    #[Route(path: '/taskOne')]
    public function taskOne(): Response
    {
        $a = new TaskOne(1);
        $b = new TaskOne(2);
        $c = new TaskOne(3);
        $a->next = $b;
        $b->next = $c;
        $c->next = null;

        dd($this->reverseTaskOne($a));

        return new Response();
    }

    #[Route(path: '/taskTwo')]
    public function taskTwo(): Response
    {
        $boxes1 = [1, 2, 1, 5, 1, 3, 5, 2, 5, 5];
        $n1 = 6;
        $resultOne = $this->maxDeliveriesTaskTwo($boxes1, $n1); // Output: 3

        $boxes2 = [2, 4, 3, 6, 1];
        $n2 = 5;
        $resultTwo =  $this->maxDeliveriesTaskTwo($boxes2, $n2); // Output: 2

        return new Response('Результат 1: ' . $resultOne . '<br> Результат 2: ' . $resultTwo);
    }

    private function maxDeliveriesTaskTwo(array $boxes, int $n): int {
        sort($boxes);
        $left = 0;
        $right = count($boxes) - 1;
        $deliveries = 0;

        while ($left <= $right) {
            if ($boxes[$left] + $boxes[$right] <= $n) {
                $left++;
            }
            $right--;
            $deliveries++;
        }

        return $deliveries;
    }

    private function reverseTaskOne(TaskOne $taskOne): TaskOne
    {
        $prev = null;
        $current = $taskOne;
        while ($current != null) {
            $next = $current->next;
            $current->next = $prev;
            $prev = $current;
            $current = $next;
        }
        return $prev;
    }
}
