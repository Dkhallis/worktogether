<?php

namespace App\Entity;

use App\Repository\TypeUniteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Un type d'unité choisi par le client, associé à une couleur
 * (ex : Serveur web en bleu, Base de données en vert).
 */
#[ORM\Entity(repositoryClass: TypeUniteRepository::class)]
class TypeUnite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]// Nom du type (ex : Serveur web)
    private ?string $libelle = null;

    #[ORM\Column(length: 20)]// Couleur d'affichage (bleu, vert, gris), utilisée dans le CSS
    private ?string $couleur = null;

    /**
     * @var Collection<int, Unite>
     */
    // Les unités de ce type
    #[ORM\OneToMany(targetEntity: Unite::class, mappedBy: 'typeUnite')]
    private Collection $unites;

    public function __construct()
    {
        $this->unites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): static
    {
        $this->couleur = $couleur;

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
            $unite->setTypeUnite($this);
        }

        return $this;
    }

    public function removeUnite(Unite $unite): static
    {
        if ($this->unites->removeElement($unite)) {
            // set the owning side to null (unless already changed)
            if ($unite->getTypeUnite() === $this) {
                $unite->setTypeUnite(null);
            }
        }

        return $this;
    }
}
