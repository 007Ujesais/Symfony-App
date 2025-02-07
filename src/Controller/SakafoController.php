<?php

namespace App\Controller;

use App\Repository\RecetteRepository;
use App\Repository\RecetteIngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class SakafoController extends AbstractController
{
    #[Route('/recettes', name: 'getRecettes', methods: ['GET'])]
    public function getAllRecettes(RecetteRepository $recetteRepository): JsonResponse
    {
        $recettes = $recetteRepository->AllRecettes();

        return $this->json($recettes);
    }

    #[Route('/recettes/{id}', name: 'recette_show', methods: ['GET'])]
    public function show(int $id, RecetteRepository $recetteRepository): JsonResponse
    {
        $recette = $recetteRepository->findRecetteById($id);
        if (!$recette) {
            return $this->json(['message' => 'Recette non trouvée'], 404);
        }
        return $this->json($recette);
    }

    #[Route('/recettesI/{id}', name:'recette_ingredients', methods: ['GET'])]
    public function getIngredientByRecette(int $id,RecetteIngredientRepository $recetteIngredientRepository): JsonResponse
    {
        $recetteIngredients = $recetteIngredientRepository->findIngredientsByRecette($id);
        if (!$recetteIngredients) {
            return $this->json(['message' => 'Recette non trouvée'], 404);
        }
        return $this->json($recetteIngredients);
    }

    #[Route('/insertrecette', name: 'ajouter_recette', methods: ['POST'])]
    public function ajouterRecette(Request $request, RecetteRepository $recetteRepository): JsonResponse
    {
        dump($request->request->all());
        dump($request->files->all());
        exit();
    
        $nom = $request->request->get('nom');
        $prix = (int) $request->request->get('prix');
        $tempsCuisson = (int) $request->request->get('tempsCuisson');
        
        $photo = $request->files->get('photo');
        $asset = $request->files->get('asset');
    
        if (!$photo || !$asset) {
            return new JsonResponse(['error' => 'Les fichiers sont requis'], 400);
        }
    
        $recette = $recetteRepository->insertPlat($nom, $photo, $asset, $prix, $tempsCuisson);
    
        return new JsonResponse(['message' => 'Recette ajoutée', 'recette' => $recette->getId()]);
    }


}
