<?php

namespace App\Repository;

use App\Entity\RecetteIngredient;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
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
    private $params;
    public function __construct(ManagerRegistry $registry, ParameterBagInterface $params)
    {
        parent::__construct($registry, Recette::class);
        $this->params = $params;
    }

    public function findIngredientsByRecette($idRecette)
    {
        return $this->createQueryBuilder('ri')
            ->andWhere('ri.recette = :idRecette')
            ->setParameter('idRecette', $idRecette)
            ->getQuery()
            ->getResult();
    }

    
}
