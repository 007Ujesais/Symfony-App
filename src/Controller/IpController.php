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
    // Si ton serveur Godot utilise l'IP 192.168.11.211, change cette valeur
    $url = "http://192.168.11.211:8000/receiveMessage"; 
    $data = json_encode(['message' => $message]);

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => $data,
        ],
    ];
    
    // Utiliser stream_context_create pour la configuration
    $context = stream_context_create($options);

    // Utiliser file_get_contents avec l'option de contexte
    $response = @file_get_contents($url, false, $context);

    // Ajouter un contrôle pour la réponse (l'erreur ou succès)
    if ($response === false) {
        // Si la requête échoue
        error_log("Erreur lors de l'envoi du message à Godot");
    } else {
        // Affiche le contenu de la réponse si elle est reçue
        error_log("Réponse reçue : " . $response);
    }
}


}
