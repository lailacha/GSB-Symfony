<?php

namespace App\Repository;

use App\Entity\FicheSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FicheSearch|null find($id, $lockMode = null, $lockVersion = null)
 * @method FicheSearch|null findOneBy(array $criteria, array $orderBy = null)
 * @method FicheSearch[]    findAll()
 * @method FicheSearch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FicheSearchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FicheSearch::class);
    }

    // /**
    //  * @return FicheSearch[] Returns an array of FicheSearch objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FicheSearch
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
