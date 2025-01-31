<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class TestController extends AbstractController
{
    #[Route('/hello', name: 'app_hello')]
    public function hello(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TestController.php',
        ]);
    }

    #[Route('/test', name: 'app_test')]
    public function test(): JsonResponse
    {
        // Données à envoyer dans le corps de la requête POST
        $postData = json_encode([
            "message" => "Hello from Insomnia!"
        ]);

        // Configuration de la requête HTTP POST
        $options = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'POST',
                'content' => $postData,
            ],
        ];

        // Créer un contexte de flux
        $context = stream_context_create($options);

        // Faire la requête POST vers le serveur Godot
        $godotResponse = file_get_contents('http://localhost:8080', false, $context);

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