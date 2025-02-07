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
    
        $photoFilename = uniqid().'.'.$photo->guessExtension();
        $assetFilename = uniqid().'.'.$asset->guessExtension();
    
        $photoPath = $this->params->get('recettes_directory') . '/' . $photoFilename;
        $assetPath = $this->params->get('assets_directory') . '/' . $assetFilename;
    
        $photo->move($this->params->get('recettes_directory'), $photoFilename);
        $asset->move($this->params->get('assets_directory'), $assetFilename);
    
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
