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

    public function findPlatByName(string $nom): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('LOWER(i.nom) LIKE LOWER(:nom)')
            ->setParameter('nom', $nom . '%')
            ->orderBy('i.nom', 'ASC')
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
        $photoBinary = file_get_contents($photo->getPathname());
        $assetBinary = file_get_contents($asset->getPathname());
    
        $recette = new Recette();
        $recette->setNom($nom)
                ->setPhoto($photoBinary)
                ->setAssets($assetBinary)
                ->setPrix($prix)
                ->setTempsCuisson($tempsCuisson);
    
        $entityManager->persist($recette);
        $entityManager->flush();
    
        return $recette;
    }

    

}
