<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ingredient $id_ingredient = null;

    public function getId(): ?int
    {
        return $this->id;
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
}
