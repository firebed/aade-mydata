<?php

declare(strict_types=1);

namespace Tests\Http;

use Firebed\AadeMyData\Exceptions\RateLimitExceededException;
use Firebed\AadeMyData\Exceptions\TransmissionFailedException;
use Firebed\AadeMyData\Http\Connection;
use Firebed\AadeMyData\Http\Exchange;
use Firebed\AadeMyData\Http\GuzzleGateway;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\RequestVatInfo;
use Firebed\AadeMyData\Http\Retry;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\MyData;
use Firebed\AadeMyData\Testing\FakeGateway;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response as HttpResponse;
use PHPUnit\Framework\TestCase;
use Tests\Traits\UsesStubs;

class ListenersTest extends TestCase
{
    use UsesStubs;

    protected function tearDown(): void
    {
        MyData::stopFaking();
        MyDataRequest::setDefaultConnection(null);
    }

    /**
     * A connection on a mock transport that never sleeps.
     *
     * @param  list<HttpResponse>  $responses
     */
    private function connection(array $responses, int $retries = 0): Connection
    {
        return Connection::make('user-id', 'secret-subscription-key')
            ->withHandler(HandlerStack::create(new MockHandler($responses)))
            ->withGateway(new class extends GuzzleGateway
            {
                protected function sleep(int $milliseconds): void {}
            })
            ->withRetries($retries, 100);
    }

    public function test_after_sending_gets_the_request_and_response(): void
    {
        MyData::fake();
        $exchanges = [];

        $mydata = new MyData(Connection::make('u', 'k')->afterSending(function (Exchange $exchange) use (&$exchanges) {
            $exchanges[] = $exchange;
        }));

        $mydata->sendInvoices(Invoice::factory()->make());

        $this->assertCount(1, $exchanges);
        $exchange = $exchanges[0];
        $this->assertInstanceOf(SendInvoices::class, $exchange->request);
        $this->assertSame('SendInvoices', $exchange->action());
        $this->assertSame('POST', $exchange->method);
        $this->assertSame('https://mydataapidev.aade.gr/SendInvoices', $exchange->url);
        $this->assertStringContainsString('<InvoicesDoc', $exchange->requestBody);
        $this->assertStringContainsString('400000000000001', $exchange->responseBody);
        $this->assertTrue($exchange->successful());
        $this->assertGreaterThanOrEqual(0, $exchange->duration);
    }

    public function test_after_sending_gets_the_failure_which_is_still_thrown(): void
    {
        MyData::fake(['*' => new RateLimitExceededException(retryAfter: 300)]);
        $exchange = null;

        $mydata = new MyData(Connection::make('u', 'k')->afterSending(function (Exchange $e) use (&$exchange) {
            $exchange = $e;
        }));

        try {
            $mydata->requestVatInfo('01/01/2026', '31/01/2026');
            $this->fail('The failure was not thrown.');
        } catch (RateLimitExceededException) {
        }

        $this->assertFalse($exchange->successful());
        $this->assertInstanceOf(RateLimitExceededException::class, $exchange->exception);
        $this->assertNull($exchange->responseBody);
        $this->assertSame(['dateFrom' => '01/01/2026', 'dateTo' => '31/01/2026'], array_intersect_key($exchange->query, ['dateFrom' => 1, 'dateTo' => 1]));
    }

    public function test_listeners_add_up_on_a_copy_of_the_connection(): void
    {
        MyData::fake();
        $calls = [];

        $connection = Connection::make('u', 'k');
        $listened = $connection
            ->afterSending(function () use (&$calls) {
                $calls[] = 'first';
            })
            ->afterSending(function () use (&$calls) {
                $calls[] = 'second';
            });

        (new MyData($connection))->cancelInvoice(1);
        $this->assertSame([], $calls);

        (new MyData($listened))->cancelInvoice(1);
        $this->assertSame(['first', 'second'], $calls);
    }

    public function test_before_retry_gets_every_retry_and_after_sending_the_outcome_once(): void
    {
        $retries = [];
        $exchanges = 0;

        $connection = $this->connection([
            new HttpResponse(503),
            new HttpResponse(429, ['Retry-After' => '2']),
            new HttpResponse(200, body: $this->getStub('request-vat-info-response')),
        ], retries: 3)
            ->beforeRetry(function (Retry $retry) use (&$retries) {
                $retries[] = $retry;
            })
            ->afterSending(function () use (&$exchanges) {
                $exchanges++;
            });

        (new RequestVatInfo)->using($connection)->handle('01/01/2026', '31/01/2026');

        $this->assertCount(2, $retries);
        $this->assertSame([1, 100, false], [$retries[0]->attempt, $retries[0]->delay, $retries[0]->rateLimited()]);
        $this->assertInstanceOf(TransmissionFailedException::class, $retries[0]->reason);
        $this->assertSame([2, 2000, true], [$retries[1]->attempt, $retries[1]->delay, $retries[1]->rateLimited()]);
        $this->assertSame('RequestVatInfo', $retries[1]->action());
        $this->assertSame(1, $exchanges);
    }

    public function test_the_logger_writes_requests_retries_and_failures(): void
    {
        $logger = new MemoryLogger;

        $connection = $this->connection([
            new HttpResponse(503),
            new HttpResponse(200, body: $this->getStub('request-vat-info-response')),
            new HttpResponse(429, ['Retry-After' => '300'], 'Rate limit is exceeded. Try again in 300 seconds.'),
        ], retries: 1)->withLogger($logger);

        (new RequestVatInfo)->using($connection)->handle('01/01/2026', '31/01/2026');

        try {
            (new RequestVatInfo)->using($connection)->handle('01/01/2026', '31/01/2026');
        } catch (RateLimitExceededException) {
        }

        $this->assertSame(['warning', 'info', 'warning'], array_column($logger->records, 'level'));
        $this->assertStringStartsWith('myDATA RequestVatInfo: retry 1 in 100 ms after:', $logger->records[0]['message']);
        $this->assertMatchesRegularExpression('/^myDATA RequestVatInfo answered in \d+ ms$/', $logger->records[1]['message']);
        $this->assertStringContainsString('failed after', $logger->records[2]['message']);
        $this->assertStringContainsString('Try again in 300 seconds', $logger->records[2]['message']);
        // Without the bodies the exception is named, not attached: its message is not redacted
        $this->assertSame(RateLimitExceededException::class, $logger->records[2]['context']['exception_class']);
        $this->assertArrayNotHasKey('exception', $logger->records[2]['context']);

        $this->assertSame(['action', 'method', 'url', 'query', 'duration_ms'], array_keys($logger->records[1]['context']));

        // The credentials are never logged
        $logged = json_encode(array_map(fn (array $record) => [$record['message'], array_diff_key($record['context'], ['exception' => 1])], $logger->records));
        $this->assertStringNotContainsString('secret-subscription-key', $logged);
        $this->assertStringNotContainsString('user-id', $logged);
    }

    public function test_the_logger_writes_the_bodies_only_when_asked(): void
    {
        MyData::fake([SendInvoices::class => FakeGateway::rejected(101, 'Invalid')]);
        $logger = new MemoryLogger;

        (new MyData(Connection::make('u', 'k')->withLogger($logger, withBodies: true)))->sendInvoices(Invoice::factory()->make());

        $this->assertStringContainsString('<InvoicesDoc', $logger->records[0]['context']['request_body']);
        $this->assertStringContainsString('Invalid', $logger->records[0]['context']['response_body']);
    }

    public function test_other_failures_are_logged_as_errors(): void
    {
        $logger = new MemoryLogger;

        try {
            (new RequestVatInfo)->using($this->connection([new HttpResponse(500, body: 'Server error')])->withLogger($logger))->handle('01/01/2026', '31/01/2026');
        } catch (TransmissionFailedException) {
        }

        $this->assertSame('error', $logger->records[0]['level']);
    }

    public function test_the_default_connection_takes_listeners_and_a_logger(): void
    {
        MyData::fake();
        $logger = new MemoryLogger;
        $actions = [];

        MyDataRequest::setLogger($logger);
        MyDataRequest::afterSending(function (Exchange $exchange) use (&$actions) {
            $actions[] = $exchange->action();
        });
        MyDataRequest::beforeRetry(fn () => null);

        (new SendInvoices)->handle(Invoice::factory()->make());

        $this->assertSame(['SendInvoices'], $actions);
        $this->assertCount(1, $logger->records);
    }
}
