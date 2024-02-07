<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
