<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\CompanyRepository;
use App\Repository\EventRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\Clock\now;

class EventController extends AbstractController
{


    public function __construct(
        private EventRepository $repo,
        private UserRepository $userRepo,
        private CompanyRepository $companyRepo,
        private TagRepository $tagRepo,
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

    public function create(Request $request): Response {
        $data = json_decode($request->getContent());

        $event = new Event();
        $event->setName($data->name);
        $event->setDescription($data->description);
        $event->setLocation($data->location);
        $event->setStartDateTime(new \DateTime($data->startDateTime));
        $event->setEndDateTime(new \DateTime($data->endDateTime));

        foreach ($data->organizerIds as $organizerId) {
            $organizer = $this->companyRepo->find($organizerId);
            $event->addOrganizer($organizer);
        }

        foreach ($data->participantIds as $participantId) {
            $participant = $this->userRepo->find($participantId);
            $event->addParticipant($participant);
        }

        foreach ($data->tagIds as $tagId) {
            $tag = $this->tagRepo->find($tagId);
            $event->addTag($tag);
        }


        $this->em->persist($event);
        $this->em->flush();

        return $this->json($event);
    }

    public function searchEvents(Request $request): Response {
        $search = json_decode($request->getContent())->search;
        $events = $this->repo->searchEvents($search);
        return $this->json($events);
    }

    public function findByCompany($id): Response {
        $events = $this->repo->findByCompany($id);
        return $this->json($events);
    }

}
