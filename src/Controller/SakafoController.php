<?php

namespace App\Controller;

use App\Repository\Ingredient;
use App\Repository\Recette;
use App\Repository\RecetteIngredient;
use App\Repository\Stock;
use App\Repository\RecetteRepository;
use App\Repository\VenteRepository;
use App\Repository\StockRepository;
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

    #[Route('/ventes', name: 'getVentes', methods: ['GET'])]
    public function getAllVentes(VenteRepository $venteRepository): JsonResponse
    {
        $vente = $venteRepository->Allvente();
        return $this->json($vente, 200, [], []);
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
            return new JsonResponse(['error' => 'Le paramètre "nom" est requis.'], JsonResponse::HTTP_BAD_REQUEST);
        }
    
        $ingredients = $ingredientRepository->findIngredientsByName($nom);
    
        if (!$ingredients) {
            return new JsonResponse(['error' => 'Aucun ingrédient trouvé.'], JsonResponse::HTTP_NOT_FOUND);
        }
    
        $data = array_map(function (Ingredient $ingredient) {
            return [
                'id' => $ingredient->getId(),
                'nom' => $ingredient->getNom(),
                'photo' => $ingredient->getPhoto() ? base64_encode(stream_get_contents($ingredient->getPhoto())) : null,
                'assets' => $ingredient->getAssets() ? base64_encode(stream_get_contents($ingredient->getAssets())) : null
            ];
        }, $ingredients);
    
        return new JsonResponse($data);
    }

    public function getPhoto(): ?string
    {
        if (is_resource($this->photo)) {
            rewind($this->photo);
            return base64_encode(stream_get_contents($this->photo));
        } elseif (is_string($this->photo)) {
            return base64_encode($this->photo);
        }
        return null;
    }

    public function getAssets(): ?string
    {
        if (is_resource($this->assets)) {
            rewind($this->assets);
            return base64_encode(stream_get_contents($this->assets));
        } elseif (is_string($this->assets)) {
            return base64_encode($this->assets);
        }
        return null;
    }
    


    #[Route('/platbyname', name: 'platByName', methods: ['GET'])]
    public function getPlatByName(Request $request, RecetteRepository $platRepository): JsonResponse    
    {
        $nom = $request->query->get('nom');
    
        if (!$nom) {
            return new JsonResponse(['error' => 'Le paramètre "nom" est requis.'], JsonResponse::HTTP_BAD_REQUEST);
        }
    
        $plat = $platRepository->findPlatByName($nom);
    
        if (!$plat) {
            return new JsonResponse(['error' => 'Aucun ingrédient trouvé.'], JsonResponse::HTTP_NOT_FOUND);
        }
    
        $data = array_map(fn(Recette $plat) => [
            'id' => $plat->getId(),
            'nom' => $plat->getNom(),
            'prix' => $plat->getPrix(),
            'temps_cuisson' => $plat->getTempsCuisson(),
            'photo' => $plat->getPhoto(),
            'assets' => $plat->getAssets()
        ], $plat);
        
    
        return new JsonResponse($data);
    }

    #[Route('/stockingredient', name: 'updateStock', methods: ['POST'])]
    public function updateStock(Request $request, StockRepository $stockRepository): JsonResponse
    {
        $response = new JsonResponse();
        $response->headers->set('Access-Control-Allow-Origin', 'https://nahandro.vercel.app');
        $response->headers->set('Access-Control-Allow-Methods', 'POST');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
    
        $ingredientId = $request->request->get('id');
        $nombre = $request->request->get('nombre');
        
        
        if (!$ingredientId || !$nombre) {
            return new JsonResponse(['error' => 'Les paramètres "id" et "nombre" sont requis.'], 400);
        }
        
        try {
            $stock = $stockRepository->insertOrUpdateStock((int) $ingredientId, (int) $nombre);
            return new JsonResponse([
                'stock' => [
                    'ingredientId' => $stock->getIngredient()->getId(),
                    'nombre' => $stock->getNombre()
                ]
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/creerecette', name: 'creerecette', methods: ['POST'])]
    public function createRecette(Request $request, RecetteIngredientRepository $recetteIngredientRepository): JsonResponse    {
        $recetteId = $request->request->get('dishId');
        $ingredients = $request->request->get('ingredients');
    
        error_log('dishId: ' . $recetteId);
        error_log('ingredients: ' . $ingredients);
    
        if (!$recetteId || !$ingredients) {
            return new JsonResponse(['error' => 'Les paramètres "dishId" et "ingredients" sont requis.'], 400);
        }
    
        try {
            $ingredients = json_decode($ingredients, true);
    
            foreach ($ingredients as $ingredient) {
                if (!isset($ingredient['ingredientId']) || !isset($ingredient['quantity'])) {
                    return new JsonResponse(['error' => 'Chaque ingrédient doit avoir un "ingredientId" et une "quantity".'], 400);
                }
    
                error_log('Ingrédient: ' . print_r($ingredient, true));
    
                $recetteIngredientRepository->insertRecette(
                    $recetteId,
                    $ingredient['ingredientId'],
                    $ingredient['quantity']
                );
            }
    
            return new JsonResponse(['message' => 'RecetteIngredient créé avec succès.'], 201);
        } catch (\Exception $e) {
            error_log('Exception: ' . $e->getMessage());
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
