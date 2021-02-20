<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LigneFraisForfaitRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LigneFraisForfaitRepository::class)
 */
class LigneFraisForfait
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"show_frais"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=6)
     * @Groups({"show_frais"})
     */
    private $mois;

    /**
     * @ORM\Column(type="string", length=3)
     * @Groups({"show_frais"})
     */
    private $idFraisForfait;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * 
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity=Visiteur::class, inversedBy="fraisforfait")
     * @ORM\JoinColumn(nullable=false)
     * 
     */
    private $visiteur;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getMois(): ?string
    {
        return $this->mois;
    }

    public function setMois(string $mois): self
    {
        $this->mois = $mois;

        return $this;
    }

    public function getIdFraisForfait(): ?string
    {
        return $this->idFraisForfait;
    }

    public function setIdFraisForfait(string $idFraisForfait): self
    {
        $this->idFraisForfait = $idFraisForfait;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getVisiteur(): ?Visiteur
    {
        return $this->visiteur;
    }

    public function setVisiteur(?Visiteur $visiteur): self
    {
        $this->visiteur = $visiteur;

        return $this;
    }
}
