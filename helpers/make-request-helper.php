<?php

/**
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */

use Fligno\ApiSdkKit\Containers\MakeRequest;

if (! function_exists('makeRequest')) {
    /**
     * @param  string|null $base_url
     * @return MakeRequest
     */
    function makeRequest(?string $base_url): MakeRequest
    {
        return resolve(
            'make-request',
            [
                'base_url' => $base_url
            ]
        );
    }
}

if (! function_exists('make_request')) {
    /**
     * @param  string|null $base_url
     * @return MakeRequest
     */
    function make_request(?string $base_url): MakeRequest
    {
        return makeRequest($base_url);
    }
}
