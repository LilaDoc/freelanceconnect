<?php
namespace App\Service;

use App\Entity\Candidacy;
use App\Entity\OffreMission;
use App\Entity\User;
use App\Repository\CandidacyRepository;
use App\Repository\CandidacyStatusRepository;
use App\Repository\OffreMissionStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CandidacyService
{
    public function __construct(
        private CandidacyRepository $candidacyRepository,
        private CandidacyStatusRepository $candidacyStatusRepository,
        private OffreMissionStatusRepository $offreMissionStatusRepository,
        private EntityManagerInterface $em,
    ) {
    }

    public function freelanceHasApplied(User $freelance, OffreMission $offreMission): bool
    {
        return $this->candidacyRepository->findOneBy([
            'freelance' => $freelance,
            'mission' => $offreMission,
        ]) !== null;
    }

    public function handleCv(Candidacy $candidacy, ?UploadedFile $cvFile): void
    {
        if ($cvFile === null) {
            return;
        }

        $candidacy->setCV(file_get_contents($cvFile->getPathname()));
    }

    public function checkFreelanceOwnCandidacy(User $freelance, Candidacy $candidacy): void
    {
        if ($candidacy->getFreelance() !== $freelance) {
            throw new AccessDeniedException('Vous n\'avez pas accès à cette candidature.');
        }
    }

    public function checkClientOwnCandidacy(User $client, Candidacy $candidacy): bool
    {
        return $candidacy->getMission()->getClient() === $client;
    }

    public function statusIsPending(Candidacy $candidacy): bool
    {
        return $candidacy->getStatus()?->getCode() === 'PENDING';
    }

    public function submitCandidacy(User $freelance, OffreMission $mission, Candidacy $candidacy): void
    {
        $candidacy->setFreelance($freelance);
        $candidacy->setMission($mission);
        $candidacy->setCreatedAt(new \DateTimeImmutable());
        $candidacy->setStatus(
            $this->candidacyStatusRepository->findOneBy(['code' => 'PENDING'])
            ?? $this->candidacyStatusRepository->findOneBy([])
        );

        $this->em->persist($candidacy);
        $this->em->flush();
    }

    public function acceptCandidacy(Candidacy $candidacy): void
    {
        $candidacy->setStatus(
            $this->candidacyStatusRepository->findOneBy(['code' => 'ACCEPTED'])
        );

        $mission = $candidacy->getMission();
        $mission->setFreelanceAssigned(true);
        $mission->setFreelanceServiceProvider($candidacy->getFreelance());
        $mission->setStatus(
            $this->offreMissionStatusRepository->findOneBy(['code' => 'IN_PROGRESS'])
        );

        $this->em->flush();
    }

    public function refuseCandidacy(Candidacy $candidacy): void
    {
        $candidacy->setStatus(
            $this->candidacyStatusRepository->findOneBy(['code' => 'REFUSED'])
        );

        $this->em->flush();
    }

    public function deleteCandidacy(Candidacy $candidacy): void
    {
        $this->em->remove($candidacy);
        $this->em->flush();
    }
}
