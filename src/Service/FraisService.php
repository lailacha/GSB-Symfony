<?php

namespace App\Service;

use App\Entity\Fichefrais;
use App\Entity\LigneFraisForfait;
use App\Repository\FicheFraisRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FraisForfaitRepository;
use App\Repository\LigneFraisHFRepository;
use App\Repository\LigneFraisForfaitRepository;

class FraisService
{

    private $ligneFraisForfaitRepository;
    private $fraisForfaitRepository;
    private $ligneFraisHorsForfaitRepository;
    private $entityManager;
    private $ficheFraisRepository;

    public function __construct(FicheFraisRepository $ficheFraisRepository, EntityManagerInterface $entityManager, LigneFraisForfaitRepository $ligneFraisForfaitRepository, FraisForfaitRepository $fraisForfaitRepository, LigneFraisHFRepository $ligneFraisHorsForfaitRepository)
    {
        $this->ligneFraisForfaitRepository = $ligneFraisForfaitRepository;
        $this->entityManager = $entityManager;
        $this->ficheFraisRepository = $ficheFraisRepository;
        $this->fraisForfaitRepository = $fraisForfaitRepository;
        $this->ligneFraisHorsForfaitRepository = $ligneFraisHorsForfaitRepository;
    }



    public function getMoisSuivant($mois)
    {
        $numAnnee = substr($mois, 0, 4);
        $numMois = substr($mois, 4, 2);
        if ($numMois == "12") {
            $numMois = "01";
            $numAnnee++;
        } else {
            $numMois++;
        }
        if (strlen($numMois) == 1)
            $numMois = "0" . $numMois;
        return $numAnnee . $numMois;
    }

    public function checkIfFicheExist($id, $mois)
    {
        $mois = $this->getMoisSuivant($mois);
        $count = $this->ficheFraisRepository->getCountByIdAndMonth($id, $mois);
        if ($count > 0)
            return true;
        else {
            return false;
        }
    }

    public function reportFrais($lignefraishorsforfait)
    {
        $visiteur = $lignefraishorsforfait->getIdvisiteur();

        //on vérifie si il y a déjà une fiche pour le mois suivant
        $ficheExist = $this->checkIfFicheExist($visiteur, $lignefraishorsforfait->getMois());

        //Si elle existe
        if ($ficheExist) {
            
            //On récupère la dernière fiche existante
            $lastMonth = $this->ficheFraisRepository->getLastMonthByVisiteur($visiteur);
            $lastFiche = $this->ficheFraisRepository->findOneBy([
                'idvisiteur' => $visiteur->getId(),
                'mois' => $lastMonth,
            ]);

            //On l'a cloture
            if ($lastFiche->getIdetat()->getId() === "CR") {
                $lastFiche->setIdetat($this->entityManager->getReference('App\Entity\Etat', 'CL'));
                $lastFiche->setDatemodif(new \DateTime("now"));
            }
            $this->entityManager->persist($lastFiche);

            //création de l'objet du visiteur
            $visiteurEntity = $this->entityManager->getReference('App\Entity\Visiteur', $visiteur->getId());

            //On crée une nouvelle fiche pour le mois qui suivera la dernière fiche existante
            $nextMonth = $this->getMoisSuivant($lastMonth);
            $newFiche = new Fichefrais();
            $newFiche->setIdvisiteur($visiteurEntity);
            dump($lastMonth);
            $newFiche->setMois($nextMonth);
            $newFiche->setIdetat($this->entityManager->getReference('App\Entity\Etat', "CR"));
            $newFiche->setDatemodif(new \DateTime("now"));
            $this->entityManager->persist($newFiche);

            //Création des frais forfaits
            $lesFrais = $this->fraisForfaitRepository->findAll();

            foreach ($lesFrais as $key => $frais) {
                $newFrais = new LigneFraisForfait();
                $newFrais->setVisiteur($visiteurEntity);
                $newFrais->setMois($nextMonth);
                $newFrais->setFraisForfait($frais);
                $this->entityManager->persist($newFrais);
            }

            //Modification du mois du frais reporté
           $lignefraishorsforfait->setMois($nextMonth);
           $this->entityManager->persist($lignefraishorsforfait);

           $this->entityManager->flush();
           
            return 'Frais reporté';
        } else {
             return 'Impossible de cloturer la fiche';
        }

       
    }
}
