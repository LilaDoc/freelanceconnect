<?php

namespace App\Controller\Front\Freelance;

use App\Entity\Candidacy;
use App\Entity\OffreMission;
use App\Form\CandidacyType;
use App\Repository\CandidacyRepository;
use App\Repository\CandidacyStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        $user = $this->getUser();
        $candidatures = $candidacyRepository->findBy(
            ['freelance' => $user],
            ['createdAt' => 'DESC']
        );

        return $this->render('freelance/candidatures/index.html.twig', [
            'user' => $user,
            'candidatures' => $candidatures,
        ]);
    }

    #[Route('/offres/{id}/postuler', name: 'app_freelance_offre_postuler', methods: ['GET', 'POST'])]
    public function postuler(
        Request $request,
        OffreMission $offreMission,
        EntityManagerInterface $entityManager,
        CandidacyStatusRepository $statusRepository,
        CandidacyRepository $candidacyRepository
    ): Response {
        $user = $this->getUser();

        // Vérifier si le freelancer a déjà postulé
        $existingCandidacy = $candidacyRepository->findOneBy([
            'freelance' => $user,
            'mission' => $offreMission,
        ]);

        if ($existingCandidacy) {
            $this->addFlash('warning', 'Vous avez déjà postulé à cette offre.');
            return $this->redirectToRoute('app_freelance_offre_show', ['id' => $offreMission->getId()]);
        }

        $candidacy = new Candidacy();
        $form = $this->createForm(CandidacyType::class, $candidacy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traitement du CV
            $cvFile = $form->get('cv')->getData();
            if ($cvFile) {
                $cvContent = file_get_contents($cvFile->getPathname());
                $candidacy->setCV($cvContent);
            }

            // Définir les valeurs automatiques
            $candidacy->setFreelance($user);
            $candidacy->setMission($offreMission);
            $candidacy->setCreatedAt(new \DateTimeImmutable());

            // Statut par défaut : PENDING
            
            $status = $statusRepository->findOneBy(['code' => 'PENDING']);
            if ($status === null) {
                $status = $statusRepository->findOneBy([]);
            }
            $candidacy->setStatus($status);

            $entityManager->persist($candidacy);
            $entityManager->flush();

            $this->addFlash('success', 'Votre candidature a été envoyée avec succès !');
            return $this->redirectToRoute('app_freelance_offre_show', ['id' => $offreMission->getId()]);
        }

        return $this->render('freelance/candidatures/postuler.html.twig', [
            'offre' => $offreMission,
            'form' => $form,
        ]);
    }

    #[Route('/candidatures/{id}', name: 'app_freelance_candidature_show', methods: ['GET'])]
    public function show(Candidacy $candidacy): Response
    {
        // Vérifier que la candidature appartient au freelancer connecté
        if ($candidacy->getFreelance() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette candidature.');
        }

        return $this->render('freelance/candidatures/show.html.twig', [
            'candidature' => $candidacy,
        ]);
    }

    #[Route('/candidatures/{id}/delete', name: 'app_freelance_candidature_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Candidacy $candidacy,
        EntityManagerInterface $entityManager
    ): Response {
        if ($candidacy->getFreelance() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette candidature.');
        }

        // Vérifier le token CSRF
        if (!$this->isCsrfTokenValid('delete' . $candidacy->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_freelance_candidatures');
        }

        // On ne peut supprimer que si le statut est PENDING
        if ($candidacy->getStatus()?->getCode() !== 'PENDING') {
            $this->addFlash('error', 'Vous ne pouvez pas retirer une candidature déjà traitée.');
            return $this->redirectToRoute('app_freelance_candidatures');
        }

        $entityManager->remove($candidacy);
        $entityManager->flush();

        $this->addFlash('success', 'Candidature retirée.');
        return $this->redirectToRoute('app_freelance_candidatures');
    }
}