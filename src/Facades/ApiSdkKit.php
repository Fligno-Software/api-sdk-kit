<?php

namespace Fligno\ApiSdkKit\Facades;

use Illuminate\Support\Facades\Facade;

class ApiSdkKit extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'api-sdk-kit';
    }
}
