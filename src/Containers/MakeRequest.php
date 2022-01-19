<?php

namespace Fligno\ApiSdkKit\Containers;

use Fligno\ApiSdkKit\Abstracts\BaseJsonSerializable;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use JsonException;
use RuntimeException;

/**
 * Class MakeRequest
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */

class MakeRequest
{
    public const HEAD = 'head';
    public const GET = 'get';
    public const POST = 'post';
    public const PUT = 'put';
    public const DELETE = 'delete';

    /**
     * @var string|null
     */
    protected ?string $base_url;

    /**
     * @var string|null
     */
    protected ?string $append_url;

    /**
     * @var BaseJsonSerializable|Collection|array
     */
    protected BaseJsonSerializable|Collection|array $data = [];

    /***
     * @var BaseJsonSerializable|Collection|array
     */
    protected BaseJsonSerializable|Collection|array $headers = [];

    /**
     * @param string|null $base_url
     */
    public function __construct(?string $base_url = null)
    {
        $this->setBaseUrl($base_url);
    }

    /**
     * @param string $method
     * @param string $append_url
     * @param bool $format_as_json
     * @return PromiseInterface|Response
     */
    public function execute(string $method, string $append_url = '', bool $format_as_json = true): PromiseInterface|Response
    {
        // Prepare URL

        if (! ($this->base_url)) {
            throw new RuntimeException('Base URL is empty.');
        }

        $this->setAppendUrl($append_url);

        $url = $this->getCompleteUrl();

        // Prepare Data

        $data = $this->normalizeToArray($this->getData());

        // Prepare Headers

        $headers = $this->normalizeToArray($this->getHeaders());

        // Prepare HTTP call

        $response = Http::withHeaders($headers);

        // Prepare Body Format

        $response = $format_as_json ? $response->asJson() : $response->asForm();

        // Prepare SSL Verify

        if (! config('terrapay-sdk.verify_ssl')) { // Note: Read https://docs.guzzlephp.org/en/stable/request-options.html#verify
            $response = $response->withoutVerifying();
        }

        // Initiate HTTP call

        return match ($method) {
            self::GET => $response->get($url, $data),
            self::POST => $response->post($url, $data),
            self::PUT => $response->put($url, $data),
            self::DELETE => $response->delete($url, $data),
            self::HEAD => $response->head($url, $data),
            default => throw new RuntimeException('HTTP method not found.')
        };
    }

    /**
     * @param string $append_url
     * @param bool $format_as_json
     * @return PromiseInterface|Response
     */
    public function executeHead(string $append_url = '', bool $format_as_json = true): PromiseInterface|Response
    {
        return $this->execute(self::HEAD, $append_url, $format_as_json);
    }

    /**
     * @param string $append_url
     * @param bool $format_as_json
     * @return PromiseInterface|Response
     */
    public function executeGet(string $append_url = '', bool $format_as_json = true): PromiseInterface|Response
    {
        return $this->execute(self::GET, $append_url, $format_as_json);
    }

    /**
     * @param string $append_url
     * @param bool $format_as_json
     * @return PromiseInterface|Response
     */
    public function executePost(string $append_url = '', bool $format_as_json = true): PromiseInterface|Response
    {
        return $this->execute(self::POST, $append_url, $format_as_json);
    }

    /**
     * @param string $append_url
     * @param bool $format_as_json
     * @return PromiseInterface|Response
     */
    public function executePut(string $append_url = '', bool $format_as_json = true): PromiseInterface|Response
    {
        return $this->execute(self::PUT, $append_url, $format_as_json);
    }

    /**
     * @param string $append_url
     * @param bool $format_as_json
     * @return PromiseInterface|Response
     */
    public function executeDelete(string $append_url = '', bool $format_as_json = true): PromiseInterface|Response
    {
        return $this->execute(self::DELETE, $append_url, $format_as_json);
    }

    /**
     * @param BaseJsonSerializable|Collection|array $data
     * @return array
     */
    public function normalizeToArray(BaseJsonSerializable|Collection|array $data): array
    {
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }
        else if ($data instanceof BaseJsonSerializable) {
            try {
                $data = json_decode(json_encode($data, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
            }
            catch (JsonException $e) {
                throw new \RuntimeException('Failed to convert object to array.');
            }
        }

        return array_filter_recursive($data);
    }

    /***** SETTERS & GETTERS *****/

    /**
     * @param string|null $base_url
     * @return MakeRequest
     */
    public function setBaseUrl(?string $base_url): static
    {
        $this->base_url = rtrim(trim($base_url), '/');

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBaseUrl(): ?string
    {
        return $this->base_url;
    }

    /**
     * @param string|null $append_url
     * @return MakeRequest
     */
    public function setAppendUrl(?string $append_url): static
    {
        $this->append_url = ltrim(trim($append_url), '/');

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAppendUrl(): ?string
    {
        return $this->append_url;
    }

    /**
     * @return string
     */
    public function getCompleteUrl(): string
    {
        return $this->base_url . '/' . $this->append_url;
    }

    /**
     * @return array|BaseJsonSerializable|Collection
     */
    public function getData(): BaseJsonSerializable|array|Collection
    {
        return $this->data;
    }

    /**
     * @param BaseJsonSerializable|array|Collection|null $data
     * @return MakeRequest
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
     * @return MakeRequest
     */
    public function setHeaders(BaseJsonSerializable|array|Collection $headers): static
    {
        $this->headers = $headers;

        return $this;
    }
}
