<?php

namespace App\Repository;

use App\Entity\Recette;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RecetteRepository extends ServiceEntityRepository
{
    private $params;
    public function __construct(ManagerRegistry $registry, ParameterBagInterface $params)
    {
        parent::__construct($registry, Recette::class);
        $this->params = $params;
    }
    

    public function AllRecettes(): array
    {
        return $this->createQueryBuilder('r')
            ->getQuery()
            ->getResult();
    }

    public function findRecetteById(int $id): ?Recette
    {
        return $this->createQueryBuilder('r')
            ->where('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function insertPlat(string $nom, UploadedFile $photo, UploadedFile $asset, int $prix, int $tempsCuisson): Recette 
    {
        $entityManager = $this->getEntityManager();

        $uploadsDir = __DIR__ . '/../../uploads';
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0755, true);
        }
        $recettesDir = $uploadsDir . '/recettes';
        $assetsDir = $uploadsDir . '/assets'; 
    
        if (!is_dir($recettesDir)) {
            mkdir($recettesDir, 0755, true);
        }
        if (!is_dir($assetsDir)) {
            mkdir($assetsDir, 0755, true);
        }
    
        $photoFilename = uniqid() . '.' . $photo->guessExtension();
        $assetFilename = uniqid() . '.' . $asset->guessExtension();
    
        $photoPath = $recettesDir . '/' . $photoFilename;
        $assetPath = $assetsDir . '/' . $assetFilename;
    
        $photo->move($recettesDir, $photoFilename);
        $asset->move($assetsDir, $assetFilename);
    
        $recette = new Recette();
        $recette->setNom($nom)
                ->setPhoto($photoFilename)
                ->setAssets($assetFilename)
                ->setPrix($prix)
                ->setTempsCuisson($tempsCuisson);
    
        $entityManager->persist($recette);
        $entityManager->flush();
    
        return $recette;
    }

    

}
