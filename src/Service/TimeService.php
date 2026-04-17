<?php
namespace App\Service;

use App\Entity\OffreMission;
use App\Entity\TimeRegistered;
use App\Entity\User;
use App\Repository\TimeRegisteredRepository;
use Doctrine\ORM\EntityManagerInterface;

class TimeService
{
    public function __construct(
        private TimeRegisteredRepository $timeRegisteredRepository,
        private EntityManagerInterface $em,
    ) {}

    public function addTime(OffreMission $mission, User $freelance, int $hours, \DateTime $date): void
    {
        $time = new TimeRegistered();
        $time->setMission($mission);
        $time->setFreelance($freelance);
        $time->setTime($hours);
        $time->setRegisteredDate($date);

        $this->em->persist($time);
        $this->em->flush();
    }

    public function deleteTime(int $timeId, User $freelance): void
    {
        $time = $this->timeRegisteredRepository->find($timeId);

        if ($time && $time->getFreelance() === $freelance) {
            $this->em->remove($time);
            $this->em->flush();
        }
    }
}
