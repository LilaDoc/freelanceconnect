<?php

namespace App\Controller\Front\Client;

use App\Entity\OffreMission;
use App\Form\OffreMissionType;
use App\Repository\OffreMissionRepository;
use App\Service\FeatureFlagService;
use App\Service\CandidacyService;
use App\Service\OffreMissionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/client')]
#[IsGranted('ROLE_CLIENT')]
class OffresMissionsController extends AbstractController
{
    public function __construct(
        private FeatureFlagService $featureFlagService,
        private OffreMissionService $offreMissionService,
    ) {
    }

    #[Route('/offres', name: 'app_client_offres', methods: ['GET'])]
    public function index(OffreMissionRepository $offreMissionRepository): Response
    {
        return $this->render('client/offres/index.html.twig', [
            'user' => $this->getUser(),
            'offres' => $offreMissionRepository->findByClientAndStatusCodes(
                $this->getUser(),
                ['PENDING', 'PUBLISHED']
            ),
        ]);
    }

    #[Route('/offres/{id}', name: 'app_client_offre_show', methods: ['GET'])]
    public function show(OffreMission $offreMission): Response
    {
        if ($offreMission->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette offre.');
        }

        return $this->render('client/offres/show.html.twig', [
            'offre' => $offreMission,
        ]);
    }

    #[Route('/offres/new', name: 'app_client_offre_new', methods: ['GET', 'POST'], priority: 1)]
    public function new(Request $request): Response
    {
        $offreMission = new OffreMission();
        $form = $this->createForm(OffreMissionType::class, $offreMission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $requiresValidation = $this->featureFlagService->isValidationOffreAdminEnabled();
            $this->offreMissionService->initOffre($offreMission, $this->getUser(), $requiresValidation);

            $flashMessage = $requiresValidation
                ? 'Offre créée. Elle sera visible après validation par un administrateur.'
                : 'Offre de mission créée et publiée avec succès.';
            $this->addFlash('success', $flashMessage);

            return $this->redirectToRoute('app_client_offres');
        }

        return $this->render('client/offres/new.html.twig', [
            'offre' => $offreMission,
            'form' => $form,
        ]);
    }

    #[Route('/offres/{id}/edit', name: 'app_client_offre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OffreMission $offreMission): Response
    {
        if ($offreMission->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette offre.');
        }

        $form = $this->createForm(OffreMissionType::class, $offreMission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->offreMissionService->updateOffre(
                $offreMission,
                $this->featureFlagService->isValidationOffreAdminEnabled()
            );

            $this->addFlash('success', 'Offre de mission modifiée avec succès.');
            return $this->redirectToRoute('app_client_offres');
        }

        return $this->render('client/offres/edit.html.twig', [
            'offre' => $offreMission,
            'form' => $form,
        ]);
    }

    #[Route('/offres/{id}/delete', name: 'app_client_offre_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        OffreMission $offreMission,
        EntityManagerInterface $entityManager
    ): Response {
        if ($offreMission->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette offre.');
        }

        if (!$this->isCsrfTokenValid('delete' . $offreMission->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide. La suppression a été annulée.');
            return $this->redirectToRoute('app_client_offres');
        }

        $entityManager->remove($offreMission);
        $entityManager->flush();
        $this->addFlash('success', 'Offre de mission supprimée.');

        return $this->redirectToRoute('app_client_offres');
    }

    #[Route('/offres/{id}/facture', name: 'app_client_offre_facture', methods: ['GET'])]
    public function facture(OffreMission $offreMission): Response
    {
        if ($offreMission->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette offre.');
        }

        return $this->render('client/offres/facture.html.twig', [
            'offre' => $offreMission,
            'facture' => $offreMission->getInvoiceNumber(),
        ]);
    }
    #[Route('/{id}/candidatures', name: 'app_client_offre_candidatures', methods: ['GET'])]
    public function candidaturesOfMission(OffreMission $offreMission, CandidacyService $candidacyService): Response
    {
        if ($offreMission->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette offre.');
        }

        return $this->render('client/offres/candidatures.html.twig', [
            'offre'       => $offreMission,
            'candidacies' => $candidacyService->getCandidacyFromOffre($offreMission),
        ]);
    }
}
