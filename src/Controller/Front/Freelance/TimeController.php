<?php
namespace App\Controller\Front\Freelance;

use App\Entity\OffreMission;
use App\Repository\TimeRegisteredRepository;
use App\Service\TimeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/freelance')]
#[IsGranted('ROLE_FREELANCER')]
final class TimeController extends AbstractController
{
    public function __construct(
        private TimeService $timeService,
    ) {}

    #[Route('/mission/{id}/time', name: 'app_freelance_mission_time', methods: ['GET'])]
    public function index(OffreMission $mission, TimeRegisteredRepository $timeRegisteredRepository): Response
    {
        return $this->render('freelance/missions/time.html.twig', [
            'mission'         => $mission,
            'timesRegistered' => $timeRegisteredRepository->findByMission($mission),
        ]);
    }

    #[Route('/mission/{id}/time/add', name: 'app_freelance_mission_time_add', methods: ['POST'])]
    public function add(Request $request, OffreMission $mission): Response
    {
        if (!$this->isCsrfTokenValid('time_add' . $mission->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token invalide.');
            return $this->redirectToRoute('app_freelance_mission_time', ['id' => $mission->getId()]);
        }

        $this->timeService->addTime(
            $mission,
            $this->getUser(),
            (int) $request->request->get('time'),
            new \DateTime($request->request->get('date'))
        );

        $this->addFlash('success', 'Temps enregistré.');
        return $this->redirectToRoute('app_freelance_mission_time', ['id' => $mission->getId()]);
    }

    #[Route('/mission/{id}/time/delete', name: 'app_freelance_mission_time_delete', methods: ['POST'])]
    public function delete(Request $request, OffreMission $mission): Response
    {
        $timeId = (int) $request->request->get('time_id');

        if (!$this->isCsrfTokenValid('time_delete' . $timeId, $request->request->get('_token'))) {
            $this->addFlash('error', 'Token invalide.');
            return $this->redirectToRoute('app_freelance_mission_time', ['id' => $mission->getId()]);
        }

        $this->timeService->deleteTime($timeId, $this->getUser());

        $this->addFlash('success', 'Entrée supprimée.');
        return $this->redirectToRoute('app_freelance_mission_time', ['id' => $mission->getId()]);
    }
}
