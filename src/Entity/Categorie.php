<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $nombreProduits = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $exemplesProduits = null;

    /**
     * @var Collection<int, Produit>
     */
    #[ORM\OneToMany(targetEntity: Produit::class, mappedBy: 'categorie')]
    private Collection $produitsCollection;

    public function __construct()
    {
        $this->produitsCollection = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getNombreProduits(): ?int
    {
        return $this->nombreProduits;
    }

    public function setNombreProduits(?int $nombreProduits): static
    {
        $this->nombreProduits = $nombreProduits;

        return $this;
    }

    public function getExemplesProduits(): ?string
    {
        return $this->exemplesProduits;
    }

    public function setExemplesProduits(string $exemplesProduits): static
    {
        $this->exemplesProduits = $exemplesProduits;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduitsCollection(): Collection
    {
        return $this->produitsCollection;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produitsCollection->contains($produit)) {
            $this->produitsCollection->add($produit);
            $produit->setCategorie($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produitsCollection->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getCategorie() === $this) {
                $produit->setCategorie(null);
            }
        }

        return $this;
    }
}
