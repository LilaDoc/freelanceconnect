<?php

namespace App\Controller\Front\Freelance;

use App\Entity\Candidacy;
use App\Entity\OffreMission;
use App\Form\CandidacyType;
use App\Repository\CandidacyRepository;
use App\Service\CandidacyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/freelance')]
#[IsGranted('ROLE_FREELANCER')]
class CandidatureController extends AbstractController
{
    #[Route('/candidatures', name: 'app_freelance_candidatures', methods: ['GET'])]
    public function index(CandidacyRepository $candidacyRepository): Response
    {
        $candidatures = $candidacyRepository->findBy(
            ['freelance' => $this->getUser()],
            ['createdAt' => 'DESC']
        );

        return $this->render('freelance/candidatures/index.html.twig', [
            'user' => $this->getUser(),
            'candidatures' => $candidatures,
        ]);
    }

    #[Route('/offres/{id}/postuler', name: 'app_freelance_offre_postuler', methods: ['GET', 'POST'])]
    public function postuler(
        Request $request,
        OffreMission $offreMission,
        CandidacyService $candidacyService
    ): Response {
        if ($candidacyService->freelanceHasApplied($this->getUser(), $offreMission)) {
            $this->addFlash('warning', 'Vous avez déjà postulé à cette offre.');
            return $this->redirectToRoute('app_freelance_offre_show', ['id' => $offreMission->getId()]);
        }

        $candidacy = new Candidacy();
        $form = $this->createForm(CandidacyType::class, $candidacy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidacyService->handleCv($candidacy, $form->get('cv')->getData());
            $candidacyService->submitCandidacy($this->getUser(), $offreMission, $candidacy);

            $this->addFlash('success', 'Votre candidature a été envoyée avec succès !');
            return $this->redirectToRoute('app_freelance_offre_show', ['id' => $offreMission->getId()]);
        }

        return $this->render('freelance/candidatures/postuler.html.twig', [
            'offre' => $offreMission,
            'form' => $form,
        ]);
    }

    #[Route('/candidatures/{id}', name: 'app_freelance_candidature_show', methods: ['GET'])]
    public function show(Candidacy $candidacy, CandidacyService $candidacyService): Response
    {
        $candidacyService->checkFreelanceOwnCandidacy($this->getUser(), $candidacy);

        return $this->render('freelance/candidatures/show.html.twig', [
            'candidature' => $candidacy,
        ]);
    }

    #[Route('/candidatures/{id}/delete', name: 'app_freelance_candidature_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Candidacy $candidacy,
        CandidacyService $candidacyService
    ): Response {
        $candidacyService->checkFreelanceOwnCandidacy($this->getUser(), $candidacy);

        if (!$this->isCsrfTokenValid('delete' . $candidacy->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_freelance_candidatures');
        }

        if (!$candidacyService->statusIsPending($candidacy)) {
            $this->addFlash('error', 'Vous ne pouvez pas retirer une candidature déjà traitée.');
            return $this->redirectToRoute('app_freelance_candidatures');
        }

        $candidacyService->deleteCandidacy($candidacy);

        $this->addFlash('success', 'Candidature retirée.');
        return $this->redirectToRoute('app_freelance_candidatures');
    }
}
