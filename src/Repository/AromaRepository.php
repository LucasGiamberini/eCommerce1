<?php

namespace App\Repository;

use App\Entity\Aroma;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Aroma>
 *
 * @method Aroma|null find($id, $lockMode = null, $lockVersion = null)
 * @method Aroma|null findOneBy(array $criteria, array $orderBy = null)
 * @method Aroma[]    findAll()
 * @method Aroma[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AromaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Aroma::class);
    }

    //    /**
    //     * @return Aroma[] Returns an array of Aroma objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Aroma
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
