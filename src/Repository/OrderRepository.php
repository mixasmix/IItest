<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public const ORDER_PER_PAGE = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }
    public function add(Order $order, bool $flush = false): Order
    {
        $this->getEntityManager()->persist($order);

        if ($flush) {
            $this->flush();
        }

        return $order;
    }

    public function getOrdersByPage(?int $limit = self::ORDER_PER_PAGE, ?int $offset = null): Paginator
    {
        $query = $this->createQueryBuilder('o')->orderBy('o.id');

        if (!empty($limit)) {
            $query->setMaxResults($limit);
        }

        if (!is_null($offset)) {
            $query->setFirstResult($offset);
        }

        return new Paginator($query);
    }
}
