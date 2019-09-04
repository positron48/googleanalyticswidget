<?php

declare(strict_types=1);

namespace App\GoogleAnalytics;

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
