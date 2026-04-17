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
       public function findByClientAndStatusCodes($client, array $codes): array
       {
           return $this->createQueryBuilder('o')
               ->join('o.status', 's')
               ->andWhere('o.client = :client')
               ->andWhere('s.code IN (:codes)')
               ->setParameter('client', $client)
               ->setParameter('codes', $codes)
               ->orderBy('o.createdAt', 'DESC')
               ->getQuery()
               ->getResult()
           ;
       }

       public function countByStatusCode(string $code): int
       {
           return $this->createQueryBuilder('o')
               ->select('COUNT(o.id)')
               ->join('o.status', 's')
               ->andWhere('s.code = :code')
               ->setParameter('code', $code)
               ->getQuery()
               ->getSingleScalarResult();
       }

       public function findByAdminWithFilters(array $filters): array
       {
           $qb = $this->createQueryBuilder('o')
               ->join('o.client', 'c')
               ->join('o.category', 'cat')
               ->leftJoin('o.freelanceServiceProvider', 'f')
               ->orderBy('o.createdAt', 'DESC');

           if (!empty($filters['client'])) {
               $search = '%' . $filters['client'] . '%';
               $qb->andWhere('c.firstName LIKE :client OR c.lastName LIKE :client')
                  ->setParameter('client', $search);
           }

           if (!empty($filters['freelance'])) {
               $search = '%' . $filters['freelance'] . '%';
               $qb->andWhere('f.firstName LIKE :freelance OR f.lastName LIKE :freelance')
                  ->setParameter('freelance', $search);
           }

           if (!empty($filters['dateFrom'])) {
               $qb->andWhere('o.createdAt >= :dateFrom')
                  ->setParameter('dateFrom', $filters['dateFrom']);
           }

           if (!empty($filters['dateTo'])) {
               $qb->andWhere('o.createdAt <= :dateTo')
                  ->setParameter('dateTo', $filters['dateTo']);
           }

           if (!empty($filters['budgetMin'])) {
               $qb->andWhere('o.budget >= :budgetMin')
                  ->setParameter('budgetMin', $filters['budgetMin']);
           }

           if (!empty($filters['budgetMax'])) {
               $qb->andWhere('o.budget <= :budgetMax')
                  ->setParameter('budgetMax', $filters['budgetMax']);
           }

           if (!empty($filters['categories']) && count($filters['categories']) > 0) {
               $qb->andWhere('cat IN (:categories)')
                  ->setParameter('categories', $filters['categories']);
           }

           return $qb->getQuery()->getResult();
       }

       public function findByFreelance($freelance): array
       {
           return $this->createQueryBuilder('o')
               ->andWhere('o.freelanceServiceProvider = :freelance')
               ->setParameter('freelance', $freelance)
               ->orderBy('o.createdAt', 'DESC')
               ->getQuery()
               ->getResult()
           ;
       }

       public function findByFreelanceStatus($freelance, array $codes): array
       {
           return $this->createQueryBuilder('o')
               ->join('o.status', 's')
               ->andWhere('o.freelanceServiceProvider = :freelance')
               ->andWhere('s.code IN (:codes)')
               ->setParameter('freelance', $freelance)
               ->setParameter('codes', $codes)
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
