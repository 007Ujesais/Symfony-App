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

        // Stocker l'IP dans un fichier
        file_put_contents('/tmp/ips.json', json_encode([$ip], JSON_PRETTY_PRINT));

        // Envoyer un message "Bonjour" à cette IP
        $this->sendMessageToClient($ip, "Bonjour du seveur symfony");

        return new JsonResponse(['message' => 'IP enregistrée et message envoyé']);
    }

    private function sendMessageToClient(string $ip, string $message): void 
    {
        $url = "http://$ip:8000/receiveMessage";
        $data = json_encode(['message' => $message]);
    
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            file_put_contents('/tmp/debug.log', "❌ cURL erreur: " . curl_error($ch) . "\n", FILE_APPEND);
        } else {
            file_put_contents('/tmp/debug.log', "✅ Message envoyé: HTTP $httpCode | Réponse: $response\n", FILE_APPEND);
        }
        
        curl_close($ch);
    }    
}
