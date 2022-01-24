<?php

namespace Fligno\ApiSdkKit\Containers;

use Fligno\StarterKit\Abstracts\BaseJsonSerializable;
use Fligno\ApiSdkKit\Traits\UsesHttpFieldsTrait;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use JsonException;
use RuntimeException;

/**
 * Class MakeRequest
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */

class MakeRequest
{
    use UsesHttpFieldsTrait;

    public const HEAD = 'head';
    public const GET = 'get';
    public const POST = 'post';
    public const PUT = 'put';
    public const DELETE = 'delete';

    public const AS_JSON = 'as_json';
    public const AS_FORM = 'as_form';

    /**
     * @var string|null
     */
    protected ?string $base_url;

    /**
     * @var string|null
     */
    protected ?string $append_url;

    /**
     * @var string|null
     */
    protected ?string $body_format;

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
     * @param string|null $body_format
     * @return PromiseInterface|Response
     */
    public function execute(string $method, string $append_url = '', string $body_format = null): PromiseInterface|Response
    {
        // Prepare URL

        if (! ($this->getBaseUrl())) {
            throw new RuntimeException('Base URL is empty.');
        }

        // Set Append URL

        $this->setAppendUrl($append_url);

        // Set Body Format

        $this->setBodyFormat($body_format);

        // Get Complete URL

        $url = $this->getCompleteUrl();

        // Prepare Data

        $data = self::normalizeToArray($this->getData());

        // Prepare Guzzle HTTP

        $response = $this->getHttp();

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
     * @param string|null $body_format
     * @return PromiseInterface|Response
     */
    public function executeHead(string $append_url = '', string $body_format = null): PromiseInterface|Response
    {
        return $this->execute(self::HEAD, $append_url, $body_format);
    }

    /**
     * @param string $append_url
     * @param string|null $body_format
     * @return PromiseInterface|Response
     */
    public function executeGet(string $append_url = '', string $body_format = null): PromiseInterface|Response
    {
        return $this->execute(self::GET, $append_url, $body_format);
    }

    /**
     * @param string $append_url
     * @param string|null $body_format
     * @return PromiseInterface|Response
     */
    public function executePost(string $append_url = '', string $body_format = null): PromiseInterface|Response
    {
        return $this->execute(self::POST, $append_url, $body_format);
    }

    /**
     * @param string $append_url
     * @param string|null $body_format
     * @return PromiseInterface|Response
     */
    public function executePut(string $append_url = '', string $body_format = null): PromiseInterface|Response
    {
        return $this->execute(self::PUT, $append_url, $body_format);
    }

    /**
     * @param string $append_url
     * @param string|null $body_format
     * @return PromiseInterface|Response
     */
    public function executeDelete(string $append_url = '', string $body_format = null): PromiseInterface|Response
    {
        return $this->execute(self::DELETE, $append_url, $body_format);
    }

    /**
     * @param BaseJsonSerializable|Collection|array $data
     * @return array
     */
    public static function normalizeToArray(BaseJsonSerializable|Collection|array $data): array
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
     * @return PendingRequest
     */
    public function getHttp(): PendingRequest
    {
        // Prepare Headers

        $headers = self::normalizeToArray($this->getHeaders());

        // Prepare HttpOptions

        $options = self::normalizeToArray($this->getHttpOptions());

        // Prepare HTTP call

        $response = Http::withHeaders($headers)->withOptions($options);

        // Prepare Body Format

        return match ($this->getBodyFormat()) {
            self::AS_FORM => $response->asForm(),
            self::AS_JSON => $response->asJson(),
            default => $response
        };
    }

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
     * @param string|null $body_format
     */
    public function setBodyFormat(?string $body_format): void
    {
        $body_format = Str::of($body_format)->snake()->match('/(' . self::AS_FORM . '|' . self::AS_JSON .  ')/')->jsonSerialize();

        $this->body_format = $body_format ?: null;
    }

    /**
     * @return string|null
     */
    public function getBodyFormat(): ?string
    {
        return $this->body_format;
    }

    /**
     * @return string
     */
    public function getCompleteUrl(): string
    {
        return $this->base_url . '/' . $this->append_url;
    }
}
