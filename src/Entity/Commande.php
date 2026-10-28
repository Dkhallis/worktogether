<?php

namespace App\Entity;

use App\Enum\TypePaiement;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Une commande passée par un client.
 * Elle contient une ou plusieurs lignes (une par offre achetée)
 * et réserve des unités.
 */
#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Date et heure de la commande
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateCommande = null;

    // Date de début de la location
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateDebut = null;

    // Date de fin de la location (vide si pas encore définie)
    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $dateFin = null;

    #[ORM\Column]// Le client paie au mois ou à l'année
    private ?bool $reconductionTacite = null;

    #[ORM\Column(enumType: TypePaiement::class)] // La location est-elle renouvelée automatiquement à la fin ?
    private ?TypePaiement $typePaiement = null;

    // Le client qui a passé la commande
    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    /**
     * @var Collection<int, Unite>
     */
    // Les lignes de la commande (composition : supprimées avec la commande)
    #[ORM\ManyToMany(targetEntity: Unite::class, inversedBy: 'commandes')]
    private Collection $unites;

    /**
     * @var Collection<int, LigneCommande>
     */
    // Les unités réservées par la commande
    #[ORM\OneToMany(targetEntity: LigneCommande::class, mappedBy: 'commande', orphanRemoval: true)]
    private Collection $lignes;

    public function __construct()
    {
        $this->unites = new ArrayCollection();
        $this->lignes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?\DateTimeImmutable
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeImmutable $dateCommande): static
    {
        $this->dateCommande = $dateCommande;

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

    public function isReconductionTacite(): ?bool
    {
        return $this->reconductionTacite;
    }

    public function setReconductionTacite(bool $reconductionTacite): static
    {
        $this->reconductionTacite = $reconductionTacite;

        return $this;
    }

    public function getTypePaiement(): ?TypePaiement
    {
        return $this->typePaiement;
    }

    public function setTypePaiement(TypePaiement $typePaiement): static
    {
        $this->typePaiement = $typePaiement;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

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
        }

        return $this;
    }

    public function removeUnite(Unite $unite): static
    {
        $this->unites->removeElement($unite);

        return $this;
    }

    /**
     * @return Collection<int, LigneCommande>
     */
    public function getLignes(): Collection
    {
        return $this->lignes;
    }

    public function addLigne(LigneCommande $ligne): static
    {
        if (!$this->lignes->contains($ligne)) {
            $this->lignes->add($ligne);
            $ligne->setCommande($this);
        }

        return $this;
    }

    public function removeLigne(LigneCommande $ligne): static
    {
        if ($this->lignes->removeElement($ligne)) {
            // set the owning side to null (unless already changed)
            if ($ligne->getCommande() === $this) {
                $ligne->setCommande(null);
            }
        }

        return $this;
    }
    /**
     * Calcule le total de la commande : prix x quantité de chaque ligne,
     * additionnés (comme le total d'un ticket de caisse).
     */
    public function calculerTotal(): float
    {
        $total = 0;
        foreach ($this->lignes as $ligne) {
            $total += $ligne->getPrix() * $ligne->getQuantite();
        }
        return $total;
    }
}
