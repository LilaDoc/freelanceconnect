<?php
namespace App\Service;

use App\Entity\OffreMission;
use App\Entity\User;
use App\Repository\OffreMissionStatusRepository;
use Doctrine\ORM\EntityManagerInterface;

class OffreMissionService
{
    public function __construct(
        private OffreMissionStatusRepository $statusRepository,
        private EntityManagerInterface $em,
    ) {
    }

    public function initOffre(OffreMission $offre, User $client, bool $requiresAdminValidation): void
    {
        $offre->setClient($client);
        $offre->setCreatedAt(new \DateTimeImmutable());
        $offre->setUpdatedAt(new \DateTimeImmutable());
        $offre->setFreelanceAssigned(false);
        $offre->setHasFirstPayment(false);
        $offre->setStatus(
            $this->resolveStatus($requiresAdminValidation)
        );

        $this->em->persist($offre);
        $this->em->flush();
    }

    public function updateOffre(OffreMission $offre, bool $requiresAdminValidation): void
    {
        $offre->setUpdatedAt(new \DateTimeImmutable());
        $offre->setStatus(
            $this->resolveStatus($requiresAdminValidation)
        );

        $this->em->flush();
    }

    public function closeMission(OffreMission $offre): void
    {
        $status = $this->statusRepository->findOneBy(['code' => 'COMPLETED']);
        if ($status) {
            $offre->setStatus($status);
            $this->em->flush();
        }
    }

    public function validateOffre(OffreMission $offre): void
    {
        $offre->setStatus($this->statusRepository->findOneBy(['code' => 'PUBLISHED']));
        $this->em->flush();
    }

    public function rejectOffre(OffreMission $offre): void
    {
        $offre->setStatus($this->statusRepository->findOneBy(['code' => 'HIDDEN']));
        $this->em->flush();
    }

    private function resolveStatus(bool $requiresAdminValidation): mixed
    {
        $code = $requiresAdminValidation ? 'PENDING' : 'PUBLISHED';

        return $this->statusRepository->findOneBy(['code' => $code])
            ?? $this->statusRepository->findOneBy([]);
    }
}
