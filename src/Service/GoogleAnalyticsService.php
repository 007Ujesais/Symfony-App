<?php 
namespace App\Service;

use Google\Client;
use Google\Service\AnalyticsData;
use Google\Service\AnalyticsData\RunRealtimeReportRequest;
use Google\Service\AnalyticsData\Dimension;
use Google\Service\AnalyticsData\Metric;

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
        // Créer une requête pour le rapport en temps réel
        $request = new RunRealtimeReportRequest();

        // Configurer les dimensions (par exemple, le pays)
        $dimension = new Dimension();
        $dimension->setName('country');
        $request->setDimensions([$dimension]);

        // Configurer les métriques (par exemple, les utilisateurs actifs)
        $metric = new Metric();
        $metric->setName('activeUsers');
        $request->setMetrics([$metric]);

        // Exécuter le rapport en temps réel
        $response = $this->analytics->properties->runRealtimeReport(
            'properties/' . $propertyId,
            $request
        );

        // Retourner les lignes de données
        return $response->getRows();
    }
}