<?php


namespace App\GoogleAnalytics;


use Bolt\Widget\BaseWidget;
use Bolt\Widget\CacheAware;
use Bolt\Widget\CacheTrait;
use Bolt\Widget\Injector\AdditionalTarget;
use Bolt\Widget\Injector\RequestZone;
use Bolt\Widget\StopwatchAware;
use Bolt\Widget\StopwatchTrait;
use Bolt\Widget\TwigAware;
use Google_Client;
use Google_Service_Analytics;
use Google_Service_Analytics_GaData;

class AnalyticsWidget extends BaseWidget implements TwigAware, CacheAware, StopwatchAware
{
    use CacheTrait;
    use StopwatchTrait;

    protected $name = 'Google Analytics';
    protected $target = AdditionalTarget::WIDGET_BACK_DASHBOARD_ASIDE_TOP;
    protected $priority = 50;
    protected $template = '@google-analytics/widget.html.twig';
    protected $zone = RequestZone::BACKEND;
    protected $cacheDuration = -1800;

    protected $google_analytics_dimensions = 'ga:date';
    protected $google_analytics_metrics 		= 'ga:pageviews,ga:uniquePageviews,ga:avgTimeOnPage';
    protected $google_analytics_sort_by 		= 'ga:date';
    protected $google_analytics_max_results 	= '30';

    public function run(array $params = []): ?string
    {
        $data = $this->gatherData();

        if (empty($data)) {
            return null;
        }

        return parent::run(['data' => $data]);
    }


    private function gatherData()
    {
        $analytics = $this->initializeAnalytics();
        $profile = $this->getFirstProfileId($analytics);
        $results = $this->getResults($analytics, $profile);

        return $results;
    }


    private function initializeAnalytics(): Google_Service_Analytics
    {
        // Creates and returns the Analytics Reporting service object.

        // Use the developers console and download your service account
        // credentials in JSON format. Place them in this directory or
        // change the key file location if necessary.
        $KEY_FILE_LOCATION = __DIR__ . '/service-account-credentials.json';

        // Create and configure a new client object.
        $client = new Google_Client();
        $client->setApplicationName("Hello Analytics Reporting");
        $client->setAuthConfig($KEY_FILE_LOCATION);
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $analytics = new Google_Service_Analytics($client);

        return $analytics;
    }

    private function getFirstProfileId($analytics): string
    {
        // Get the user's first view (profile) ID.

        // Get the list of accounts for the authorized user.
        $accounts = $analytics->management_accounts->listManagementAccounts();

        if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

            // Get the list of properties for the authorized user.
            $properties = $analytics->management_webproperties
                ->listManagementWebproperties($firstAccountId);

            if (count($properties->getItems()) > 0) {
                $items = $properties->getItems();
                $firstPropertyId = $items[0]->getId();

                // Get the list of views (profiles) for the authorized user.
                $profiles = $analytics->management_profiles
                    ->listManagementProfiles($firstAccountId, $firstPropertyId);

                if (count($profiles->getItems()) > 0) {
                    $items = $profiles->getItems();

                    // Return the first view (profile) ID.
                    return $items[0]->getId();

                } else {
                    throw new Exception('No views (profiles) found for this user.');
                }
            } else {
                throw new Exception('No properties found for this user.');
            }
        } else {
            throw new Exception('No accounts found for this user.');
        }
    }

    function getResults($analytics, $profileId): Google_Service_Analytics_GaData
    {
        $params = [
            'dimensions' => $this->google_analytics_dimensions,
            'sort' => $this->google_analytics_sort_by,
            'filters' => 'ga:medium==organic',
            'max-results' => $this->google_analytics_max_results
        ];

        return $analytics->data_ga->get('ga:' . $profileId, '28daysAgo', 'today', $this->google_analytics_metrics, $params);
    }

}