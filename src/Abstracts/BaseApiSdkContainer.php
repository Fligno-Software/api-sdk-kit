<?php

namespace Fligno\ApiSdkKit\Abstracts;

use Fligno\ApiSdkKit\Interfaces\CanHealthCheckInterface;

/**
 * Class BaseApiSdkContainer
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
abstract class BaseApiSdkContainer
{
    /**
     * @var string|null
     */
    protected ?string $base_url = null;

    /**
     * @return bool
     */
    public function canHealthCheck(): bool
    {
        return $this instanceof CanHealthCheckInterface;
    }

    /***** GETTERS & SETTERS *****/

    /**
     * @return string|null
     */
    public function getBaseUrl(): ?string
    {
        return $this->base_url;
    }

    /**
     * @param string|null $base_url
     */
    public function setBaseUrl(?string $base_url): void
    {
        $this->base_url = $base_url;
    }
}
