<?php

namespace App\Controller\Front\Client;

use App\Entity\Candidacy;
use App\Entity\OffreMission;
use App\Form\CandidacyFilterType;
use App\Repository\CandidacyRepository;
use App\Repository\OffreMissionRepository;
use App\Service\CandidacyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/client')]
#[IsGranted('ROLE_CLIENT')]
class CandidatureController extends AbstractController
{
    #[Route('/candidatures', name: 'app_client_candidatures', methods: ['GET'])]
    public function indexAll(
        Request $request,
        CandidacyRepository $candidacyRepository,
        OffreMissionRepository $offreMissionRepository
    ): Response {
        $offres = $offreMissionRepository->findBy(['client' => $this->getUser()]);

        $form = $this->createForm(CandidacyFilterType::class, null, [
            'offres' => $offres,
        ]);
        $form->handleRequest($request);

        $filters = $form->isSubmitted() ? $form->getData() : [];

        return $this->render('client/candidatures/index.html.twig', [
            'candidatures' => $candidacyRepository->findByClientWithFilters($this->getUser(), $filters),
            'filterForm' => $form,
        ]);
    }

    #[Route('/offres/{id}/candidatures', name: 'app_client_offre_candidatures', methods: ['GET'])]
    public function index(
        OffreMission $offreMission,
        CandidacyRepository $candidacyRepository
    ): Response {
        if ($offreMission->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette offre.');
        }

        return $this->render('client/candidatures/index.html.twig', [
            'offre' => $offreMission,
            'candidatures' => $candidacyRepository->findBy(
                ['mission' => $offreMission],
                ['createdAt' => 'DESC']
            ),
        ]);
    }

    #[Route('/candidatures/{id}', name: 'app_client_candidature_show', methods: ['GET'])]
    public function show(Candidacy $candidacy): Response
    {
        if ($candidacy->getMission()->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette candidature.');
        }

        return $this->render('client/candidatures/show.html.twig', [
            'candidature' => $candidacy,
        ]);
    }

    #[Route('/candidatures/{id}/accept', name: 'app_client_candidature_accept', methods: ['POST'])]
    public function accept(
        Request $request,
        Candidacy $candidacy,
        CandidacyService $candidacyService
    ): Response {
        if (!$candidacyService->checkClientOwnCandidacy($this->getUser(), $candidacy)) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette candidature.');
        }

        if (!$this->isCsrfTokenValid('accept' . $candidacy->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_client_candidature_show', ['id' => $candidacy->getId()]);
        }

        $candidacyService->acceptCandidacy($candidacy);
        $this->addFlash('success', 'Candidature acceptée.');

        return $this->redirectToRoute('app_client_candidature_show', ['id' => $candidacy->getId()]);
    }

    #[Route('/candidatures/{id}/refuse', name: 'app_client_candidature_refuse', methods: ['POST'])]
    public function refuse(
        Request $request,
        Candidacy $candidacy,
        CandidacyService $candidacyService
    ): Response {
        if (!$candidacyService->checkClientOwnCandidacy($this->getUser(), $candidacy)) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette candidature.');
        }

        if (!$this->isCsrfTokenValid('refuse' . $candidacy->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_client_candidature_show', ['id' => $candidacy->getId()]);
        }

        $candidacyService->refuseCandidacy($candidacy);
        $this->addFlash('success', 'Candidature refusée.');

        return $this->redirectToRoute('app_client_candidature_show', ['id' => $candidacy->getId()]);
    }

    #[Route('/candidatures/{id}/cv', name: 'app_client_candidature_cv', methods: ['GET'])]
    public function downloadCv(Candidacy $candidacy): Response
    {
        if ($candidacy->getMission()->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette candidature.');
        }

        $cv = $candidacy->getCV();
        if (!$cv) {
            throw $this->createNotFoundException('CV non trouvé.');
        }

        $cvContent = is_resource($cv) ? stream_get_contents($cv) : $cv;

        return new Response($cvContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="CV_' . $candidacy->getFreelance()->getLastName() . '.pdf"',
        ]);
    }
}
