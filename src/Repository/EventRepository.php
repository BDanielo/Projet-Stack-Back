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
            ->andWhere('p.id =:userId')
            ->orWhere('o.id = :userId')
            ->Join('e.organizers', 'o')
            ->Join('e.participants', 'p')
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
            ->andWhere('e.endDateTime >= :start')
            ->setParameter('start', new \DateTime(date('Y-m-d')))
            ->orderBy('e.endDateTime', 'DESC')
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

    // A function to search for events by name, description, location, and tags
    public function searchEvents(string $search): array
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.tags', 't')
            ->orWhere('e.name LIKE :search')
            ->orWhere('e.description LIKE :search')
            ->orWhere('e.location LIKE :search')
            ->orWhere('t.name LIKE :search')
            ->setParameter('search', '%'.$search.'%')
            ->orderBy('e.startDateTime', 'DESC')
            ->getQuery();

        return $qb->getResult();
    }

    public function findByCompany($id)
    {
        $qb = $this->createQueryBuilder('e')
            ->Join('e.organizers', 'o')
            ->andWhere('o.id = :id')
            ->setParameter('id', $id)
            ->orderBy('e.startDateTime', 'DESC')
            ->getQuery();

        return $qb->getResult();
    }

    public function isUserParticipant($id, $userId)
    {
        $qb = $this->createQueryBuilder('e')
            ->Join('e.participants', 'p')
            ->andWhere('e.id = :id')
            ->andWhere('p.id = :userId')
            ->setParameter('id', $id)
            ->setParameter('userId', $userId)
            ->getQuery();

        return $qb->getResult();
    }

}
