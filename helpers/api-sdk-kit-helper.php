<?php

/**
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */

use Fligno\ApiSdkKit\ApiSdkKit;

if (! function_exists('apiSdkKit'))
{
    /**
     * @return ApiSdkKit
     */
    function apiSdkKit(): ApiSdkKit
    {
        return resolve('api-sdk-kit');
    }
}

if (! function_exists('api_sdk_kit'))
{
    /**
     * @return ApiSdkKit
     */
    function api_sdk_kit(): ApiSdkKit
    {
        return apiSdkKit();
    }
}
