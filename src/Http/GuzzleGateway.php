<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\ErrorResponseException;
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

        // Authentication with myDATA failed, or the user is not authorized for the
        // requested resource. Keep the reason myDATA gives so the two can be told apart.
        $body = $this->body($exception);

        if ($exception->getCode() === 401) {
            throw new MyDataAuthenticationException($exception->getCode(), $exception, $this->reason($body));
        }

        // Rate limit exception
        if ($exception->getCode() === 429) {
            throw new RateLimitExceededException($exception->getMessage(), $exception->getCode(), $exception);
        }

        // myDATA can also answer a bad request with an error document (e.g. HTTP 400).
        // The HTTP status stays the exception code, as it was before the errors were read.
        throw ErrorResponseException::fromXml($body, $exception->getCode(), $exception)
            ?? new TransmissionFailedException($body ?: $exception->getMessage(), $exception->getCode(), $exception);
    }

    /**
     * The body of a failed response, read from the start even if it has been read before.
     */
    private function body(GuzzleException $exception): string
    {
        return $exception instanceof RequestException ? trim((string) $exception->getResponse()?->getBody()) : '';
    }

    /**
     * The error message of a failed response: plain text from myDATA, the "message"
     * of the JSON error returned by the API gateway, or any other JSON as it is.
     * Markup (e.g. an HTML error page from a proxy) is not a usable reason.
     */
    private function reason(string $body): ?string
    {
        $json = json_decode($body, true);
        if (is_array($json) && is_string($json['message'] ?? null)) {
            $body = trim($json['message']);
        } elseif (is_string($json)) {
            $body = trim($json);
        }

        if ($body === '' || str_starts_with($body, '<')) {
            return null;
        }

        // Keep the exception message readable: valid UTF-8 (mb_substr only replaces
        // invalid bytes itself since PHP 8.3) and capped in length
        $body = mb_scrub($body, 'UTF-8');

        return mb_strlen($body) > 500 ? mb_substr($body, 0, 500) . '…' : $body;
    }
}
