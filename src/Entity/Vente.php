<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: "App\Repository\VenteRepository")]
class Vente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: Recette::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private $recette;

    #[ORM\Column(type: "date", nullable: true)]
    private $dateAchat;

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

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->dateAchat;
    }

    public function setDateAchat(?\DateTimeInterface $dateAchat): self
    {
        $this->dateAchat = $dateAchat;
        return $this;
    }
}
