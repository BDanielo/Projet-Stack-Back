<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class UserController extends AbstractController
{


    public function __construct(
        private UserRepository $repo,
        private Security $security,

    )
    {
    }

    public function findByEmail(Request $request): Response {

        $queryContent = $request->getQueryString();

        // Parse the URL string into variables
        parse_str($queryContent, $params);

        // Get the value of the 'email' parameter
        $email = $params['email'];

        // Decode the URL-encoded email
        $decoded_email = urldecode($email);

        $events = $this->repo->getUserIdByEmail($decoded_email);
        return $this->json($events);
    }

    public function getCurrentUser() : Response
    {
        $token = $this->security->getToken();
        $user = $token->getUser();
        return $this->json($user);
    }

    public function getUserRoles() : Response
    {
        $token = $this->security->getToken();
        $user = $token->getUser();
        return $this->json($user->getRoles());
    }
}

