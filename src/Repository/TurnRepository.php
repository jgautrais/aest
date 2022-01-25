<?php

namespace App\Repository;

use DateTime;
use App\Entity\Turn;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Turn|null find($id, $lockMode = null, $lockVersion = null)
 * @method Turn|null findOneBy(array $criteria, array $orderBy = null)
 * @method Turn[]    findAll()
 * @method Turn[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TurnRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Turn::class);
    }

    public function getStatsAllTime(User $user): array
    {
        $query = $this
            ->createQueryBuilder('t')
            ->select("count(t.accuracyCategory) as turns, sum(t.accuracy) as sumAccuracy")
            ->where('t.user = :user')
            ->groupBy("t.accuracyCategory")
            ->orderBy("t.accuracyCategory", "ASC")
            ->setParameter('user', $user);

        return (array) $query->getQuery()->getResult();
    }

    public function getStatsLastMonth(User $user): array
    {
        $query = $this
            ->createQueryBuilder('t')
            ->select("count(t.accuracyCategory) as turns, sum(t.accuracy) as sumAccuracy")
            ->andwhere('t.user = :user')
            ->andWhere('t.date BETWEEN :l30days AND :today')
            ->groupBy("t.accuracyCategory")
            ->orderBy("t.accuracyCategory", "ASC")
            ->setParameter('user', $user)
            ->setParameter('today', date('Y-m-d h:i:s'))
            ->setParameter('l30days', date('Y-m-d h:i:s', strtotime("-30 days")));

        return (array) $query->getQuery()->getResult();
    }

    public function getStatsLastWeek(User $user): array
    {
        $query = $this
            ->createQueryBuilder('t')
            ->select("count(t.accuracyCategory) as turns, sum(t.accuracy) as sumAccuracy")
            ->andwhere('t.user = :user')
            ->andWhere('t.date BETWEEN :l30days AND :today')
            ->groupBy("t.accuracyCategory")
            ->orderBy("t.accuracyCategory", "ASC")
            ->setParameter('user', $user)
            ->setParameter('today', date('Y-m-d h:i:s'))
            ->setParameter('l30days', date('Y-m-d h:i:s', strtotime("-7 days")));

        return (array) $query->getQuery()->getResult();
    }

    public function getStatsToday(User $user): array
    {
        $query = $this
            ->createQueryBuilder('t')
            ->select("count(t.accuracyCategory) as turns, sum(t.accuracy) as sumAccuracy")
            ->andwhere('t.user = :user')
            ->andWhere('t.date >= :today')
            ->groupBy("t.accuracyCategory")
            ->orderBy("t.accuracyCategory", "ASC")
            ->setParameter('user', $user)
            ->setParameter('today', date('Y-m-d 00:00:00'));

        return (array) $query->getQuery()->getResult();
    }

    // /**
    //  * @return Turn[] Returns an array of Turn objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Turn
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
