<?php

namespace App\Repository;

use App\Entity\SuggestionHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SuggestionHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method SuggestionHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method SuggestionHistory[]    findAll()
 * @method SuggestionHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuggestionHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SuggestionHistory::class);
    }

    // /**
    //  * @return SuggestionHistory[] Returns an array of SuggestionHistory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SuggestionHistory
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
