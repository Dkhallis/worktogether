<?php

namespace App\Entity;

use App\Enum\EtatUnite;
use App\Repository\UniteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UniteRepository::class)]
class Unite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numero = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nomPersonnalise = null;

    #[ORM\Column(enumType: EtatUnite::class)]
    private ?EtatUnite $etat = null;

    #[ORM\ManyToOne(inversedBy: 'unites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Baie $baie = null;

    #[ORM\ManyToOne(inversedBy: 'unites')]
    private ?TypeUnite $typeUnite = null;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\ManyToMany(targetEntity: Commande::class, mappedBy: 'unites')]
    private Collection $commandes;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getNomPersonnalise(): ?string
    {
        return $this->nomPersonnalise;
    }

    public function setNomPersonnalise(?string $nomPersonnalise): static
    {
        $this->nomPersonnalise = $nomPersonnalise;

        return $this;
    }

    public function getEtat(): ?EtatUnite
    {
        return $this->etat;
    }

    public function setEtat(EtatUnite $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getBaie(): ?Baie
    {
        return $this->baie;
    }

    public function setBaie(?Baie $baie): static
    {
        $this->baie = $baie;

        return $this;
    }

    public function getTypeUnite(): ?TypeUnite
    {
        return $this->typeUnite;
    }

    public function setTypeUnite(?TypeUnite $typeUnite): static
    {
        $this->typeUnite = $typeUnite;

        return $this;
    }

    public function getEmplacement(): string
    {
        return $this->baie->getNumero() . '-U' . str_pad($this->numero, 2, '0', STR_PAD_LEFT);
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->addUnite($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            $commande->removeUnite($this);
        }

        return $this;
    }
}
