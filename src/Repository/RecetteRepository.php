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
    
        $recettesDirectory = __DIR__ . '/../../public/uploads/recettes';
        $assetsDirectory = __DIR__ . '/../../public/uploads/assets';
    
        if (!is_dir($recettesDirectory)) {
            mkdir($recettesDirectory, 0755, true);
        }
        if (!is_dir($assetsDirectory)) {
            mkdir($assetsDirectory, 0755, true);
        }
    
        $photoFilename = uniqid().'.'.$photo->guessExtension();
        $assetFilename = uniqid().'.'.$asset->guessExtension();
    
        $photoPath = $recettesDirectory . '/' . $photoFilename;
        $assetPath = $assetsDirectory . '/' . $assetFilename;
    
        try {
            $photo->move($recettesDirectory, $photoFilename);
            $asset->move($assetsDirectory, $assetFilename);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors du déplacement des fichiers : " . $e->getMessage());
        }
    
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
