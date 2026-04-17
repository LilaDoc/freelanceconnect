<?php

namespace App\Controller\Front\Freelance;

use App\Entity\OffreMission;
use App\Repository\OffreMissionRepository;
use App\Service\OffreMissionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/freelance')]
#[IsGranted('ROLE_FREELANCER')]
final class MissionController extends AbstractController
{
    public function __construct(
        private OffreMissionService $missionService,
    ) {}
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
    #[Route('/{id}/firstpayment', name: 'app_freelance_mission_firstpayment', methods: ['POST'])]
    public function firstPaymentAdd(Request $request, OffreMission $mission): Response
    {
        if ($mission->getFreelanceServiceProvider() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if (!$this->isCsrfTokenValid('firstpayment' . $mission->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token invalide.');
            return $this->redirectToRoute('app_freelance_mission_show', ['id' => $mission->getId()]);
        }

        $this->missionService->addFirstPayment($mission, (int) $request->request->get('value'));

        $this->addFlash('success', 'Premier paiement enregistré.');
        return $this->redirectToRoute('app_freelance_mission_show', ['id' => $mission->getId()]);
    }
}
