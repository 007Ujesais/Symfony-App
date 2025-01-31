<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(): JsonResponse
    {
        // Faire une requête GET vers le serveur Godot
        $godotResponse = file_get_contents('http://localhost:8080');

        // Vérifier si la requête a réussi
        if ($godotResponse === FALSE) {
            $godotResponse = "Erreur : Impossible de contacter le serveur Godot.";
        }

        // Retourner une réponse JSON
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'message_godot' => $godotResponse,  // Inclure la réponse du serveur Godot
            'path' => 'src/Controller/TestController.php',
        ]);
    }
}