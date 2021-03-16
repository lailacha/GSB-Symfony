<?php

namespace App\Service;

use App\Repository\FicheFraisRepository;
use App\Repository\LigneFraisHFRepository;

class FraisService
{

    private $ligneFraisHFRepository;
    private $ficheFraisRepository;
    public function __construct(LigneFraisHFRepository $ligneFraisHFRepository, FicheFraisRepository $ficheFraisRepository)
    {
        $this->ligneFraisHFRepository = $ligneFraisHFRepository;
        $this->ficheFraisRepository = $ficheFraisRepository;
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
}
