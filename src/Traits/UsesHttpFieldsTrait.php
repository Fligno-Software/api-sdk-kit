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
     * @var array
     */
    protected array $data = [];

    /***
     * @var array
     */
    protected array $headers = [];

    /**
     * @var array
     */
    protected array $httpOptions = [];

    /*****
     * SETTERS & GETTERS
     *****/

    /**
     * @return Collection
     */
    public function getData(): Collection
    {
        return collect($this->data);
    }

    /**
     * @param  BaseJsonSerializable|array|Collection $data
     * @return void
     */
    public function setData(BaseJsonSerializable|array|Collection $data): void
    {
        $this->data = self::normalizeToArray($data);
    }

    /**
     * @param BaseJsonSerializable|array|Collection $data
     * @param bool $merge If true, the new data will be merged to the old data. If false, the new data will replace the old data.
     * @param bool $override If true, common keys from new data will replace the old one's. If false, common keys will be ignored.
     * @return static
     */
    public function data(BaseJsonSerializable|array|Collection $data, bool $merge = true, bool $override = true): static
    {
        $data = self::normalizeToArray($data);

        if (! $merge) {
            $this->data = [];
        }
        else if (! $override) {
            $data = collect($data)->except(array_keys($this->data));
        }

        $this->data = $this->getData()->merge($data)->toArray();

        return $this;
    }

    /**
     * @return Collection
     */
    public function getHeaders(): Collection
    {
        return collect($this->headers);
    }

    /**
     * @param  array|BaseJsonSerializable|Collection $headers
     * @return void
     */
    public function setHeaders(BaseJsonSerializable|array|Collection $headers): void
    {
        $this->headers = self::normalizeToArray($headers);
    }

    /**
     * @param array|BaseJsonSerializable|Collection $headers
     * @param bool $merge If true, the new headers will be merged to the old headers. If false, the new headers will replace the old headers.
     * @param bool $override If true, common keys from new headers will replace the old one's. If false, common keys will be ignored.
     * @return static
     */
    public function headers(BaseJsonSerializable|array|Collection $headers, bool $merge = true, bool $override = true): static
    {
        $headers = self::normalizeToArray($headers);

        if (! $merge) {
            $this->headers = [];
        }
        else if (! $override) {
            $headers = collect($headers)->except(array_keys($this->headers));
        }

        $this->headers = $this->getHeaders()->merge($headers)->toArray();

        return $this;
    }

    /**
     * @return Collection
     */
    public function getHttpOptions(): Collection
    {
        return collect($this->httpOptions);
    }

    /**
     * @param array|BaseJsonSerializable|Collection $httpOptions
     * @return void
     */
    public function setHttpOptions(BaseJsonSerializable|array|Collection $httpOptions): void
    {
        $this->httpOptions = self::normalizeToArray($httpOptions);
    }

    /**
     * @param array|BaseJsonSerializable|Collection $httpOptions
     * @param bool $merge If true, the new http options will be merged to the old http options. If false, the new http options will replace the old http options.
     * @param bool $override If true, common keys from new http options will replace the old one's. If false, common keys will be ignored.
     * @return static
     */
    public function httpOptions(BaseJsonSerializable|array|Collection $httpOptions, bool $merge = true, bool $override = true): static
    {
        $httpOptions = self::normalizeToArray($httpOptions);

        if (! $merge) {
            $this->httpOptions = [];
        }
        else if (! $override) {
            $httpOptions = collect($httpOptions)->except(array_keys($this->httpOptions));
        }

        $this->httpOptions = $this->getHttpOptions()->merge($httpOptions)->toArray();

        return $this;
    }

    /**
     * @param  BaseJsonSerializable|Collection|array $data
     * @return array
     */
    public static function normalizeToArray(BaseJsonSerializable|Collection|array $data): array
    {
        if ($data instanceof Collection || $data instanceof BaseJsonSerializable) {
            return $data->toArray();
        }

        return $data;
    }
}
