<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LigneFraisForfait
 *
 * @ORM\Table(name="ligne_frais_forfait", indexes={@ORM\Index(name="IDX_BD293ECF7F72333D", columns={"visiteur_id"}), @ORM\Index(name="IDX_BD293ECF7B70375E", columns={"frais_forfait_id"})})
 * @ORM\Entity
 */
class LigneFraisForfait
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="mois", type="string", length=6, nullable=false)
     */
    private $mois;

    /**
     * @var int|null
     *
     * @ORM\Column(name="quantite", type="integer", nullable=true)
     */
    private $quantite;

    /**
     * @var \Fraisforfait
     *
     * @ORM\ManyToOne(targetEntity="Fraisforfait")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="frais_forfait_id", referencedColumnName="id")
     * })
     */
    private $fraisForfait;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="visiteur_id", referencedColumnName="id")
     * })
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

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getFraisForfait(): ?Fraisforfait
    {
        return $this->fraisForfait;
    }

    public function setFraisForfait(?Fraisforfait $fraisForfait): self
    {
        $this->fraisForfait = $fraisForfait;

        return $this;
    }

    public function getVisiteur(): ?User
    {
        return $this->visiteur;
    }

    public function setVisiteur(?User $visiteur): self
    {
        $this->visiteur = $visiteur;

        return $this;
    }


}
