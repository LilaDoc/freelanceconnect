<?php

namespace App\Controller\API;

use App\Repository\OffreMissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

final class MissionController extends AbstractController
{
    #[Route('/api/missions/recent', name: 'api_mission_recent', methods: ['GET'])]
    public function getRecentMission(OffreMissionRepository $repo)
    {
        $missions = $repo->findRecent();
        return $this->json($missions, 200, [], ['groups' => ['api_missions_recent']]);
    }

    #[Route('/api/missions', name: 'api_missions', methods: ['GET'])]
    public function getMission(OffreMissionRepository $repo)
    {
        $missions = $repo->findAll();
        return $this->json($missions, 200, [], ['groups' => ['api_missions']]);
    }
}
