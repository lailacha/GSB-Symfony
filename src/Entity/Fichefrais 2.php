<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Fichefrais
 *
 * @ORM\Table(name="Fichefrais", indexes={@ORM\Index(name="idEtat", columns={"idEtat"}), @ORM\Index(name="IDX_DD88A8D81D06ADE3", columns={"idVisiteur"})})
 * @ORM\Entity(repositoryClass="App\Repository\FicheFraisRepository")
 */
class Fichefrais
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="mois", type="string", length=6, nullable=false, unique=true, options={"fixed"=true})
     * @Groups({"fiche:mois"})
     */
    private $mois;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbJustificatifs", type="integer", nullable=true)
     */
    private $nbjustificatifs;

    /**
     * @var string|null
     *
     * @ORM\Column(name="montantValide", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $montantvalide;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateModif", type="date", nullable=true)
     */
    private $datemodif;

    /**
     * @var \Etat
     *
     * @ORM\ManyToOne(targetEntity="Etat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idEtat", referencedColumnName="id")
     * })
     */
    private $idetat;

    /**
     * @var \User
     *
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idVisiteur", referencedColumnName="id")
     * })
     * @Groups({"fiche:mois"})

     */
    private $idvisiteur;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Fraisforfait", mappedBy="idvisiteur")
     */
    private $idfraisforfait;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idfraisforfait = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getMois(): ?string
    {
        return $this->mois;
    }

    public function setMois(string $mois)
    {
        $this->mois = $mois;

        return $this;
    }
    public function getNbjustificatifs(): ?int
    {
        return $this->nbjustificatifs;
    }

    public function setNbjustificatifs(?int $nbjustificatifs): self
    {
        $this->nbjustificatifs = $nbjustificatifs;

        return $this;
    }

    public function getMontantvalide(): ?string
    {
        return $this->montantvalide;
    }

    public function setMontantvalide(?string $montantvalide): self
    {
        $this->montantvalide = $montantvalide;

        return $this;
    }

    public function getDatemodif(): ?\DateTimeInterface
    {
        return $this->datemodif;
    }

    public function setDatemodif(?\DateTimeInterface $datemodif): self
    {
        $this->datemodif = $datemodif;

        return $this;
    }

    public function __toString(): string
    {
        return $this->mois;
    }

    public function getIdetat()
    {
        return $this->idetat;
    }

    public function setIdetat(?Etat $idetat): self
    {
        $this->idetat = $idetat;

        return $this;
    }

    public function getIdvisiteur()
    {
        return $this->idvisiteur;
    }

    public function setIdvisiteur(?User $idvisiteur): self
    {
        $this->idvisiteur = $idvisiteur;

        return $this;
    }

    /**
     * @return Collection|Fraisforfait[]
     */
    public function getIdfraisforfait(): Collection
    {
        return $this->idfraisforfait;
    }

    public function addIdfraisforfait(Fraisforfait $idfraisforfait): self
    {
        if (!$this->idfraisforfait->contains($idfraisforfait)) {
            $this->idfraisforfait[] = $idfraisforfait;
            $idfraisforfait->addIdvisiteur($this);
        }

        return $this;
    }

    public function removeIdfraisforfait(Fraisforfait $idfraisforfait): self
    {
        if ($this->idfraisforfait->removeElement($idfraisforfait)) {
            $idfraisforfait->removeIdvisiteur($this);
        }

        return $this;
    }

    public function getFormatedMonth()
    {
        $mois = $this->mois;
        $numAnnee = substr($mois, 0, 4);
        $numMois = substr($mois, 4, 2);
        return $numAnnee . '/' . $numMois;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
