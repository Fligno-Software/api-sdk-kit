<?php

namespace Fligno\ApiSdkKit\Abstracts;

use Fligno\ApiSdkKit\Containers\MakeRequest;
use Fligno\ApiSdkKit\Interfaces\CanGetHealthCheckInterface;

/**
 * Class BaseApiSdkContainer
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
abstract class BaseApiSdkContainer
{
    /**
     * @return bool
     */
    public function canGetHealthCheck(): bool
    {
        return $this instanceof CanGetHealthCheckInterface;
    }

    /***** GETTERS & SETTERS *****/

    /**
     * @return string
     */
    abstract public function getBaseUrl(): string;

    /**
     * @return MakeRequest
     */
    public function getMakeRequest(): MakeRequest
    {
        return makeRequest(rtrim(trim($this->getBaseUrl()), '/'));
    }
}
