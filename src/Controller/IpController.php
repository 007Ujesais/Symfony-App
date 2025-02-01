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
        file_put_contents('/tmp/debug.log', "Envoi du message vers $ip:8000\n", FILE_APPEND);
        
        $url = "http://$ip:8000/receiveMessage";
        $data = json_encode(['message' => $message]);
    
        $options = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'POST',
                'content' => $data,
            ],
        ];
    
        $context = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);
    
        if ($result === false) {
            $error = error_get_last();
            file_put_contents('/tmp/debug.log', "Erreur lors de l'envoi : " . json_encode($error) . "\n", FILE_APPEND);
        } else {
            file_put_contents('/tmp/debug.log', "Message envoyé avec succès : $result\n", FILE_APPEND);
        }
    }    
}
