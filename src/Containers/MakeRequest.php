<?php

namespace Fligno\ApiSdkKit\Containers;

use Closure;
use Fligno\ApiSdkKit\DataFactories\AuditLogDataFactory;
use Fligno\ApiSdkKit\Models\AuditLog;
use Fligno\StarterKit\Abstracts\BaseJsonSerializable;
use Fligno\ApiSdkKit\Traits\UsesHttpFieldsTrait;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use ReflectionException;
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
    public const PATCH = 'patch';
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
     * @var array
     */
    protected array $pre_request_processes;

    /**
     * @param string|null $base_url
     */
    public function __construct(?string $base_url = null)
    {
        $this->setBaseUrl($base_url);

        $this->pre_request_processes = [
            function (self $request) {
                $request->httpOptions(['verify' => (bool) config('starter-kit.verify_ssl')]);
            }
        ];
    }

    /**
     * @param string $method
     * @param string|null $append_url
     * @param string|null $body_format
     * @param bool $return_as_model
     * @return PromiseInterface|Response|Builder|AuditLog
     * @throws ReflectionException
     */
    public function execute(
        string $method,
        ?string $append_url = '',
        string $body_format = null,
        bool $return_as_model = true
    ): PromiseInterface|Response|Builder|AuditLog {
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

        // Prepare Guzzle HTTP

        $response = $this->getHttp();

        // Initiate HTTP call

        if (! in_array($method, [self::GET, self::POST, self::PUT, self::PATCH, self::DELETE, self::HEAD])) {
            throw new RuntimeException('HTTP method not available.');
        }

        $result = $response->$method($url, $this->data);

        if ($return_as_model) {
            $log = new AuditLogDataFactory;
            $log->url = $url;
            $log->method = $method;
            $log->status = $result->status();
            $log->data = $result->collect();
            $log->headers = $result->headers();
            return $log->create();
        }

        return $result;
    }

    /**
     * @param string|null $append_url
     * @param string|null $body_format
     * @param bool $return_as_model
     * @return PromiseInterface|Response|Builder|AuditLog
     * @throws ReflectionException
     */
    public function executeHead(
        ?string $append_url = '',
        string $body_format = null,
        bool $return_as_model = true
    ): PromiseInterface|Response|Builder|AuditLog {
        return $this->execute(self::HEAD, $append_url, $body_format, $return_as_model);
    }

    /**
     * @param string|null $append_url
     * @param string|null $body_format
     * @param bool $return_as_model
     * @return PromiseInterface|Response|Builder|AuditLog
     * @throws ReflectionException
     */
    public function executeGet(
        ?string $append_url = '',
        string $body_format = null,
        bool $return_as_model = true
    ): PromiseInterface|Response|Builder|AuditLog {
        return $this->execute(self::GET, $append_url, $body_format, $return_as_model);
    }

    /**
     * @param string|null $append_url
     * @param string|null $body_format
     * @param bool $return_as_model
     * @return PromiseInterface|Response|Builder|AuditLog
     * @throws ReflectionException
     */
    public function executePost(
        ?string $append_url = '',
        string $body_format = null,
        bool $return_as_model = true
    ): PromiseInterface|Response|Builder|AuditLog {
        return $this->execute(self::POST, $append_url, $body_format, $return_as_model);
    }

    /**
     * @param string|null $append_url
     * @param string|null $body_format
     * @param bool $return_as_model
     * @return PromiseInterface|Response|Builder|AuditLog
     * @throws ReflectionException
     */
    public function executePut(
        ?string $append_url = '',
        string $body_format = null,
        bool $return_as_model = true
    ): PromiseInterface|Response|Builder|AuditLog {
        return $this->execute(self::PUT, $append_url, $body_format, $return_as_model);
    }

    /**
     * @param string|null $append_url
     * @param string|null $body_format
     * @param bool $return_as_model
     * @return PromiseInterface|Response|Builder|AuditLog
     * @throws ReflectionException
     */
    public function executeDelete(
        ?string $append_url = '',
        string $body_format = null,
        bool $return_as_model = true
    ): PromiseInterface|Response|Builder|AuditLog {
        return $this->execute(self::DELETE, $append_url, $body_format, $return_as_model);
    }

    /*****
     * SETTERS & GETTERS
     *****/

    /**
     * @return PendingRequest
     */
    public function getHttp(): PendingRequest
    {
        $this->getPreRequestProcesses()->each(fn($closure) => $closure($this));

        // Prepare HTTP call

        $response = Http::withUserAgent(config('api-sdk-kit.user_agent'))
            ->withHeaders($this->headers)
            ->withOptions($this->httpOptions);

        // Prepare Body Format

        return match ($this->getBodyFormat()) {
            self::AS_FORM => $response->asForm(),
            self::AS_JSON => $response->asJson(),
            default => $response
        };
    }

    /**
     * @param  string|null $base_url
     * @return static
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
     * @param  string|null $append_url
     * @return static
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
        $body_format = Str::of($body_format)->snake()->match('/(' . self::AS_FORM . '|' . self::AS_JSON .  ')/')
            ->jsonSerialize();

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

    /**
     * @param  Closure|Closure[] $closure
     * @return static
     */
    public function addToPreRequestProcesses(Closure|array $closure): static
    {
        $this->pre_request_processes = $this->getPreRequestProcesses()->merge(collect($closure))->toArray();

        return $this;
    }

    /**
     * @return Collection
     */
    public function getPreRequestProcesses(): Collection
    {
        return collect($this->pre_request_processes);
    }

    /*****
     * OTHER FUNCTIONS
     *****/

    /**
     * @param string $url
     * @return static
     */
    public function proxy(string $url): static
    {
        $closure = function (self $request) use ($url) {
            $request->httpOptions(['proxy' => $url]);
        };

        $this->addToPreRequestProcesses($closure);

        return $this;
    }
}
