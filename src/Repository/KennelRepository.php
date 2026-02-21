<?php

namespace App\Repository;

use App\Entity\Kennel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Kennel>
 */
class KennelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Kennel::class);
    }

    public function findActiveOrderedByName(): array
    {
        return $this->createQueryBuilder('k')
            ->where('k.isActive = true')
            ->orderBy('k.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function createSearchQueryBuilder(array $filters): QueryBuilder
    {
        $qb = $this->createQueryBuilder('k')
            ->leftJoin('k.breeds', 'b')
            ->addSelect('b')
            ->where('k.isActive = true');

        if (!empty($filters['name'])) {
            $qb->andWhere('k.name LIKE :name')
               ->setParameter('name', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['breed'])) {
            $qb->andWhere('b.id = :breed')
               ->setParameter('breed', $filters['breed']);
        }

        if (!empty($filters['country'])) {
            $qb->andWhere('k.country = :country')
               ->setParameter('country', $filters['country']);
        }

        if (!empty($filters['region'])) {
            $qb->andWhere('k.region LIKE :region')
               ->setParameter('region', '%' . $filters['region'] . '%');
        }

        if (!empty($filters['purpose'])) {
            $qb->andWhere('k.purposes LIKE :purpose')
               ->setParameter('purpose', '%"' . $filters['purpose'] . '"%');
        }

        return $qb->orderBy('k.name', 'ASC');
    }

    public function countActive(): int
    {
        return (int) $this->createQueryBuilder('k')
            ->select('COUNT(k.id)')
            ->where('k.isActive = true')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findLatest(int $limit = 6): array
    {
        return $this->createQueryBuilder('k')
            ->leftJoin('k.breeds', 'b')
            ->addSelect('b')
            ->where('k.isActive = true')
            ->orderBy('k.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
