<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
class Annonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $poste = null;

    #[ORM\Column(length: 255)]
    private ?string $lieuDeTravail = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?bool $Active = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $entreprise = null;


    #[ORM\ManyToOne(inversedBy: 'annonces')]
    private ?Recruteur $recruteur = null;

    #[ORM\OneToMany(mappedBy: 'annonce', targetEntity: Candidature::class)]
    private Collection $candidatures;

    public function __construct()
    {
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): self
    {
        $this->poste = $poste;

        return $this;
    }

    public function getLieuDeTravail(): ?string
    {
        return $this->lieuDeTravail;
    }

    public function setLieuDeTravail(string $lieuDeTravail): self
    {
        $this->lieuDeTravail = $lieuDeTravail;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }


    public function isActive(): ?bool
    {
        return $this->Active;
    }

    public function setActive(?bool $Active): self
    {
        $this->Active = $Active;

        return $this;
    }

    public function getEntreprise(): ?string
    {
        return $this->entreprise;
    }

    public function setEntreprise(?string $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }


    public function getRecruteur(): ?Recruteur
    {
        return $this->recruteur;
    }

    public function setRecruteur(?Recruteur $recruteur): self
    {
        $this->recruteur = $recruteur;

        return $this;
    }


}
