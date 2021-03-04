<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class FicheSearch
{


    /*
     * @var Int
     */
  private $visiteur;

  /*
   * @var string
   */
  private $mois;


    public function getVisiteur()
    {
        return $this->visiteur;
    }

    public function setVisiteur($visiteur)
    {
        $this->visiteur = $visiteur;
        return $this;
    }


    public function getMois(): ?string
    {
        return $this->mois;
    }

    public function setMois(?string $mois): self
    {
        $this->mois = $mois;

        return $this;
    }
}
