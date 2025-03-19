<?php 
// src/Service/GoogleAnalyticsService.php
namespace App\Service;

use Google\Client;
use Google\Service\AnalyticsData;

class GoogleAnalyticsService
{
    private $client;
    private $analytics;

    public function __construct(string $credentialsPath)
    {
        $this->client = new Client();
        $this->client->setAuthConfig($credentialsPath);
        $this->client->addScope(AnalyticsData::ANALYTICS_READONLY);
        $this->analytics = new AnalyticsData($this->client);
    }

    public function getRealTimeData(string $propertyId): array
    {
        $response = $this->analytics->properties->runRealtimeReport(
            'properties/' . $propertyId,
            [
                'dimensions' => [['name' => 'country']],
                'metrics' => [['name' => 'activeUsers']],
            ]
        );

        return $response->getRows();
    }
}