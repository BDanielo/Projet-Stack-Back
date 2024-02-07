<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    public function __construct(
        private CompanyRepository $repo,
        private EntityManagerInterface $em
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
        return $this->json($events);
    }
}
