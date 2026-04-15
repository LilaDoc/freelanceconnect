<?php

namespace App\Controller\Front\Freelance;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/freelancer')]
#[IsGranted('ROLE_FREELANCER')]
class FreelancerDashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_freelancer_dashboard', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('freelance/dashboard.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}
