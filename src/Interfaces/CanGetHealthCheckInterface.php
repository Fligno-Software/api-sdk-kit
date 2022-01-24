<?php

namespace Fligno\ApiSdkKit\Interfaces;

use Fligno\StarterKit\Abstracts\BaseJsonSerializable;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

/**
 * Interface CanHealthCheckInterface
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
interface CanGetHealthCheckInterface
{
    /**
     * @param BaseJsonSerializable|Collection|array|null $data
     * @return BaseJsonSerializable|PromiseInterface|Response
     */
    public function getHealthCheck(BaseJsonSerializable|Collection|array $data = null): BaseJsonSerializable|PromiseInterface|Response;
}
