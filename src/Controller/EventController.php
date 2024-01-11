<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{


    public function __construct(
        private EventRepository $repo,
        private UserRepository $userRepo,
        private EntityManagerInterface $em
    )
    {
    }

    public function latest(): Response {
        $events = $this->repo->findLatest();

        return $this->json($events);
    }

    public function findByUserAndMonth($id, $month): Response {
        $events = $this->repo->findByUserAndMonth($id, $month);

        return $this->json($events);
    }

    public function findByUserAndInterests($id): Response {
        $events = $this->repo->findByUserAndInterests($id);

        return $this->json($events);
    }

    public function findParticipants($id): Response {
        $events = $this->repo->findParticipants($id);

        return $this->json($events);
    }

    public function addParticipant($id, Request $request): Response {
        $event = $this->repo->find($id);

        $userId = json_decode($request->getContent())->userId;

        $user = $this->userRepo->find($userId);
        $event->addParticipant($user);

        $this->em->persist($event);
        $this->em->flush();

        return $this->json($event);
    }

}
