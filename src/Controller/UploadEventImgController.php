<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class UploadEventImgController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em, private LoggerInterface $logger,)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request): Event
    {
//        get file from request content
        $uploadedFile = $request->files->get('file');

        // log $request->request->get('name')
        $this->logger->info('Name: '.$request->request->get('name'));



        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $project_dir = $this->getParameter('project_dir');
        $uploads_directory = $this->getParameter('uploads_directory');

        // Save uploadedFile to /public/uploads
        $fileName = md5(uniqid()) . '.' . $uploadedFile->guessExtension();
        try {
            $uploadedFile->move($project_dir.$uploads_directory, $fileName);
        } catch (FileException $e) {
            // Handle file upload error
            throw new FileException('Failed to upload the file');
        }

        $filepath ='/image/' . $fileName;

        $event = new Event();
        $event->setImage($filepath);
        $event->setName($request->request->get('name'));
        $event->setDescription($request->request->get('description'));
        $event->setLocation($request->request->get('location'));
        $event->setStartDateTime(new \DateTime($request->request->get('startDateTime')));
        $event->setEndDateTime(new \DateTime($request->request->get('endDateTime')));

        // Assuming $request->request->get('organizerIds') contains an array of organizer IDs
        $organizerIds = explode(',', $request->request->get('organizersId'));
        //dump($organizerIds);

        // Fetch Company entities based on organizer IDs

        // Set organizers to the Event entity
        foreach ($organizerIds as $organizerId) {
            $organizer = $this->em->getRepository(Company::class)->find($organizerId);
            $event->addOrganizer($organizer);
        }

        // Assuming $request->request->get('participantIds') contains an array of participant IDs
        $participantIds = explode(',', $request->request->get('participantsId'));

        // Set participants to the Event entity
        foreach ($participantIds as $participantId) {
            $participant = $this->em->getRepository(User::class)->find($participantId);
            $event->addParticipant($participant);
        }



        return $event;
    }
}
