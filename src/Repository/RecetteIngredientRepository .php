<?php

namespace App\Repository;

use App\Entity\RecetteIngredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RecetteIngredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecetteIngredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecetteIngredient[]    findAll()
 * @method RecetteIngredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecetteIngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecetteIngredient::class);
    }

    // Exemple d'une méthode personnalisée pour obtenir les ingrédients d'une recette
    public function findIngredientsByRecette($idRecette)
    {
        return $this->createQueryBuilder('ri')
            ->andWhere('ri.recette = :idRecette')
            ->setParameter('idRecette', $idRecette)
            ->getQuery()
            ->getResult();
    }
}
