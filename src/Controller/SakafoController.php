<?php

namespace App\Controller;

use App\Repository\RecetteRepository;
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

    #[Route('/insertplat', name: 'insertPlat', methods: ['POST'])]
    public function ajouterRecette(Request $request, RecetteRepository $recetteRepository): JsonResponse
    {
        // Ajouter les headers CORS
        $response = new JsonResponse();
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:5173');
        $response->headers->set('Access-Control-Allow-Methods', 'POST');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
    
        // Récupérer les données du formulaire
        $nom = $request->request->get('nom');
        $prix = $request->request->get('prix');
        $tempsCuisson = $request->request->get('tempsCuisson');
        $photo = $request->files->get('photo');
        $assets = $request->files->get('assets');
    
        // Vérifier les champs requis
        if (!$nom || !$prix || !$tempsCuisson) {
            return new JsonResponse(['error' => 'Tous les champs sont requis'], 400);
        }
    
        // Traiter les fichiers (exemple : sauvegarder sur le serveur)
        $photoPath = null;
        $assetsPath = null;
    
        if ($photo) {
            $photoPath = $this->saveFile($photo, 'photo');
        }
    
        if ($assets) {
            $assetsPath = $this->saveFile($assets, 'assets');
        }
    
        // Insérer la recette
        $recette = $recetteRepository->insertPlat($nom, $photoPath, $assetsPath, $prix, $tempsCuisson);
    
        // Retourner une réponse JSON
        $response->setData(['message' => 'Recette ajoutée', 'recette' => $recette->getId()]);
        return $response;
    }
    
    private function saveFile($file, $prefix): string
    {
        $uploadsDir = $this->getParameter('kernel.project_dir') . '/public/uploads';
        $fileName = $prefix . '_' . uniqid() . '.' . $file->guessExtension();
    
        $file->move($uploadsDir, $fileName);
    
        return '/uploads/' . $fileName;
    }

    #[Route('/testpost', name: 'test_post', methods: ['POST'])]
    public function testPost(Request $request): JsonResponse
    {
        $data = $request->request->all();
        return $this->json([
            'message' => 'Requête POST reçue avec succès',
            'data' => $data
        ]);
    }

}
