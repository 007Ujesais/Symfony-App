<?php

// src/Controller/AdminController.php
namespace App\Controller;

use App\Service\GoogleAnalyticsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private $googleAnalyticsService;

    public function __construct(GoogleAnalyticsService $googleAnalyticsService)
    {
        $this->googleAnalyticsService = $googleAnalyticsService;
    }

    #[Route('/admin/analytics', name: 'admin_analytics')]
    public function analytics(): Response
    {
        $propertyId = 'G-LJH9BCFB5R';
        $data = $this->googleAnalyticsService->getRealTimeData($propertyId);

        return $this->render('admin/analytics.html.twig', [
            'analyticsData' => $data,
        ]);
    }

    #[Route('/admin/analytics/data', name: 'admin_analytics_data')]
    public function analyticsData(): JsonResponse
    {
        $propertyId = 'G-LJH9BCFB5R';
        $data = $this->googleAnalyticsService->getRealTimeData($propertyId);

        $formattedData = array_map(function ($row) {
            return [
                'country' => $row->getDimensionValues()[0]->getValue(),
                'activeUsers' => $row->getMetricValues()[0]->getValue(),
            ];
        }, $data);

        return $this->json($formattedData);
    }
}
