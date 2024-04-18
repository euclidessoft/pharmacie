<?php

namespace App\Repository;

use App\Entity\Mutuelles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mutuelles>
 *
 * @method Mutuelles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mutuelles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mutuelles[]    findAll()
 * @method Mutuelles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MutuellesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mutuelles::class);
    }

    //    /**
    //     * @return Mutuelles[] Returns an array of Mutuelles objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Mutuelles
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
