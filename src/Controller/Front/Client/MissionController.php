<?php

namespace App\Controller\Front\Client;

use App\Entity\OffreMission;
use App\Repository\OffreMissionRepository;
use App\Service\OffreMissionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/client')]
#[IsGranted('ROLE_CLIENT')]
final class MissionController extends AbstractController
{
    #[Route('/missions', name: 'app_client_missions', methods: ['GET'])]
    public function index(OffreMissionRepository $offreMissionRepository): Response
    {
        return $this->render('client/missions/index.html.twig', [
            'missions' => $offreMissionRepository->findByClientAndStatusCodes(
                $this->getUser(),
                ['IN_PROGRESS']
            ),
        ]);
    }

    #[Route('/missions/{id}', name: 'app_clientmission_show', methods: ['GET'])]
    public function show(OffreMission $offreMission): Response
    {
        if ($offreMission->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette mission.');
        }

        return $this->render('client/missions/show.index.twig', [
            'mission' => $offreMission,
        ]);
    }

    #[Route('/missions/{id}/close', name: 'app_clientmission_close', methods: ['POST'])]
    public function close(
        Request $request,
        OffreMission $offreMission,
        OffreMissionService $offreMissionService
    ): Response {
        if ($offreMission->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette mission.');
        }

        if (!$this->isCsrfTokenValid('close' . $offreMission->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_clientmission_show', ['id' => $offreMission->getId()]);
        }

        $offreMissionService->closeMission($offreMission);
        $this->addFlash('success', 'Mission clôturée avec succès.');

        return $this->redirectToRoute('app_clientmission_show', ['id' => $offreMission->getId()]);
    }
     #[Route('/missions/{id}/firstpayment/acte', name: 'app_client_mission_firstpayment_acte', methods: ['POST'])]
     public function acte(
        Request $request, OffreMission $offreMission, OffreMissionService $offreMissionService): Response{
        if ($offreMission->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette mission.');
        }
        if (!$this->isCsrfTokenValid('close' . $offreMission->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_clientmission_show', ['id' => $offreMission->getId()]);
        }
        $offreMissionService->acteFirstPayment($offreMission);
        $this->addFlash('success', 'Payement acté avec succès.');
        return $this->redirectToRoute('app_clientmission_show', ['id' => $offreMission->getId()]);
    }
}
