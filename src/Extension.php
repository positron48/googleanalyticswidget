<?php


namespace App\GoogleAnalytics;


use AcmeCorp\ReferenceExtension\ReferenceWidget;
use AcmeCorp\ReferenceExtension\Twig;
use Bolt\Extension\BaseExtension;

class Extension extends BaseExtension
{
    /**
     * Return the full name of the extension
     */
    public function getName(): string
    {
        return 'Google Analytics';
    }

    public function initialize(): void
    {
        $this->registerWidget(new AnalyticsWidget());
    }
}