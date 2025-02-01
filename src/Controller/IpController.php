<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class IpController extends AbstractController{
    #[Route('/sendIp', name: 'send_ip', methods: ['POST'])]
    public function sendIp(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $ip = $data['ip'] ?? null;

        if (!$ip) {
            return new JsonResponse(['message' => 'IP manquante'], 400);
        }

        // Stocker l'IP dans un fichier ou une base de données
        file_put_contents('/tmp/ips.json', json_encode([$ip], JSON_PRETTY_PRINT));

        return new JsonResponse(['message' => 'IP enregistrée']);
    }
}
