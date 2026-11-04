<?php

declare(strict_types=1);

namespace Tests\Http;

use Firebed\AadeMyData\Exceptions\MyDataConnectionException;
use Firebed\AadeMyData\Exceptions\MyDataTimeoutException;
use Firebed\AadeMyData\Exceptions\RateLimitExceededException;
use Firebed\AadeMyData\Exceptions\TransmissionFailedException;
use Firebed\AadeMyData\Http\Connection;
use Firebed\AadeMyData\Http\GuzzleGateway;
use Firebed\AadeMyData\Http\RequestVatInfo;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response as HttpResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Traits\UsesStubs;

class RetryTest extends TestCase
{
    use UsesStubs;

    private SleeplessGateway $gateway;

    protected function setUp(): void
    {
        $this->gateway = new SleeplessGateway;
    }

    /**
     * @param  list<HttpResponse|\Throwable>  $responses
     */
    private function connection(array $responses, int $retries = 3, int $delay = 100): Connection
    {
        return Connection::make('u', 'k', 'dev')
            ->withHandler(HandlerStack::create(new MockHandler($responses)))
            ->withGateway($this->gateway)
            ->withRetries($retries, $delay);
    }

    public function test_throttling_and_gateway_errors_are_retried_with_backoff(): void
    {
        $connection = $this->connection([
            new HttpResponse(429),
            new HttpResponse(503),
            new HttpResponse(502),
            new HttpResponse(200, body: $this->getStub('request-vat-info-response')),
        ]);

        $result = (new RequestVatInfo)->using($connection)->handle('01/01/2026', '31/01/2026');

        $this->assertNotNull($result->getVatInfo());
        $this->assertSame([100, 200, 400], $this->gateway->sleeps);
    }

    public function test_retry_after_header_sets_the_delay(): void
    {
        $connection = $this->connection([
            new HttpResponse(429, ['Retry-After' => '2']),
            new HttpResponse(200, body: $this->getStub('request-vat-info-response')),
        ]);

        (new RequestVatInfo)->using($connection)->handle('01/01/2026', '31/01/2026');

        $this->assertSame([2000], $this->gateway->sleeps);
    }

    public function test_the_backoff_delay_is_capped(): void
    {
        $connection = $this->connection([
            new HttpResponse(503),
            new HttpResponse(503),
            new HttpResponse(200, body: $this->getStub('request-vat-info-response')),
        ], retries: 5, delay: 8_000);

        (new RequestVatInfo)->using($connection)->handle('01/01/2026', '31/01/2026');

        $this->assertSame([8_000, 10_000], $this->gateway->sleeps);
    }

    public function test_a_long_rate_limit_wait_is_not_retried_but_reported(): void
    {
        $connection = $this->connection([
            new HttpResponse(429, ['Retry-After' => '298'], '{ "statusCode": 429, "message": "Rate limit is exceeded. Try again in 298 seconds." }'),
            new HttpResponse(200, body: $this->getStub('request-vat-info-response')),
        ], retries: 5);

        try {
            (new RequestVatInfo)->using($connection)->handle('01/01/2026', '31/01/2026');
            $this->fail('A RateLimitExceededException was expected.');
        } catch (RateLimitExceededException $e) {
            $this->assertSame([], $this->gateway->sleeps);
            $this->assertSame(298, $e->getRetryAfter());
            $this->assertEqualsWithDelta(time() + 298, $e->getRetryAt(), 2);
            $this->assertSame(429, $e->getCode());
            $this->assertSame('Rate limit is exceeded. Try again in 298 seconds.', $e->getMessage());
        }
    }

    public function test_the_rate_limit_is_waited_out_when_the_connection_allows_it(): void
    {
        $connection = $this->connection([
            new HttpResponse(429, ['Retry-After' => '298'], '{ "statusCode": 429, "message": "Rate limit is exceeded. Try again in 298 seconds." }'),
            new HttpResponse(429, ['Retry-After' => '3']),
            new HttpResponse(200, body: $this->getStub('request-vat-info-response')),
        ], retries: 0)->withRateLimitWait(600);

        $result = (new RequestVatInfo)->using($connection)->handle('01/01/2026', '31/01/2026');

        $this->assertNotNull($result->getVatInfo());
        // Not counted as retries, and one second more than myDATA asks for
        $this->assertSame([299_000, 4_000], $this->gateway->sleeps);
    }

    public function test_rate_limit_waits_spend_neither_retries_nor_backoff(): void
    {
        $connection = $this->connection([
            new HttpResponse(429, ['Retry-After' => '2']),
            new HttpResponse(429, ['Retry-After' => '2']),
            new HttpResponse(503),
            new HttpResponse(200, body: $this->getStub('request-vat-info-response')),
        ], retries: 1)->withRateLimitWait(600);

        $result = (new RequestVatInfo)->using($connection)->handle('01/01/2026', '31/01/2026');

        $this->assertNotNull($result->getVatInfo());
        // The 503 still gets its one retry, with the first backoff delay
        $this->assertSame([3_000, 3_000, 100], $this->gateway->sleeps);
    }

    public function test_a_rate_limit_longer_than_the_allowed_wait_is_not_waited_out(): void
    {
        $connection = $this->connection([
            new HttpResponse(429, ['Retry-After' => '1560']),
        ], retries: 3)->withRateLimitWait(600);

        try {
            (new RequestVatInfo)->using($connection)->handle('01/01/2026', '31/01/2026');
            $this->fail('A RateLimitExceededException was expected.');
        } catch (RateLimitExceededException $e) {
            $this->assertSame([], $this->gateway->sleeps);
            $this->assertSame(1560, $e->getRetryAfter());
        }
    }

    public function test_the_rate_limit_waits_of_one_request_are_bounded(): void
    {
        $connection = $this->connection([
            new HttpResponse(429, ['Retry-After' => '250']),
            new HttpResponse(429, ['Retry-After' => '250']),
            new HttpResponse(429, ['Retry-After' => '250']),
            new HttpResponse(200, body: $this->getStub('request-vat-info-response')),
        ], retries: 0)->withRateLimitWait(600);

        try {
            (new RequestVatInfo)->using($connection)->handle('01/01/2026', '31/01/2026');
            $this->fail('A RateLimitExceededException was expected.');
        } catch (RateLimitExceededException $e) {
            // 251 + 251 fit in 600 s, a third wait would not
            $this->assertSame([251_000, 251_000], $this->gateway->sleeps);
            $this->assertSame(250, $e->getRetryAfter());
        }
    }

    public function test_the_throttle_survives_a_re_derived_connection(): void
    {
        $connection = $this->connection([
            new HttpResponse(200, body: $this->getStub('request-vat-info-response')),
            new HttpResponse(200, body: $this->getStub('request-vat-info-response')),
        ], retries: 0)->withThrottle(30_000);

        (new RequestVatInfo)->using($connection)->handle('01/01/2026', '31/01/2026');
        (new RequestVatInfo)->using($connection->withTimeout(5))->handle('01/01/2026', '31/01/2026');

        $this->assertCount(1, $this->gateway->sleeps);
        $this->assertEqualsWithDelta(30_000, $this->gateway->sleeps[0], 500);
    }

    public function test_the_rate_limit_wait_is_read_from_the_message_when_there_is_no_header(): void
    {
        $connection = $this->connection([new HttpResponse(429, body: 'Rate Limit is exceeded. Try again in 26 minutes')], retries: 0);

        try {
            (new RequestVatInfo)->using($connection)->handle('01/01/2026', '31/01/2026');
            $this->fail('A RateLimitExceededException was expected.');
        } catch (RateLimitExceededException $e) {
            $this->assertSame(26 * 60, $e->getRetryAfter());
        }
    }

    public function test_a_rate_limit_without_a_wait_has_no_retry_after(): void
    {
        $connection = $this->connection([new HttpResponse(429)], retries: 0);

        try {
            (new RequestVatInfo)->using($connection)->handle('01/01/2026', '31/01/2026');
            $this->fail('A RateLimitExceededException was expected.');
        } catch (RateLimitExceededException $e) {
            $this->assertNull($e->getRetryAfter());
            $this->assertNull($e->getRetryAt());
        }
    }

    public function test_requests_are_spaced_by_the_throttle(): void
    {
        $connection = $this->connection([
            new HttpResponse(200, body: $this->getStub('request-vat-info-response')),
            new HttpResponse(200, body: $this->getStub('request-vat-info-response')),
            new HttpResponse(200, body: $this->getStub('request-vat-info-response')),
        ], retries: 0)->withThrottle(30_000);

        $request = (new RequestVatInfo)->using($connection);
        $request->handle('01/01/2026', '31/01/2026');
        $request->handle('01/01/2026', '31/01/2026');
        $request->handle('01/01/2026', '31/01/2026');

        // The first request goes out at once, the next ones wait out the spacing. The
        // test gateway records the sleeps instead of sleeping, so each slot is reserved
        // further out: 30 s, then 60 s from the same moment.
        $this->assertCount(2, $this->gateway->sleeps);
        $this->assertEqualsWithDelta(30_000, $this->gateway->sleeps[0], 500);
        $this->assertEqualsWithDelta(60_000, $this->gateway->sleeps[1], 500);
    }

    public function test_no_throttle_by_default(): void
    {
        $connection = $this->connection([
            new HttpResponse(200, body: $this->getStub('request-vat-info-response')),
            new HttpResponse(200, body: $this->getStub('request-vat-info-response')),
        ], retries: 0);

        $request = (new RequestVatInfo)->using($connection);
        $request->handle('01/01/2026', '31/01/2026');
        $request->handle('01/01/2026', '31/01/2026');

        $this->assertSame([], $this->gateway->sleeps);
    }

    public function test_the_last_failure_is_thrown_when_retries_run_out(): void
    {
        $connection = $this->connection([new HttpResponse(429), new HttpResponse(429)], retries: 1);

        $this->expectException(RateLimitExceededException::class);

        try {
            (new RequestVatInfo)->using($connection)->handle('01/01/2026', '31/01/2026');
        } finally {
            $this->assertSame([100], $this->gateway->sleeps);
        }
    }

    public function test_connection_failures_are_retried(): void
    {
        $connection = $this->connection([
            new ConnectException('Could not resolve host', new Request('GET', 'test')),
            new HttpResponse(200, body: $this->getStub('request-vat-info-response')),
        ]);

        (new RequestVatInfo)->using($connection)->handle('01/01/2026', '31/01/2026');

        $this->assertSame([100], $this->gateway->sleeps);
    }

    /**
     * @return array<string, array{0: \Throwable}>
     */
    public static function timeouts(): array
    {
        return [
            'before the response headers' => [Timeouts::beforeHeaders()],
            'after the response headers' => [Timeouts::afterHeaders(new HttpResponse(200))],
        ];
    }

    #[DataProvider('timeouts')]
    public function test_a_timeout_is_never_retried(\Throwable $timeout): void
    {
        $connection = $this->connection([
            $timeout,
            new HttpResponse(200, body: $this->getStub('request-vat-info-response')),
        ]);

        $this->expectException(MyDataTimeoutException::class);

        try {
            (new RequestVatInfo)->using($connection)->handle('01/01/2026', '31/01/2026');
        } finally {
            $this->assertSame([], $this->gateway->sleeps);
        }
    }

    public function test_a_connect_timeout_is_retried_like_a_connection_failure(): void
    {
        if (! Timeouts::typed()) {
            $this->markTestSkipped('Guzzle 7 does not tell a connect timeout from the other timeouts.');
        }

        $connection = $this->connection([
            Timeouts::connect(),
            new HttpResponse(200, body: $this->getStub('request-vat-info-response')),
        ]);

        (new RequestVatInfo)->using($connection)->handle('01/01/2026', '31/01/2026');

        $this->assertSame([100], $this->gateway->sleeps);
    }

    public function test_client_errors_are_not_retried(): void
    {
        $connection = $this->connection([
            new HttpResponse(400, body: 'Bad request'),
            new HttpResponse(200, body: $this->getStub('request-vat-info-response')),
        ]);

        $this->expectException(TransmissionFailedException::class);

        try {
            (new RequestVatInfo)->using($connection)->handle('01/01/2026', '31/01/2026');
        } finally {
            $this->assertSame([], $this->gateway->sleeps);
        }
    }

    public function test_nothing_is_retried_by_default(): void
    {
        $connection = $this->connection([new HttpResponse(503)], retries: 0);

        $this->expectException(TransmissionFailedException::class);

        try {
            (new RequestVatInfo)->using($connection)->handle('01/01/2026', '31/01/2026');
        } finally {
            $this->assertSame([], $this->gateway->sleeps);
        }
    }

    public function test_an_unreachable_host_without_retries_reports_a_connection_failure(): void
    {
        $connection = $this->connection([new ConnectException('Could not resolve host', new Request('GET', 'test'))], retries: 0);

        $this->expectException(MyDataConnectionException::class);
        (new RequestVatInfo)->using($connection)->handle('01/01/2026', '31/01/2026');
    }
}

class SleeplessGateway extends GuzzleGateway
{
    /** @var list<int> */
    public array $sleeps = [];

    protected function sleep(int $milliseconds): void
    {
        $this->sleeps[] = $milliseconds;
    }
}
