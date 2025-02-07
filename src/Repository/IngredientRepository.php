<?php

namespace App\Repository;

use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ingredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ingredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ingredient[]    findAll()
 * @method Ingredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

    // Exemple d'une méthode personnalisée pour rechercher un ingrédient par nom
    public function findIngredientByName($nom)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.nom = :nom')
            ->setParameter('nom', $nom)
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

    public function insertIngredient(string $nom, UploadedFile $photo, UploadedFile $asset): Ingredient 
{
    $entityManager = $this->getEntityManager();

    $photoBinary = file_get_contents($photo->getPathname());
    $assetBinary = file_get_contents($asset->getPathname());

    $ingredient = new Ingredient();
    $ingredient->setNom($nom)
               ->setPhoto($photoBinary)
               ->setAssets($assetBinary);

    $entityManager->persist($ingredient);
    $entityManager->flush();

    return $ingredient;
}
}
