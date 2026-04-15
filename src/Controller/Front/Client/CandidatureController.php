<?php

namespace App\Controller\Front\Client;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/client')]
#[IsGranted('ROLE_CLIENT')]
class CandidatureController extends AbstractController
{
    #[Route('/candidatures', name: 'app_client_candidatures', methods: ['GET'])]
    public function index(): Response
    {
        // TODO: Récupérer les candidatures reçues sur les offres du client
        return $this->render('client/candidatures/index.html.twig', [
            'candidatures' => [],
        ]);
    }
}
