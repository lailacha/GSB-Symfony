<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Visiteur
 *
 * @ORM\Table(name="Users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class Visiteur
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=4, nullable=false, options={"fixed"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"fiche:mois"})

     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=30, nullable=true, options={"fixed"=true})
     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="prenom", type="string", length=30, nullable=true, options={"fixed"=true})
     */
    private $prenom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="login", type="string", length=20, nullable=true, options={"fixed"=true})
     */
    private $login;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mdp", type="string", length=20, nullable=true, options={"fixed"=true})
     */
    private $mdp;

    /**
     * @var string|null
     *
     * @ORM\Column(name="adresse", type="string", length=30, nullable=true, options={"fixed"=true})
     */
    private $adresse;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cp", type="string", length=5, nullable=true, options={"fixed"=true})
     */
    private $cp;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ville", type="string", length=30, nullable=true, options={"fixed"=true})
     */
    private $ville;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateEmbauche", type="date", nullable=true)
     */
    private $dateembauche;

    /**
     * @ORM\OneToMany(targetEntity=LigneFraisForfait::class, mappedBy="visiteur")
     */
    private $fraisforfait;

    public function __construct()
    {
        $this->fraisforfait = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(?string $mdp): self
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(?string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getDateembauche(): ?\DateTimeInterface
    {
        return $this->dateembauche;
    }

    public function setDateembauche(?\DateTimeInterface $dateembauche): self
    {
        $this->dateembauche = $dateembauche;

        return $this;
    }

    /**
     * @return Collection|LigneFraisForfait[]
     */
    public function getFrais(): Collection
    {
        return $this->fraisforfait;
    }

    public function addFraisForfait(LigneFraisForfait $fraisForfait): self
    {
        if (!$this->frais->contains($fraisForfait)) {
            $this->frais[] = $fraisForfait;
            $fraisForfait->setVisiteur($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function removeFrai(LigneFraisForfait $fraisForfait): self
    {
        if ($this->frais->removeElement($fraisForfait)) {
            // set the owning side to null (unless already changed)
            if ($fraisForfait->getVisiteur() === $this) {
                $fraisForfait->setVisiteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LigneFraisForfait[]
     */
    public function getFraisforfait(): Collection
    {
        return $this->fraisforfait;
    }

    public function removeFraisforfait(LigneFraisForfait $fraisforfait): self
    {
        if ($this->fraisforfait->removeElement($fraisforfait)) {
            // set the owning side to null (unless already changed)
            if ($fraisforfait->getVisiteur() === $this) {
                $fraisforfait->setVisiteur(null);
            }
        }

        return $this;
    }
}
