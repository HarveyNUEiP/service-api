<?php

namespace App\Services\Http;

use Exception;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Http;
use JsonException;

abstract class AbstractHttpRequest
{
    /**
     * The HTTP client instance.
     *
     * @var \Illuminate\Http\Client\PendingRequest
     */
    protected static $http;

    /**
     * Get the HTTP client instance.
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    public static function getHttp(): \Illuminate\Http\Client\PendingRequest
    {
        return self::$http ?? Http::hrm();
    }

    /**
     * Decode the body of the response.
     *
     * @param  \Psr\Http\Message\ResponseInterface  $response
     * @return mixed
     */
    public static function decodeBody($response)
    {
        try {
            return json_decode(
                json: $response->getBody(),
                associative: true,
                flags: JSON_THROW_ON_ERROR
            );
        } catch (JsonException $je) {
            return $response->getBody()->getContents();
        }
    }

    /**
     * Send a request to the server.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  array  $options
     * @return mixed
     */
    public static function send(string $method, string $uri, array $options = [])
    {
        try {
            $response = self::getHttp()->send($method, $uri, $options);
        } catch (ClientException $ce) {
            $response = $ce->getResponse();
        }

        $statusCode = $response->getStatusCode();

        // If the status code is not in the 2xx range,
        // it means that the error is on the server side.
        if ($statusCode < 200 || $statusCode > 300) {
            throw new Exception($response->getBody(), $statusCode);
        }

        return self::decodeBody($response);
    }
}
