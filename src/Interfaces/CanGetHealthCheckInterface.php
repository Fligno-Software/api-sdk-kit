<?php

namespace Fligno\ApiSdkKit\Interfaces;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;

/**
 * Interface CanHealthCheckInterface
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
interface CanGetHealthCheckInterface
{
    /**
     * @return PromiseInterface|Response
     */
    public function getHealthCheck(): PromiseInterface|Response;
}
