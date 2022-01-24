<?php

namespace Fligno\ApiSdkKit\Traits;

use Fligno\StarterKit\Abstracts\BaseJsonSerializable;
use Illuminate\Support\Collection;

/**
 * Trait UsesHttpFieldsTrait
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
trait UsesHttpFieldsTrait
{
    /**
     * @var BaseJsonSerializable|Collection|array
     */
    protected BaseJsonSerializable|Collection|array $data = [];

    /***
     * @var BaseJsonSerializable|Collection|array
     */
    protected BaseJsonSerializable|Collection|array $headers = [];

    /**
     * @var BaseJsonSerializable|Collection|array
     */
    protected BaseJsonSerializable|Collection|array $httpOptions = [];

    /***** SETTERS & GETTERS *****/

    /**
     * @return array|BaseJsonSerializable|Collection
     */
    public function getData(): BaseJsonSerializable|array|Collection
    {
        return $this->data;
    }

    /**
     * @param BaseJsonSerializable|array|Collection|null $data
     * @return static
     */
    public function setData(BaseJsonSerializable|array|Collection|null $data): static
    {
        if ($data) {
            $this->data = $data;
        }

        return $this;
    }

    /**
     * @return array|BaseJsonSerializable|Collection
     */
    public function getHeaders(): BaseJsonSerializable|array|Collection
    {
        return $this->headers;
    }

    /**
     * @param array|BaseJsonSerializable|Collection $headers
     * @return static
     */
    public function setHeaders(BaseJsonSerializable|array|Collection $headers): static
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return array|BaseJsonSerializable|Collection
     */
    public function getHttpOptions(): BaseJsonSerializable|array|Collection
    {
        return $this->httpOptions;
    }

    /**
     * @param array|BaseJsonSerializable|Collection $httpOptions
     * @return static
     */
    public function setHttpOptions(BaseJsonSerializable|array|Collection $httpOptions): static
    {
        $this->httpOptions = $httpOptions;

        return $this;
    }
}
