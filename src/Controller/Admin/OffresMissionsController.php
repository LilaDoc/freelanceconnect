<?php

namespace App\Controller\Admin;

use App\Entity\OffreMission;
use App\Form\MissionFilterType;
use App\Repository\OffreMissionRepository;
use App\Service\OffreMissionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/missions')]
#[IsGranted('ROLE_ADMIN')]
class OffresMissionsController extends AbstractController
{
    // US 5.1 — Tableau de bord avec filtrage avancé
    #[Route('', name: 'app_admin_offres', methods: ['GET'])]
    public function index(
        Request $request,
        OffreMissionRepository $offreMissionRepository
    ): Response {
        $form = $this->createForm(MissionFilterType::class);
        $form->handleRequest($request);

        $filters = $form->isSubmitted() ? $form->getData() : [];

        return $this->render('admin/missions/index.html.twig', [
            'missions' => $offreMissionRepository->findByAdminWithFilters($filters ?? []),
            'filterForm' => $form,
        ]);
    }

    #[Route('/{id}/validate', name: 'app_admin_offre_validate', methods: ['POST'])]
    public function validate(
        Request $request,
        OffreMission $offreMission,
        OffreMissionService $offreMissionService
    ): Response {
        if (!$this->isCsrfTokenValid('validate' . $offreMission->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_admin_offres');
        }

        $offreMissionService->validateOffre($offreMission);
        $this->addFlash('success', 'Offre "' . $offreMission->getTitle() . '" publiée.');
        return $this->redirectToRoute('app_admin_offres');
    }

    #[Route('/{id}/reject', name: 'app_admin_offre_reject', methods: ['POST'])]
    public function reject(
        Request $request,
        OffreMission $offreMission,
        OffreMissionService $offreMissionService
    ): Response {
        if (!$this->isCsrfTokenValid('reject' . $offreMission->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_admin_offres');
        }

        $offreMissionService->rejectOffre($offreMission);
        $this->addFlash('warning', 'Offre "' . $offreMission->getTitle() . '" refusée.');
        return $this->redirectToRoute('app_admin_offres');
    }

    // US 5.2 — Suppression (modération)
    #[Route('/{id}/delete', name: 'app_admin_offre_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        OffreMission $offreMission,
        EntityManagerInterface $em
    ): Response {
        if (!$this->isCsrfTokenValid('delete' . $offreMission->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_admin_offres');
        }

        $em->remove($offreMission);
        $em->flush();

        $this->addFlash('success', 'Mission "' . $offreMission->getTitle() . '" supprimée.');
        return $this->redirectToRoute('app_admin_offres');
    }
}
