<?php

namespace App\Entity;

use App\Repository\ChauffeurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ChauffeurRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['nom','telephone'], message: "Ce chauffeur a déjà été ajouté")]
class Chauffeur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $matricule = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $permis = null;

    private ?Conduire $nouvelleAttribution = null;
//    private $chauffeurRepository = $chauffeurRepository;
    private ChauffeurRepository $chauffeurRepository;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(?string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getPermis(): ?string
    {
        return $this->permis;
    }

    public function setPermis(?string $permis): static
    {
        $this->permis = $permis;

        return $this;
    }

    #[ORM\PrePersist]
    public function generateMatricule(PrePersistEventArgs $event): void
    {
        $entityManager = $event->getObjectManager();
        $chauffeurRepository = $entityManager->getRepository(Chauffeur::class);
        do{
            $variable = str_pad((int)random_int(0, 99), 2, '0', STR_PAD_LEFT);
            $matricule = date('ym').$variable;
        } while($chauffeurRepository->findOneBy(['matricule'=> $matricule]));

        $this->matricule = $matricule;
    }

    public function getNouvelleAttribution(): ?Conduire
    {
        return $this->nouvelleAttribution;
    }

    public function setNouvelleAttribution(?Conduire $nouvelleAttribution): static
    {
        $this->nouvelleAttribution = $nouvelleAttribution;
        return $this;
    }
}
