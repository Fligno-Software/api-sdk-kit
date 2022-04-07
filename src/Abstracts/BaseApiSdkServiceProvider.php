<?php

namespace Fligno\ApiSdkKit\Abstracts;

use Fligno\StarterKit\Providers\BaseStarterKitServiceProvider as ServiceProvider;

/**
 * Class BaseApiSdkServiceProvider
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
abstract class BaseApiSdkServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        parent::boot();
    }
}
