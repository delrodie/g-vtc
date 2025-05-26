<?php

namespace App\Entity;

use App\Repository\ConduireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConduireRepository::class)]
class Conduire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateDebutAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $montantRecette = null;

    #[ORM\Column(nullable: true)]
    private ?bool $statut = null;

    #[ORM\ManyToOne]
    private ?Chauffeur $chauffeur = null;

    #[ORM\ManyToOne]
    private ?Vehicule $vehicule = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebutAt(): ?\DateTimeImmutable
    {
        return $this->dateDebutAt;
    }

    public function setDateDebutAt(?\DateTimeImmutable $dateDebutAt): static
    {
        $this->dateDebutAt = $dateDebutAt;

        return $this;
    }

    public function getMontantRecette(): ?int
    {
        return $this->montantRecette;
    }

    public function setMontantRecette(?int $montantRecette): static
    {
        $this->montantRecette = $montantRecette;

        return $this;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(?bool $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getChauffeur(): ?Chauffeur
    {
        return $this->chauffeur;
    }

    public function setChauffeur(?Chauffeur $chauffeur): static
    {
        $this->chauffeur = $chauffeur;

        return $this;
    }

    public function getVehicule(): ?Vehicule
    {
        return $this->vehicule;
    }

    public function setVehicule(?Vehicule $vehicule): static
    {
        $this->vehicule = $vehicule;

        return $this;
    }
}
