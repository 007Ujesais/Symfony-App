<?php

namespace App\Repository;

use App\Entity\RecetteIngredient;
use App\Entity\Recette;
use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RecetteIngredientRepository extends ServiceEntityRepository
{
    private $params;

    public function __construct(ManagerRegistry $registry, ParameterBagInterface $params)
    {
        parent::__construct($registry, RecetteIngredient::class);
        $this->params = $params;
    }

    public function insertRecette(int $recetteId, int $ingredientId, int $quantity): void
    {
        $recette = $this->getEntityManager()->getRepository(Recette::class)->find($recetteId);
        $ingredient = $this->getEntityManager()->getRepository(Ingredient::class)->find($ingredientId);
    
        if (!$recette || !$ingredient) {
            throw new \Exception('Recette ou Ingredient non trouvé.');
        }
    
        $recetteIngredient = new RecetteIngredient();
        $recetteIngredient->setRecette($recette);
        $recetteIngredient->setIngredient($ingredient);
        $recetteIngredient->setNombre($quantity);
    
        $this->getEntityManager()->persist($recetteIngredient);
        $this->getEntityManager()->flush();
    }
}