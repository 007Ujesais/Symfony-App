<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $username = $data['email'] ?? null;
        $password = $data['password'] ?? null;

    if (!$username || !$password) {
        return $this->json(['message' => 'Missing credentials'], 400);
    }
        return $this->json([
            'message' => 'Login successful!',
            'user' => $this->getUser() ? $this->getUser()->getUserIdentifier() : null,
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['POST'])]
    public function logout(): void{}
}