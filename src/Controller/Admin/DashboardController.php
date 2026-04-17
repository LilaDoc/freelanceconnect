<?php

namespace App\Controller\Admin;

use App\Repository\CandidacyRepository;
use App\Repository\OffreMissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_admin_dashboard')]
    public function index(
        OffreMissionRepository $offreMissionRepository,
        CandidacyRepository $candidacyRepository,
    ): Response {
        return $this->render('admin/dashboard.html.twig', [
            'totalMissions'     => count($offreMissionRepository->findAll()),
            'pendingMissions'   => $offreMissionRepository->countByStatusCode('PENDING'),
            'totalCandidatures' => count($candidacyRepository->findAll()),
        ]);
    }
}
