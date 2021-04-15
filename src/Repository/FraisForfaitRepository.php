<?php

namespace App\Repository;

use App\Entity\Fraisforfait;
use App\Entity\LigneFraisForfait;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Fraisforfait|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fraisforfait|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fraisforfait[]    findAll()
 * @method Fraisforfait[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FraisForfaitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fraisforfait::class);
    }

    public function initFraisForfait(User $Visiteur, string $mois){

        //Création des frais forfaits
        $lesFrais = $this->findAll();

        foreach ($lesFrais as $key => $frais) {
            $newFrais = new LigneFraisForfait();
            $newFrais->setVisiteur($Visiteur);
            $newFrais->setMois($mois);
            $newFrais->setFraisForfait($frais);
            $this->getEntityManager()->persist($newFrais);
        }

        $this->getEntityManager()->flush();
    }
    // /**
    //  * @return Fraisforfait[] Returns an array of Fraisforfait objects
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
    public function findOneBySomeField($value): ?Fraisforfait
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
