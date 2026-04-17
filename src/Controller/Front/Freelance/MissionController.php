<?php

namespace App\Controller\Front\Freelance;

use App\Entity\OffreMission;
use App\Repository\OffreMissionRepository;
use App\Repository\OffreMissionStatusRepository;
use App\Repository\CandidacyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/freelance')]
#[IsGranted('ROLE_FREELANCER')]
final class MissionController extends AbstractController
{
    #[Route('/missions', name: 'app_freelance_missions', methods: ['GET'])]
    public function index(OffreMissionRepository $OffreMissionRepository): Response
    {
        return $this->render('freelance/missions/index.html.twig', [
            'missions' => $OffreMissionRepository->findByFreelance($this->getUser()),
        ]);
    }
    #[Route('/missions/{id}', name: 'app_freelance_mission_show', methods: ['GET'])]
    public function show(OffreMission $offreMission): Response
    {
        if ($offreMission->getFreelanceServiceProvider() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette mission.');
        }

        return $this->render('freelance/missions/show.index.twig', [
            'mission' => $offreMission,
        ]);
    }
    #[Route('/closed', name: 'app_freelance_closed', methods: ['GET'])]
    public function closed(OffreMissionRepository $OffreMissionRepository): Response
    {
        return $this->render('freelance/missions/closed.html.twig', [
            'missions' => $OffreMissionRepository->findByFreelanceStatus($this->getUser(),['COMPLETED']),
        ]);
    }
}
 