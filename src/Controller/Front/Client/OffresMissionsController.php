<?php

namespace App\Controller\Front\Client;

use App\Entity\OffreMission;
use App\Entity\OffreMissionStatus;
use App\Form\OffreMissionType;
use App\Repository\OffreMissionRepository;
use App\Repository\OffreMissionStatusRepository;
use App\Service\FeatureFlagService;
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
        private FeatureFlagService $featureFlagService
    ) {
    }

    // Liste des offres de missions du client
    #[Route('/offres', name: 'app_client_offres', methods: ['GET'])]
    public function index(OffreMissionRepository $offreMissionRepository): Response
    {
        $user = $this->getUser();
        $offres = $offreMissionRepository->findByClient($user);

        return $this->render('client/offres/index.html.twig', [
            'user' => $user,
            'offres' => $offres,
        ]);
    }

    // Détails d'une offre de mission
    #[Route('/offres/{id}', name: 'app_client_offre_show', methods: ['GET'])]
    public function show(OffreMission $offreMission): Response
    {
        // Vérifier que l'offre appartient au client connecté
        if ($offreMission->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette offre.');
        }

        return $this->render('client/offres/show.html.twig', [
            'offre' => $offreMission,
        ]);
    }

    // Créer une nouvelle offre de mission
    #[Route('/offres/new', name: 'app_client_offre_new', methods: ['GET', 'POST'], priority: 1)]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        OffreMissionStatusRepository $statusRepository
    ): Response {
        $offreMission = new OffreMission();
        
        $form = $this->createForm(OffreMissionType::class, $offreMission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Définir les valeurs par défaut
            $offreMission->setClient($this->getUser());
            $offreMission->setCreatedAt(new \DateTimeImmutable());
            $offreMission->setUpdatedAt(new \DateTimeImmutable());
            $offreMission->setFreelanceAssigned(false);
            $offreMission->setHasFirstPayment(false);
            
            // Définir le statut selon le feature flag
            if ($this->featureFlagService->isValidationOffreAdminEnabled()) {
                // Validation admin requise : statut "En attente de validation"
                $statusCode = 'PENDING';
                $flashMessage = 'Offre de mission créée. Elle sera visible après validation par un administrateur.';
            } else {
                // Pas de validation admin : publication directe
                $statusCode = 'PUBLISHED';
                $flashMessage = 'Offre de mission créée et publiée avec succès.';
            }
            
            $status = $statusRepository->findOneBy(['code' => $statusCode]) 
                ?? $statusRepository->findOneBy([]);
            $offreMission->setStatus($status);
            
            $entityManager->persist($offreMission);
            $entityManager->flush();
            
            $this->addFlash('success', $flashMessage);
            return $this->redirectToRoute('app_client_offres');
        }

        return $this->render('client/offres/new.html.twig', [
            'offre' => $offreMission,
            'form' => $form,
        ]);
    }

    // Éditer une offre de mission
    #[Route('/offres/{id}/edit', name: 'app_client_offre_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        OffreMission $offreMission,
        EntityManagerInterface $entityManager,
        OffreMissionStatusRepository $statusRepository
    ): Response {
        if ($offreMission->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette offre.');
        }

        $form = $this->createForm(OffreMissionType::class, $offreMission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offreMission->setUpdatedAt(new \DateTimeImmutable());
            
            // Définir le statut selon le feature flag
            if ($this->featureFlagService->isValidationOffreAdminEnabled()) {
                $statusCode = 'PENDING';
            } else {
                $statusCode = 'PUBLISHED';
            }
            $status = $statusRepository->findOneBy(['code' => $statusCode]) 
                ?? $statusRepository->findOneBy([]);
            $offreMission->setStatus($status);
            
            $entityManager->flush();
            
            $this->addFlash('success', 'Offre de mission modifiée avec succès.');
            return $this->redirectToRoute('app_client_offres');
        }

        return $this->render('client/offres/edit.html.twig', [
            'offre' => $offreMission,
            'form' => $form,
        ]);
    }

    // Supprimer une offre de mission
    #[Route('/offres/{id}/delete', name: 'app_client_offre_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        OffreMission $offreMission,
        EntityManagerInterface $entityManager
    ): Response {
        if ($offreMission->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette offre.');
        }

        // Vérifier le token CSRF avant suppression
        if (!$this->isCsrfTokenValid('delete' . $offreMission->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide. La suppression a été annulée.');
            return $this->redirectToRoute('app_client_offres');
        }

        $entityManager->remove($offreMission);
        $entityManager->flush();
        $this->addFlash('success', 'Offre de mission supprimée.');

        return $this->redirectToRoute('app_client_offres');
    }

    // Voir la facture d'une offre de mission
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
}