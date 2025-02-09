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

    public function insertRecette(int $recetteId, int $ingredientId, int $quantity): RecetteIngredient
    {
        $entityManager = $this->getEntityManager();

        $recette = $entityManager->getRepository(Recette::class)->find($recetteId);
        $ingredient = $entityManager->getRepository(Ingredient::class)->find($ingredientId);

        if (!$recette || !$ingredient) {
            throw new \Exception('Recette ou Ingredient non trouvé.');
        }

        $recetteIngredient = new RecetteIngredient();
        $recetteIngredient->setRecette($recette);
        $recetteIngredient->setIngredient($ingredient);
        $recetteIngredient->setNombre($quantity);

        $entityManager->persist($recetteIngredient);
        $entityManager->flush();

        return $recetteIngredient;
    }
}