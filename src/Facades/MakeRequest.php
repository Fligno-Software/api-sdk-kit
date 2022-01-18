<?php

namespace Fligno\ApiSdkKit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class MakeRequest
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 *
 * @see \Fligno\ApiSdkKit\Containers\MakeRequest
 */
class MakeRequest extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'make-request';
    }
}
