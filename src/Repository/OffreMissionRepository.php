<?php

namespace App\Repository;

use App\Entity\OffreMission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OffreMission>
 */
class OffreMissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OffreMission::class);
    }

       /**
        * @return OffreMission[] Returns an array of OffreMission objects
        */
       public function findByClient($value): array
       {
           return $this->createQueryBuilder('o')
               ->andWhere('o.client = :val')
               ->setParameter('val', $value)
               ->orderBy('o.id', 'ASC')
               ->setMaxResults(10)
               ->getQuery()
               ->getResult()
           ;
       }
       public function findByStatus($status): array
       {
           return $this->createQueryBuilder('o')
               ->andWhere('o.offreStatus = :status')
               ->setParameter('status', $status)
               ->orderBy('o.createdAt', 'DESC')
               ->getQuery()
               ->getResult()
           ;
       }
}
//        public function findOneBySomeField($value): ?OffreMission
//        {
//            return $this->createQueryBuilder('o')
//                ->andWhere('o.exampleField = :val')
//                ->setParameter('val', $value)
//                ->getQuery()
//                ->getOneOrNullResult()
//            ;
//        }
// }
