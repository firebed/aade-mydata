<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\MyDataAuthenticationException;
use Firebed\AadeMyData\Exceptions\MyDataConnectionException;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Exceptions\MyDataTimeoutException;
use Firebed\AadeMyData\Exceptions\RateLimitExceededException;
use Firebed\AadeMyData\Exceptions\TransmissionFailedException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

/**
 * Default gateway: sends requests to the AADE myDATA REST API using Guzzle.
 */
class GuzzleGateway implements Gateway
{
    /**
     * @throws MyDataAuthenticationException|MyDataException
     */
    public function get(MyDataRequest $request, array $query): string
    {
        return $this->send('GET', $request, ['query' => $query]);
    }

    /**
     * @throws MyDataAuthenticationException|MyDataException
     */
    public function post(MyDataRequest $request, ?array $query = null, ?string $body = null): string
    {
        return $this->send('POST', $request, array_filter(['query' => $query, 'body' => $body]));
    }

    /**
     * @throws MyDataAuthenticationException|MyDataException
     */
    protected function send(string $method, MyDataRequest $request, array $options): string
    {
        $this->validateCredentials();

        try {
            $response = MyDataRequest::httpClient()->request($method, $request->getUrl(), $options);

            return $response->getBody()->getContents();
        } catch (GuzzleException $e) {
            $this->handleTransmissionException($e);
        }
    }

    /**
     * @throws MyDataAuthenticationException
     */
    protected function validateCredentials(): void
    {
        if (! MyDataRequest::hasCredentials()) {
            throw new MyDataAuthenticationException(401);
        }
    }

    /**
     * Authorization errors, bad request, communication errors,
     * myDATA server errors, rate limits, connection timeout, etc.
     *
     * @throws MyDataAuthenticationException|MyDataException
     */
    protected function handleTransmissionException(GuzzleException $exception): never
    {
        // Specific case for timeout exception (HTTP 28 for cURL)
        // Connection with myDATA was established, but the response took too long
        if ($exception instanceof RequestException) {
            $errorNo = $exception->getHandlerContext()['errno'] ?? null;
            if ($errorNo === 28) {
                throw new MyDataTimeoutException(previous: $exception);
            }
        }

        // In case the endpoint url is wrong or the connection timed out, myDATA is unreachable
        if ($exception->getCode() === 0) {
            throw new MyDataConnectionException($exception->getCode(), $exception);
        }

        // Authentication with myDATA failed
        if ($exception->getCode() === 401) {
            throw new MyDataAuthenticationException($exception->getCode(), $exception);
        }

        // Rate limit exception
        if ($exception->getCode() === 429) {
            throw new RateLimitExceededException($exception->getMessage(), $exception->getCode(), $exception);
        }

        $message = $exception->getResponse()?->getBody()->getContents() ?: $exception->getMessage();
        throw new TransmissionFailedException($message, $exception->getCode(), $exception);
    }
}
