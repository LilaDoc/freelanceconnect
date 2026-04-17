<?php

namespace App\Repository;

use App\Entity\TimeRegistered;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TimeRegistered>
 */
class TimeRegisteredRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimeRegistered::class);
    }

    //    /**
    //     * @return TimeRegistered[] Returns an array of TimeRegistered objects
    //     */
    public function findByMission($mission): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.mission = :mission')
            ->setParameter('mission', $mission)
            ->orderBy('t.registeredDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?TimeRegistered
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
