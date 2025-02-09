<?php

namespace App\Repository;

use App\Entity\Stock;
use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class StockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stock::class);
    }
    public function insertOrUpdateStock(int $ingredientId, int $nombre): Stock
    {
        $entityManager = $this->getEntityManager();
        $ingredient = $entityManager->getRepository(Ingredient::class)->find($ingredientId);
    
        if (!$ingredient) {
            throw new \Exception("L'ingrédient avec l'ID $ingredientId n'existe pas.");
        }
        $stock = $this->findOneBy(['ingredient' => $ingredient]);
    
        if (!$stock) {
            $stock = new Stock();
            $stock->setIngredient($ingredient);
            $stock->setNombre($nombre);
        } else {
            $stock->setNombre($stock->getNombre() + $nombre);
        }
        $entityManager->persist($stock);
        $entityManager->flush();
    
        return $stock;
    }
}