<?php

namespace App\Repository;

use App\Entity\Litter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Litter>
 */
class LitterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Litter::class);
    }

    public function findWithPuppiesAvailable(): array
    {
        return $this->createQueryBuilder('l')
            ->leftJoin('l.kennel', 'k')
            ->addSelect('k')
            ->where('l.hasPuppiesAvailable = true')
            ->andWhere('k.isActive = true')
            ->orderBy('l.bornAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function countTotal(): int
    {
        return (int) $this->createQueryBuilder('l')
            ->select('COUNT(l.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
