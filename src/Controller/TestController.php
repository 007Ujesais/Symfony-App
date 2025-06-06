<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class TestController extends AbstractController
{
    #[Route('/test', name: 'app_hello')]
    public function hello(): JsonResponse
    {
        return $this->json([
            'message' => 'dawo ihany e',
            'path' => 'src/Controller/TestController.php',
        ]);
    }
}