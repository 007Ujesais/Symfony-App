<?php

namespace App\Repository;

use App\Entity\Recette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Recette|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recette|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recette[]    findAll()
 * @method Recette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recette::class);
    }

    // Exemple d'une méthode personnalisée pour trouver les recettes par prix
    public function findRecetteByPrice($prix)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.prix = :prix')
            ->setParameter('prix', $prix)
            ->getQuery()
            ->getResult();
    }
}
