<?php

namespace Fligno\ApiSdkKit\Abstracts;

use Fligno\ApiSdkKit\Containers\MakeRequest;
use Fligno\ApiSdkKit\Interfaces\CanGetHealthCheckInterface;
use Fligno\ApiSdkKit\Interfaces\CanGetNewApiKeysInterface;
use Fligno\ApiSdkKit\Traits\UsesHttpFieldsTrait;

/**
 * Class BaseApiSdkContainer
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
abstract class BaseApiSdkContainer
{
    use UsesHttpFieldsTrait;

    /**
     * @return bool
     */
    public function canGetHealthCheck(): bool
    {
        return $this instanceof CanGetHealthCheckInterface || method_exists($this, 'getHealthCheck');
    }

    /**
     * @return bool
     */
    public function canGetNewApiKeys(): bool
    {
        return $this instanceof CanGetNewApiKeysInterface || method_exists($this, 'getNewApiKeys');
    }

    /*****
     * GETTERS & SETTERS
     *****/

    /**
     * @return string
     */
    abstract public function getBaseUrl(): string;

    /**
     * @return MakeRequest
     */
    public function getMakeRequest(): MakeRequest
    {
        return makeRequest(rtrim(trim($this->getBaseUrl()), '/'))
            ->httpOptions($this->getHttpOptions())
            ->headers($this->getHeaders());
    }
}
