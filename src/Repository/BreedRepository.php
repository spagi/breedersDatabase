<?php

namespace App\Repository;

use App\Entity\Breed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Breed>
 */
class BreedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Breed::class);
    }

    public function findAllOrderedByName(): array
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.nameCz', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function searchByName(string $query): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.nameCz LIKE :q OR b.nameEn LIKE :q OR b.nameSk LIKE :q')
            ->setParameter('q', '%' . $query . '%')
            ->orderBy('b.nameCz', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
