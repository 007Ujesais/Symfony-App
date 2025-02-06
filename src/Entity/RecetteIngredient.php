<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: "App\Repository\RecetteIngredientRepository")]
class RecetteIngredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: Recette::class)]
    #[ORM\JoinColumn(nullable: true)]
    private $recette;

    #[ORM\ManyToOne(targetEntity: Ingredient::class)]
    #[ORM\JoinColumn(nullable: true)]
    private $ingredient;

    #[ORM\Column(type: "integer")]
    private $nombre;

    public function getId(): int
    {
        return $this->id;
    }

    public function getRecette(): Recette
    {
        return $this->recette;
    }

    public function setRecette(Recette $recette): self
    {
        $this->recette = $recette;
        return $this;
    }

    public function getIngredient(): Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(Ingredient $ingredient): self
    {
        $this->ingredient = $ingredient;
        return $this;
    }

    public function getNombre(): int
    {
        return $this->nombre;
    }

    public function setNombre(int $nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }
}
