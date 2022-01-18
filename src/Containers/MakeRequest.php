<?php

namespace Fligno\ApiSdkKit\Containers;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use JetBrains\PhpStorm\Pure;

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
     * @var Http
     */
    protected Http $http;

    /**
     * @param string|null $base_url
     * @param Http|null $http
     */
    #[Pure]
    public function __construct(protected ?string $base_url = null, ?Http $http = null)
    {
        $this->http = $http ?? new Http();
    }

    /**
     * @param string $method
     * @param string $append_url
     * @param array $data
     * @param array $headers
     * @param bool $as_json
     * @return PromiseInterface|Response
     */
    public function execute(string $method, string $append_url = '', array $data = [], array $headers = [], bool $as_json = true): PromiseInterface|Response
    {
        // Prepare URL

        if (! ($url = $this->base_url)) {
            throw new \RuntimeException('Base URL is empty');
        }

        if($append_url = trim($append_url)) {
            $url .= '/' . $append_url;
        }

        // Prepare Data

        $data = array_filter_recursive($data);

        // Prepare Headers

        $headers = array_filter_recursive($headers);

        // Prepare HTTP call

        $response = $this->http::withHeaders($headers);

        // Prepare Body Format

        $response = $as_json ? $response->asJson() : $response->asForm();

        // Prepare SSL Verify

        if (! config('terrapay-sdk.verify_ssl')) { // Note: Read https://docs.guzzlephp.org/en/stable/request-options.html#verify
            $response = $response->withoutVerifying();
        }

        // Initiate HTTP call

        return match ($method) {
            self::HEAD => $response->head($url, $data),
            self::GET => $response->get($url, $data),
            self::POST => $response->post($url, $data),
            self::PUT => $response->put($url, $data),
            self::DELETE => $response->delete($url, $data)
        };
    }

    /***** SETTERS & GETTERS *****/

    /**
     * @param string|null $base_url
     * @return MakeRequest
     */
    public function setBaseUrl(?string $base_url): static
    {
        $this->base_url = $base_url;

        return $this;
    }

    /**
     * @return Http
     */
    public function getHttp(): Http
    {
        return $this->http;
    }
}
