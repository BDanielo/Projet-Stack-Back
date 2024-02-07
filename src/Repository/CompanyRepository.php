<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Company>
 *
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

//    /**
//     * @return Company[] Returns an array of Company objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Company
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function getWaitingForApprovalCompanies(): array
    {
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.validated = :validated')
            ->setParameter('validated', false)
            ->getQuery();

        return $qb->getResult();

    }

    public function searchEvents(string $search): array
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.categories', 'cc')
            ->orWhere('c.name LIKE :search')
            ->orWhere('c.description LIKE :search')
            ->orWhere('c.location LIKE :search')
            ->orWhere('cc.name LIKE :search')
            ->setParameter('search', '%'.$search.'%')
            ->orderBy('c.creationDate', 'DESC')
            ->getQuery();

        return $qb->getResult();
    }
}
