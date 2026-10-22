<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OffreRepository::class)]
class Offre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $nbUnites = null;

    #[ORM\Column]
    private ?float $prixMensuel = null;

    #[ORM\Column]
    private ?float $remise = null;

    #[ORM\Column]
    private ?bool $active = null;

    /**
     * @var Collection<int, LigneCommande>
     */
    #[ORM\OneToMany(targetEntity: LigneCommande::class, mappedBy: 'offre')]
    private Collection $lignesCommande;

    public function __construct()
    {
        $this->lignesCommande = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNbUnites(): ?int
    {
        return $this->nbUnites;
    }

    public function setNbUnites(int $nbUnites): static
    {
        $this->nbUnites = $nbUnites;

        return $this;
    }

    public function getPrixMensuel(): ?float
    {
        return $this->prixMensuel;
    }

    public function setPrixMensuel(float $prixMensuel): static
    {
        $this->prixMensuel = $prixMensuel;

        return $this;
    }

    public function getRemise(): ?float
    {
        return $this->remise;
    }

    public function setRemise(float $remise): static
    {
        $this->remise = $remise;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection<int, LigneCommande>
     */
    public function getLignesCommande(): Collection
    {
        return $this->lignesCommande;
    }

    public function addLignesCommande(LigneCommande $lignesCommande): static
    {
        if (!$this->lignesCommande->contains($lignesCommande)) {
            $this->lignesCommande->add($lignesCommande);
            $lignesCommande->setOffre($this);
        }

        return $this;
    }

    public function removeLignesCommande(LigneCommande $lignesCommande): static
    {
        if ($this->lignesCommande->removeElement($lignesCommande)) {
            // set the owning side to null (unless already changed)
            if ($lignesCommande->getOffre() === $this) {
                $lignesCommande->setOffre(null);
            }
        }

        return $this;
    }
}
