<?php

namespace App\Controller;

use App\Entity\Follow;
use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    public function __construct(
        private CompanyRepository $repo,
        private EntityManagerInterface $em,
        private UserRepository $userRepo
    )
    {
    }

    public function getWaitingForApprovalCompanies(): Response {
        $companies = $this->repo->getWaitingForApprovalCompanies();

        return $this->json($companies);
    }

    public function searchEvents(Request $request): Response {
        $search = json_decode($request->getContent())->search;
        $events = $this->repo->searchEvents($search);
        return $this->json($events, 200, [], ['groups' => ['company:search', 'Default']]);
    }

    public function addSubscriber($id, Request $request): Response {
        $company = $this->repo->find($id);

        $userId = $this->getUser()->getId();

        $user = $this->userRepo->find($userId);
        $follow = new Follow();
        $follow->setFollower($user)
            ->setCompany($company)
            ->setNotificationEnabled(true);
        $company->addFollow($follow);

        $this->em->persist($follow);
        $this->em->persist($company);
        $this->em->flush();

        return $this->json($company);
    }

    public function removeSubscriber($id, Request $request): Response {
        $company = $this->repo->find($id);

        $userId = $this->getUser()->getId();

        $user = $this->userRepo->find($userId);
        $company->getFollows()->exists(function($key, $element) use ($user) {
            if ($element->getFollower() == $user) {
                $this->em->remove($element);
                return true;
            }
            return false;
        });

        $this->em->persist($company);
        $this->em->flush();

        return $this->json($company);
    }

    public function isUserSubscribe($id): Response {
        $company = $this->repo->find($id);
        $userId = $this->getUser()->getId();
        $user = $this->userRepo->find($userId);
        //check if follows : Follow[] containe follow->getFollower() == $user
        $isSubscribed = $company->getFollows()->exists(function($key, $element) use ($user) {
            return $element->getFollower() == $user;
        });
        return $this->json($isSubscribed);
    }
}
