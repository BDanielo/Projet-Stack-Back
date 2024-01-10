<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

//    /**
//     * @return Event[] Returns an array of Event objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Event
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    // A function to get events of a user for a specific month of current year
    public function findByUserAndMonth(int $userId, int $month): array
    {
        $qb = $this->createQueryBuilder('e')
            ->andWhere('e.startDateTime >= :start')
            ->andWhere('e.startDateTime < :end')
            ->andWhere('o.id = :userId')
            ->Join('e.organizers', 'o')
            ->setParameter('start', new \DateTime(date('Y')."-$month-01"))
            ->setParameter('end', (new \DateTime(date('Y')."-$month-01"))->modify('last day of this month'))
            ->setParameter('userId', $userId)
            ->orderBy('e.startDateTime', 'DESC')
            ->getQuery();

        return $qb->getResult();
    }

    // A function to get the lastest event
    public function findLatest(): array
    {
        $qb = $this->createQueryBuilder('e')
            ->orderBy('e.startDateTime', 'DESC')
            ->setMaxResults(10)
            ->getQuery();

        return $qb->getResult();
    }

    // A function to get events of a user based on its interests
    public function findByUserAndInterests(int $userId): array
    {
        // Get user with userID, then get its interests (tags), then get all event with those tags (not using EntityManager)
        $qb = $this->createQueryBuilder('e')
            ->Join('e.tags', 't')
            ->Join('t.users', 'u')
            ->andWhere('u.id = :userId')
            ->andWhere('e.startDateTime >= :start')
            ->setParameter('start', new \DateTime(date('Y-m-d')))
            ->setParameter('userId', $userId)
            ->orderBy('e.startDateTime', 'DESC')
            ->getQuery();

        return $qb->getResult();
    }

    // A function to counts participants of an event
    public function findParticipants(int $eventId): array
    {
        $qb = $this->createQueryBuilder('e')
            ->Join('e.participants', 'p')
            ->andWhere('e.id = :eventId')
            ->setParameter('eventId', $eventId)
            ->getQuery();

        return $qb->getResult();
    }

}
