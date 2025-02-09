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

    public function Allvente(): array
    {
        return $this->createQueryBuilder('v')
            ->leftJoin('v.recette', 'r')
            ->addSelect('r') 
            ->orderBy('v.dateAchat', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
