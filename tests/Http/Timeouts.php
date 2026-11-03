<?php

declare(strict_types=1);

namespace Tests\Http;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ConnectTimeoutException;
use GuzzleHttp\Exception\NetworkTimeoutException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ResponseTimeoutException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * The timeouts Guzzle reports, built for the installed major: Guzzle 8 has an
 * exception class per stage, Guzzle 7 puts cURL error 28 in the handler context.
 */
final class Timeouts
{
    public static function typed(): bool
    {
        return class_exists(NetworkTimeoutException::class);
    }

    /**
     * Before the response headers: the request may have been processed.
     */
    public static function beforeHeaders(): Throwable
    {
        $request = new Request('GET', 'test');

        return self::typed()
            ? new NetworkTimeoutException('Timed out', $request)
            : new ConnectException('Timed out', $request, null, ['errno' => 28]);
    }

    /**
     * After the response headers: the request was processed, the body did not arrive in time.
     */
    public static function afterHeaders(ResponseInterface $response): Throwable
    {
        $request = new Request('GET', 'test');

        return self::typed()
            ? new ResponseTimeoutException('Timed out', $request, $response)
            : new RequestException('Timed out', $request, $response, null, ['errno' => 28]);
    }

    /**
     * Guzzle 8 only: the connection was never made. Guzzle 7 reports it like any other timeout.
     */
    public static function connect(): Throwable
    {
        return new ConnectTimeoutException('Connection timed out', new Request('GET', 'test'));
    }
}
