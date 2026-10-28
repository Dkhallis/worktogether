<?php

namespace App\Entity;

use App\Enum\TypeIntervention;
use App\Repository\InterventionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Une intervention de WorkTogether sur une ou plusieurs unités.
 */
#[ORM\Entity(repositoryClass: InterventionRepository::class)]
class Intervention
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Type : INCIDENT ou MAINTENANCE
    #[ORM\Column(enumType: TypeIntervention::class)]
    private ?TypeIntervention $type = null;

    // Description de l'intervention
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]// Date et heure de début
    private ?\DateTimeImmutable $dateDebut = null;

    #[ORM\Column(nullable: true)]// Date et heure de fin (vide si l'intervention est en cours)
    private ?\DateTimeImmutable $dateFin = null;

    /**
     * @var Collection<int, Unite>
     */
    // Les unités concernées
    #[ORM\ManyToMany(targetEntity: Unite::class, inversedBy: 'interventions')]
    private Collection $Unites;

    public function __construct()
    {
        $this->Unites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?TypeIntervention
    {
        return $this->type;
    }

    public function setType(TypeIntervention $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeImmutable
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeImmutable $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeImmutable
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeImmutable $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * @return Collection<int, Unite>
     */
    public function getUnites(): Collection
    {
        return $this->Unites;
    }

    public function addUnite(Unite $unite): static
    {
        if (!$this->Unites->contains($unite)) {
            $this->Unites->add($unite);
        }

        return $this;
    }

    public function removeUnite(Unite $unite): static
    {
        $this->Unites->removeElement($unite);

        return $this;
    }
}
