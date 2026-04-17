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
final class OffresMissionController extends AbstractController
{
    #[Route('/offres', name: 'app_freelance_offres', methods: ['GET'])]
    public function index(
        OffreMissionRepository $offreMissionRepository,
        OffreMissionStatusRepository $statusRepository
    ): Response {
        $publishedStatus = $statusRepository->findOneBy(['code' => 'PUBLISHED']);
        
        return $this->render('freelance/offres/index.html.twig', [
            'user' => $this->getUser(),
            'offres' => $offreMissionRepository->findBy(
                ['status' => $publishedStatus],
                ['createdAt' => 'DESC']
            ),
        ]);
    }

    #[Route('/offres/{id}', name: 'app_freelance_offre_show', methods: ['GET'])]
    public function show(OffreMission $offreMission, CandidacyRepository $candidacyRepository): Response
    {
        // Verifier que le user n'a pas deja postulé à cette offre
        $user = $this->getUser();
        $hasApplied = $candidacyRepository->findOneBy(['mission' => $offreMission, 'freelance' => $user]) !== null;

        return $this->render('freelance/offres/show.html.twig', [
            'offre' => $offreMission,
            'user' => $user,
            'hasApplied' => $hasApplied,
        ]);
    }
}
