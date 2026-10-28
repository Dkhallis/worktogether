<?php

namespace App\Entity;

use App\Repository\BaieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Une baie du datacenter. WorkTogether en possède 30 (B001 à B030).
 * Chaque baie contient 42 unités.
 */
#[ORM\Entity(repositoryClass: BaieRepository::class)]
class Baie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]// Numéro de la baie (ex : B001)
    private ?string $numero = null;

    /**
     * @var Collection<int, Unite>
     */
    // Les 42 unités de la baie
    #[ORM\OneToMany(targetEntity: Unite::class, mappedBy: 'baie', orphanRemoval: true)]
    private Collection $unites;

    public function __construct()
    {
        $this->unites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * @return Collection<int, Unite>
     */
    public function getUnites(): Collection
    {
        return $this->unites;
    }

    public function addUnite(Unite $unite): static
    {
        if (!$this->unites->contains($unite)) {
            $this->unites->add($unite);
            $unite->setBaie($this);
        }

        return $this;
    }

    public function removeUnite(Unite $unite): static
    {
        if ($this->unites->removeElement($unite)) {
            // set the owning side to null (unless already changed)
            if ($unite->getBaie() === $this) {
                $unite->setBaie(null);
            }
        }

        return $this;
    }
}
