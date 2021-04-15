<?php

namespace App\Service;

use App\Entity\Fichefrais;
use App\Entity\LigneFraisForfait;
use App\Entity\User;
use App\Repository\FicheFraisRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FraisForfaitRepository;
use App\Repository\LigneFraisHFRepository;
use App\Repository\LigneFraisForfaitRepository;

class FraisService
{

    private $ligneFraisForfaitRepository;
    private $fraisForfaitRepository;
    private $LignefraishorsforfaitRepository;
    private $entityManager;
    private $ficheFraisRepository;

    public function __construct(FicheFraisRepository $ficheFraisRepository, EntityManagerInterface $entityManager, LigneFraisForfaitRepository $ligneFraisForfaitRepository, FraisForfaitRepository $fraisForfaitRepository, LigneFraisHFRepository $LignefraishorsforfaitRepository)
    {
        $this->ligneFraisForfaitRepository = $ligneFraisForfaitRepository;
        $this->entityManager = $entityManager;
        $this->ficheFraisRepository = $ficheFraisRepository;
        $this->fraisForfaitRepository = $fraisForfaitRepository;
        $this->LignefraishorsforfaitRepository = $LignefraishorsforfaitRepository;
    }

    /**
     * Retourne le mois au format aaaamm selon le jour dans le mois
     *
     * @param String $date au format  jj/mm/aaaa
     *
     * @return String Mois au format aaaamm
     */
    function getMois($date): string
    {
        @list($jour, $mois, $annee) = explode('/', $date);
        unset($jour);
        if (strlen($mois) == 1) {
            $mois = '0' . $mois;
        }
        return $annee . $mois;
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

    public function checkIfNextFicheExist($id, $mois): bool
    {
        $mois = $this->getMoisSuivant($mois);
        $count = $this->ficheFraisRepository->getCountByIdAndMonth($id, $mois);
        if ($count > 0)
            return true;
        else {
            return false;
        }
    }


    public function checkIfFicheExist($id, $mois): bool
    {
        $count = $this->ficheFraisRepository->getCountByIdAndMonth($id, $mois);
        if ($count > 0)
            return true;
        else {
            return false;
        }
    }


    public function reportFrais($Lignefraishorsforfait)
    {
        $visiteur = $Lignefraishorsforfait->getIdvisiteur();

        //on vérifie si il y a déjà une fiche pour le mois suivant
        $ficheExist = $this->checkIfNextFicheExist($visiteur, $Lignefraishorsforfait->getMois());

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
            $visiteurEntity = $this->entityManager->getReference('App\Entity\User', $visiteur->getId());

            //On crée une nouvelle fiche pour le mois qui suivera la dernière fiche existante
            $nextMonth = $this->getMoisSuivant($lastMonth);
            $newFiche = new Fichefrais();
            $newFiche->setIdvisiteur($visiteurEntity);
            $newFiche->setMois($nextMonth);
            $newFiche->setIdetat($this->entityManager->getReference('App\Entity\Etat', "CR"));
            $newFiche->setDatemodif(new \DateTime("now"));
            $this->entityManager->persist($newFiche);

            //Création des frais forfaits
        $this->fraisForfaitRepository->initFraisForfait($visiteurEntity, $nextMonth);

            //Modification du mois du frais reporté
            $Lignefraishorsforfait->setMois($nextMonth);
            $this->entityManager->persist($Lignefraishorsforfait);

            $this->entityManager->flush();

            return 'Frais reporté';
        } else {
            return 'Impossible de cloturer la fiche';
        }


    }

}
