<?php
namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Recette;
use App\Entity\Stock;
use App\Repository\RecetteRepository;
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

    #[Route('/platbyname', name: 'platByName', methods: ['GET'])]
    public function getPlatByName(Request $request, PlatRepository $platRepository): JsonResponse    
    {
        $nom = $request->query->get('nom');
    
        if (!$nom) {
            return new JsonResponse(['error' => 'Le paramètre "nom" est requis.'], JsonResponse::HTTP_BAD_REQUEST);
        }
    
        $plat = $platRepository->findPlatByName($nom);
    
        if (!$plat) {
            return new JsonResponse(['error' => 'Aucun ingrédient trouvé.'], JsonResponse::HTTP_NOT_FOUND);
        }
    
        $data = array_map(function (Recette $plat) {
            return [
                'id' => $plat->getId(),
                'nom' => $plat->getNom(),
                'prix' => $plat->getPrix(),
                'temps_cuisson' => $plat->getTempsCuisson(),
                'photo' => $plat->getPhoto() ? base64_encode(stream_get_contents($plat->getPhoto())) : null,
                'assets' => $plat->getAssets() ? base64_encode(stream_get_contents($plat->getAssets())) : null
            ];
        }, $plat);
    
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
}
