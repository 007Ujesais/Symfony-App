<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: "App\Repository\RecetteRepository")]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $nom;

    #[ORM\Column(type: "blob", nullable: true)]
    private $photo;

    #[ORM\Column(type: "blob", nullable: true)]
    private $assets;

    #[ORM\Column(type: "integer", nullable: true)]
    private $prix;

    #[ORM\Column(type: "integer", nullable: true)]
    private $tempsCuisson;

    public function getId(): int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }
    public function getPhoto(): ?string
    {
        if (is_resource($this->photo)) {
            rewind($this->photo);
            return base64_encode(stream_get_contents($this->photo));
        }
        return null;
    }

    public function getAssets(): ?string
    {
        if (is_resource($this->assets)) {
            rewind($this->assets);
            return base64_encode(stream_get_contents($this->assets));
        }
        return null;
    }
    
        public function setPhoto($photo): self
        {
            $this->photo = $photo;
            return $this;
        }

    public function setAssets($assets): self
    {
        $this->assets = $assets;
        return $this;
    }

    public function getPrix(): int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    public function getTempsCuisson(): int
    {
        return $this->tempsCuisson;
    }

    public function setTempsCuisson(int $tempsCuisson): self
    {
        $this->tempsCuisson = $tempsCuisson;
        return $this;
    }
}