<?php

namespace App\Entity;

use App\Enum\EtatUnite;
use App\Repository\UniteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Une unité (un "U") dans une baie. C'est ce que les clients louent.
 * Elle est numérotée de 1 à 42, du bas vers le haut de la baie.
 */
#[ORM\Entity(repositoryClass: UniteRepository::class)]
class Unite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]// Numéro de l'unité dans sa baie (de 1 à 42)
    private ?int $numero = null;

    // Nom donné par le client (facultatif)
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nomPersonnalise = null;

    // État de l'unité : OK, INCIDENT ou MAINTENANCE
    #[ORM\Column(enumType: EtatUnite::class)]
    private ?EtatUnite $etat = null;

    // La baie qui contient l'unité (composition : pas d'unité sans baie)
    #[ORM\ManyToOne(inversedBy: 'unites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Baie $baie = null;

    // Le type choisi par le client (facultatif)
    #[ORM\ManyToOne(inversedBy: 'unites')]
    private ?TypeUnite $typeUnite = null;

    /**
     * @var Collection<int, Commande>
     */
    // Les commandes qui ont réservé cette unité
    #[ORM\ManyToMany(targetEntity: Commande::class, mappedBy: 'unites')]
    private Collection $commandes;

    /**
     * @var Collection<int, Intervention>
     */
    // Les interventions qui ont touché cette unité
    #[ORM\ManyToMany(targetEntity: Intervention::class, mappedBy: 'Unites')]
    private Collection $interventions;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->interventions = new ArrayCollection();
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

    /**
     * Renvoie l'emplacement de l'unité au format du sujet, ex : B001-U03.
     * str_pad ajoute un 0 devant les numéros à un chiffre (3 devient 03).
     */
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

    /**
     * @return Collection<int, Intervention>
     */
    public function getInterventions(): Collection
    {
        return $this->interventions;
    }

    public function addIntervention(Intervention $intervention): static
    {
        if (!$this->interventions->contains($intervention)) {
            $this->interventions->add($intervention);
            $intervention->addUnite($this);
        }

        return $this;
    }

    public function removeIntervention(Intervention $intervention): static
    {
        if ($this->interventions->removeElement($intervention)) {
            $intervention->removeUnite($this);
        }

        return $this;
    }
}
