<?php

namespace App\Repository;

use App\Entity\Fichefrais;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Fichefrais|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fichefrais|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fichefrais[]    findAll()
 * @method Fichefrais[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FicheFraisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fichefrais::class);
    }

    // /**
    //  * @return Fichefrais[] Returns an array of Fichefrais objects
    //  */
    public function findOneByIdJoinedVisiteur($id)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.idvisiteur = :idvisiteur')
            ->setParameter('idvisiteur', $id)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * retrun count of fiche by id and visiteur
    //  */
    public function getCountByIdAndMonth($id, $mois)
    {

        return $this->createQueryBuilder('f')
            ->select('COUNT(f)')
            ->andWhere('f.idvisiteur = :idvisiteur')
            ->andWhere('f.mois = :mois')
            ->setParameter('idvisiteur', $id)
            ->setParameter('mois', $mois)
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function getLastMonthByVisiteur($id)
    {
        return $this->createQueryBuilder('f')
            ->select('max(f.mois)')
            ->andWhere('f.idvisiteur = :idvisiteur')
            ->setParameter('idvisiteur', $id)
            ->getQuery()
            ->getSingleScalarResult();
    }

    // /**
    //  * @return Fichefrais[] Returns an array of Fichefrais objects
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
    public function findOneBySomeField($value): ?Fichefrais
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getAll($search)
    {
        $query = $this->createQueryBuilder('f');

        if ($search->getIdvisiteur()) {
            $query =  $query->andWhere('f.idvisiteur = :visiteur')->setParameter('visiteur', $search->getIdvisiteur());
        }

        if ($search->getIdEtat()) {
            $query =  $query->andWhere('f.idetat = :etat')->setParameter('etat', $search->getIdEtat());
        }
        if ($search->getMois()) {
            $query =  $query->andWhere("f.mois = :mois")->setParameter('mois', $search->getMois());
        }
        $query =  $query->orderBy("f.mois", "DESC");

        return $query->getQuery();
    }
}
