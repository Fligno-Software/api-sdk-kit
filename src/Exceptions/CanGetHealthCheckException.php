<?php

namespace Fligno\ApiSdkKit\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class CanGetHealthCheckException
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class CanGetHealthCheckException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function render(Request $request): JsonResponse
    {
        return customResponse()
            ->code(config('api-sdk-kit.get_health_check_exception.code'))
            ->message(config('api-sdk-kit.get_health_check_exception.message'))
            ->generate();
    }
}
