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
            ->innerJoin('v.recette', 'r')
            ->addSelect('r')
            ->groupBy('r.id, v.id, v.dateAchat')
            ->orderBy('r.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
    
    public function getVentesByMonth(): array
    {
        return $this->createQueryBuilder('v')
            ->innerJoin('v.recette', 'r')
            ->addSelect('r')
            ->select('EXTRACT(YEAR FROM v.dateAchat) as year, EXTRACT(MONTH FROM v.dateAchat) as month, SUM(r.prix) as totalPrix, COUNT(v.id) as totalVentes')
            ->groupBy('year, month')
            ->orderBy('year, month', 'ASC')
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
