<?php

namespace App\Repository;

use App\Entity\Ingredient;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class IngredientRepository extends ServiceEntityRepository
{
    private $params;
    public function __construct(ManagerRegistry $registry, ParameterBagInterface $params)
    {
        parent::__construct($registry, Ingredient::class);
        $this->params = $params;
    }

    public function findIngredientsByName(string $nom): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('LOWER(i.nom) LIKE LOWER(:nom)')
            ->setParameter('nom', $nom . '%')
            ->orderBy('i.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function AllIngredient(): array
    {
        return $this->createQueryBuilder('i')
            ->getQuery()
            ->getResult();
    }

    public function findIngredientById(int $id): ?Ingredient
    {
        return $this->createQueryBuilder('i')
            ->where('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function insertIngredient(string $nom, UploadedFile $photo, UploadedFile $assets): Ingredient 
    {
        $entityManager = $this->getEntityManager();
        
        if (!$photo || !$assets) {
            throw new \Exception('Photo or assets file is missing');
        }
    
        $photoBinary = file_get_contents($photo->getPathname());
        $assetsBinary = file_get_contents($assets->getPathname());
    
        $ingredient = new Ingredient();
        $ingredient->setNom($nom)
                   ->setPhoto($photoBinary)
                   ->setAssets($assetsBinary);
    
        $entityManager->persist($ingredient);
        $entityManager->flush();
    
        return $ingredient;
    }
}
