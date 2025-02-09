<?php

namespace App\Repository;

use App\Entity\Vente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class VenteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vente::class);
    }

    public function AllVentes(): array
    {
        return $this->createQueryBuilder('v')
            ->select('DISTINCT r')
            ->innerJoin('v.recette', 'r') 
            ->orderBy('r.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findVenteByName(string $nom): array
    {
        return $this->createQueryBuilder('v')
            ->innerJoin('v.recette', 'r')
            ->addSelect('r')
            ->andWhere('LOWER(r.nom) LIKE LOWER(:nom)')
            ->setParameter('nom', $nom . '%')
            ->groupBy('r.id')
            ->orderBy('r.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
