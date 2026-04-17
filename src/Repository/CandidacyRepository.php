<?php

namespace App\Repository;

use App\Entity\Candidacy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Candidacy>
 */
class CandidacyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidacy::class);
    }

    public function findByClientWithFilters($client, array $filters): array
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.mission', 'm')
            ->join('c.freelance', 'f')
            ->join('c.status', 's')
            ->andWhere('m.client = :client')
            ->setParameter('client', $client)
            ->orderBy('c.createdAt', 'DESC');

        if (!empty($filters['offre'])) {
            $qb->andWhere('m = :offre')
               ->setParameter('offre', $filters['offre']);
        }

        if (!empty($filters['status'])) {
            $qb->andWhere('s.code = :status')
               ->setParameter('status', $filters['status']);
        }

        if (!empty($filters['dateFrom'])) {
            $qb->andWhere('c.createdAt >= :dateFrom')
               ->setParameter('dateFrom', $filters['dateFrom']);
        }

        if (!empty($filters['freelance'])) {
            $search = '%' . strtolower($filters['freelance']) . '%';
            $qb->andWhere('LOWER(f.firstName) LIKE :name OR LOWER(f.lastName) LIKE :name')
               ->setParameter('name', $search);
        }

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Candidacy[] Returns an array of Candidacy objects
    //     */
       public function findByUser($value): array
       {
           return $this->createQueryBuilder('c')
               ->andWhere('c.user = :val')
               ->setParameter('val', $value)
               ->orderBy('c.id', 'ASC')
               ->setMaxResults(10)
               ->getQuery()
               ->getResult()
           ;
       }
              public function findByOffreMission($value): array
       {
           return $this->createQueryBuilder('c')
               ->andWhere('c.offreMission = :val')
               ->setParameter('val', $value)
               ->orderBy('c.id', 'ASC')
               ->setMaxResults(10)
               ->getQuery()
               ->getResult()
           ;
       }


    //    public function findOneBySomeField($value): ?Candidacy
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
