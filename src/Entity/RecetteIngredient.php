<?php

namespace App\Entity;

use App\Repository\RecetteIngredientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecetteIngredientRepository::class)]
class RecetteIngredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Relation ManyToOne avec Recette
    #[ORM\ManyToOne(targetEntity: Recette::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recette $id_recette = null;

    // Relation ManyToOne avec Ingredient
    #[ORM\ManyToOne(targetEntity: Ingredient::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ingredient $id_ingredient = null;

    #[ORM\Column]
    private ?int $quantite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdRecette(): ?Recette
    {
        return $this->id_recette;
    }

    public function setIdRecette(Recette $id_recette): static
    {
        $this->id_recette = $id_recette;

        return $this;
    }

    public function getIdIngredient(): ?Ingredient
    {
        return $this->id_ingredient;
    }

    public function setIdIngredient(Ingredient $id_ingredient): static
    {
        $this->id_ingredient = $id_ingredient;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }
}