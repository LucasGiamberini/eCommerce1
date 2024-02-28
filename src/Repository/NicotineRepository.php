<?php

namespace App\Repository;

use App\Entity\Nicotine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Nicotine>
 *
 * @method Nicotine|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nicotine|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nicotine[]    findAll()
 * @method Nicotine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NicotineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nicotine::class);
    }

    //    /**
    //     * @return Nicotine[] Returns an array of Nicotine objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('n.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Nicotine
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
