<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{


    public function __construct(
        private UserRepository $repo,
    )
    {
    }

    public function findByEmail(Request $request): Response {
        $requestContent = $request->getContent();

        //form request content (which is in json) get email value
        $email = json_decode($requestContent)->email;

        $events = $this->repo->getUserIdByEmail($email);
        return $this->json($events);
    }
}

