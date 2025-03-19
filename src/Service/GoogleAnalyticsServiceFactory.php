<?php

namespace App\Service;

class GoogleAnalyticsServiceFactory
{
    public static function create(): GoogleAnalyticsService
    {
        $credentialsPath = __DIR__ . '/../../config/google_credentials.json';
        return new GoogleAnalyticsService($credentialsPath);
    }
}