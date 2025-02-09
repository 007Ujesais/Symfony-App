<?php

namespace App\Controller;

use App\Repository\RecetteRepository;
use App\Repository\IngredientRepository;
use App\Repository\RecetteIngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SakafoController extends AbstractController
{
    #[Route('/recettes', name: 'getRecettes', methods: ['GET'])]
    public function getAllRecettes(RecetteRepository $recetteRepository): JsonResponse
    {
        $recettes = $recetteRepository->AllRecettes();
        return $this->json($recettes, 200, [], []);
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

    #[Route('/insertplat', name: 'insertPlat', methods: ['POST'])]
    public function ajouterRecette(Request $request, RecetteRepository $recetteRepository): JsonResponse
    {
        $response = new JsonResponse();
        $response->headers->set('Access-Control-Allow-Origin', 'https://nahandro.vercel.app');
        $response->headers->set('Access-Control-Allow-Methods', 'POST');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
    
        $nom = $request->request->get('nom');
        $prix = $request->request->get('prix');
        $tempsCuisson = $request->request->get('tempsCuisson');
        $photo = $request->files->get('photo');
        $assets = $request->files->get('assets');
    
        try {
            $recette = $recetteRepository->insertPlat($nom, $photo, $assets, $prix, $tempsCuisson);
            $response->setData(['message' => 'Recette ajoutée', 'recette' => $recette->getId()]);
        }  catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    
        return $response;
    }

    #[Route('/insertingredient', name: 'insertIngredient', methods: ['POST'])]
    public function ajouterIngredient(Request $request, IngredientRepository $ingredientRepository): JsonResponse
    {
        $response = new JsonResponse();
        $response->headers->set('Access-Control-Allow-Origin', 'https://nahandro.vercel.app');
        $response->headers->set('Access-Control-Allow-Methods', 'POST');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
    
        $nom = $request->request->get('nom');
        $photo = $request->files->get('photo');
        $assets = $request->files->get('assets');
    
        if (!$nom || !$photo || !$assets) {
            return new JsonResponse(['error' => 'Missing data'], 400);
        }
    
        try {
            $ingredient = $ingredientRepository->insertIngredient($nom, $photo, $assets);
            $response->setData(['message' => 'Ingredient ajoutée', 'ingredient' => $ingredient->getId()]);
        }  catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
        return $response;
    }

    #[Route('/ingredientbyname', name: 'ingredientByName', methods: ['GET'])]
    public function getIngredientByName(Request $request, IngredientRepository $ingredientRepository): JsonResponse
    {
        $nom = $request->query->get('nom');
        if (!$nom) {
            return new JsonResponse(['error' => 'Le paramètre "nom" est requis.'], 400);
        }
        $ingredient = $ingredientRepository->findIngredientByName($nom);
        if (!$ingredient) {
            return new JsonResponse(['message' => 'Aucun ingrédient trouvé avec ce nom.'], 404);
        }
        return $this->json($ingredient);
    }

}
