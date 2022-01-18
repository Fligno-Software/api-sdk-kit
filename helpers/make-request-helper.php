<?php

/**
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */

use Fligno\ApiSdkKit\Containers\MakeRequest;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

if (! function_exists('makeRequest'))
{
    /**
     * @param string|null $base_url
     * @param Http|null $http
     * @return MakeRequest
     */
    function makeRequest(?string $base_url, ?Http $http= null): MakeRequest
    {
        return resolve('make-request', [
            'base_url' => $base_url,
            'http' => $http
        ]);
    }
}

if (! function_exists('make_request'))
{
    /**
     * @param string|null $base_url
     * @param Http|null $http
     * @return MakeRequest
     */
    function make_request(?string $base_url, ?Http $http = null): MakeRequest
    {
        return makeRequest($base_url, $http);
    }
}

if (! function_exists('make_get_request'))
{
    /**
     * @param string|null $base_url
     * @param string $append_url
     * @param array $data
     * @param array $headers
     * @param bool $as_json
     * @param Http|null $http
     * @return PromiseInterface|Response
     */
    function make_get_request(?string $base_url, string $append_url = '', array $data = [], array $headers = [], bool $as_json = true, ?Http $http = null): PromiseInterface|Response
    {
        return makeRequest($base_url, $http)->execute(MakeRequest::GET, $append_url, $data, $headers, $as_json);
    }
}
