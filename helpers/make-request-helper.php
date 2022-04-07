<?php

/**
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */

use Fligno\StarterKit\Abstracts\BaseJsonSerializable;
use Fligno\ApiSdkKit\Containers\MakeRequest;
use Illuminate\Support\Collection;

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

if (! function_exists('normalizeToArray')) {
    /**
     * @param  BaseJsonSerializable|Collection|array $data
     * @return array
     */
    function normalizeToArray(BaseJsonSerializable|Collection|array $data): array
    {
        return MakeRequest::normalizeToArray($data);
    }
}

if (! function_exists('normalize_to_array')) {
    /**
     * @param  BaseJsonSerializable|Collection|array $data
     * @return array
     */
    function normalize_to_array(BaseJsonSerializable|Collection|array $data): array
    {
        return normalizeToArray($data);
    }
}
