<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateCreation = null;

    /**
     * @var Collection<int, LignePanier>
     */
    #[ORM\OneToMany(targetEntity: LignePanier::class, mappedBy: 'panier')]
    private Collection $lignesPanier;

    public function __construct()
    {
        $this->lignesPanier = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreation(): ?\DateTimeImmutable
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeImmutable $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * @return Collection<int, LignePanier>
     */
    public function getLignesPanier(): Collection
    {
        return $this->lignesPanier;
    }

    public function addLignePanier(LignePanier $lignePanier): static
    {
        if (!$this->lignesPanier->contains($lignePanier)) {
            $this->lignesPanier->add($lignePanier);
            $lignePanier->setPanier($this);
        }

        return $this;
    }

    public function removeLignePanier(LignePanier $lignePanier): static
    {
        if ($this->lignesPanier->removeElement($lignePanier)) {
            // set the owning side to null (unless already changed)
            if ($lignePanier->getPanier() === $this) {
                $lignePanier->setPanier(null);
            }
        }

        return $this;
    }
}
