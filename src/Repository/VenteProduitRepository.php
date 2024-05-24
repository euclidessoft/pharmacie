<?php

namespace App\Repository;

use App\Entity\VenteProduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VenteProduit>
 *
 * @method VenteProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method VenteProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method VenteProduit[]    findAll()
 * @method VenteProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VenteProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VenteProduit::class);
    }

    //    /**
    //     * @return VenteProduit[] Returns an array of VenteProduit objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?VenteProduit
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
