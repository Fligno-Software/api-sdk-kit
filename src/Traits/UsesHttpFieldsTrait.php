<?php

namespace Fligno\ApiSdkKit\Traits;

use Fligno\StarterKit\Traits\UsesDataParsingTrait;
use Illuminate\Support\Collection;

/**
 * Trait UsesHttpFieldsTrait
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
trait UsesHttpFieldsTrait
{
    use UsesDataParsingTrait;

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
     * @param mixed $data
     * @return void
     */
    public function setData(mixed $data): void
    {
        $this->data = $this->parse($data);
    }

    /**
     * @param mixed $data
     * @param bool $merge If true, the new data will be merged to the old data. If false, the new data will replace the old data.
     * @param bool $override If true, common keys from new data will replace the old one's. If false, common keys will be ignored.
     * @return static
     */
    public function data(mixed $data, bool $merge = true, bool $override = true): static
    {
        $data = $this->parse($data);

        if (! $merge) {
            $this->data = [];
        } elseif (! $override) {
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
     * @param  mixed $headers
     * @return void
     */
    public function setHeaders(mixed $headers): void
    {
        $this->headers = $this->parse($headers);
    }

    /**
     * @param mixed $headers
     * @param bool $merge If true, the new headers will be merged to the old headers. If false, the new headers will replace the old headers.
     * @param bool $override If true, common keys from new headers will replace the old one's. If false, common keys will be ignored.
     * @return static
     */
    public function headers(mixed $headers, bool $merge = true, bool $override = true): static
    {
        $headers = $this->parse($headers);

        if (! $merge) {
            $this->headers = [];
        } elseif (! $override) {
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
     * @param mixed $httpOptions
     * @return void
     */
    public function setHttpOptions(mixed $httpOptions): void
    {
        $this->httpOptions = $this->parse($httpOptions);
    }

    /**
     * @param mixed $httpOptions
     * @param bool $merge If true, the new http options will be merged to the old http options. If false, the new http options will replace the old http options.
     * @param bool $override If true, common keys from new http options will replace the old one's. If false, common keys will be ignored.
     * @return static
     */
    public function httpOptions(mixed $httpOptions, bool $merge = true, bool $override = true): static
    {
        $httpOptions = $this->parse($httpOptions);

        if (! $merge) {
            $this->httpOptions = [];
        } elseif (! $override) {
            $httpOptions = collect($httpOptions)->except(array_keys($this->httpOptions));
        }

        $this->httpOptions = $this->getHttpOptions()->merge($httpOptions)->toArray();

        return $this;
    }
}
