<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class IpController extends AbstractController
{
    #[Route('/sendIp', name: 'send_ip', methods: ['POST'])]
    public function sendIp(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $ip = $data['ip'] ?? null;

        if (!$ip) {
            return new JsonResponse(['message' => 'IP manquante'], 400);
        }

        // Log de l'IP reçue
        error_log("IP reçue : " . $ip);

        // Envoyer un message "Bonjour" à cette IP
        $this->sendMessageToClient($ip, "Bonjour du serveur Symfony");

        return new JsonResponse(['message' => 'IP enregistrée et message envoyé']);
    }

    private function sendMessageToClient(string $ip, string $message): void
    {
        $url = "http://127.0.0.1:8000/receiveMessage";
        $data = json_encode(['message' => $message]);
    
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
    
        if ($response === false) {
            error_log("Erreur cURL : " . $error);
        } else {
            error_log("Réponse de Godot : " . $response);
        }
    }
    
}
