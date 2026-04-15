<?php

namespace App\Controller\Front\Client;

use App\Entity\OffreMission;
use App\Repository\OffreMissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/client')]
#[IsGranted('ROLE_CLIENT')]
class ClientDashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_client_dashboard', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();
        
        return $this->render('client/dashboard.html.twig', [
            'user' => $user,
        ]);
    }

   
}
