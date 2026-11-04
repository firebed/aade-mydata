<?php

declare(strict_types=1);

namespace Tests\Http;

use Firebed\AadeMyData\Exceptions\RateLimitExceededException;
use Firebed\AadeMyData\Http\Connection;
use Firebed\AadeMyData\Http\Exchange;
use Firebed\AadeMyData\Http\GuzzleGateway;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\RequestVatInfo;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\MyData;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response as HttpResponse;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Tests\Traits\UsesStubs;

class ListenerFailureTest extends TestCase
{
    use UsesStubs;

    protected function tearDown(): void
    {
        MyData::stopFaking();
        MyDataRequest::setDefaultConnection(null);
    }

    public function test_a_throwing_listener_never_hides_the_answer(): void
    {
        MyData::fake();
        $after = [];

        $mydata = new MyData(Connection::make('u', 'k')
            ->afterSending(fn () => throw new RuntimeException('Audit log is down'))
            ->afterSending(function (Exchange $e) use (&$after) {
                $after[] = $e->action();
            }));

        $this->assertSame('400000000000001', $mydata->sendInvoices(Invoice::factory()->make())->first()->getInvoiceMark());
        $this->assertSame(['SendInvoices'], $after);
    }

    public function test_a_throwing_listener_never_hides_the_failure(): void
    {
        MyData::fake(['*' => new RateLimitExceededException(retryAfter: 300)]);

        $this->expectException(RateLimitExceededException::class);

        (new MyData(Connection::make('u', 'k')->afterSending(fn () => throw new RuntimeException('Audit log is down'))))
            ->requestVatInfo('01/01/2026', '31/01/2026');
    }

    public function test_a_throwing_retry_listener_does_not_stop_the_retries(): void
    {
        $connection = Connection::make('u', 'k')
            ->withHandler(HandlerStack::create(new MockHandler([new HttpResponse(503), new HttpResponse(200, body: $this->getStub('request-vat-info-response'))])))
            ->withGateway(new class extends GuzzleGateway
            {
                protected function sleep(int $milliseconds): void {}
            })
            ->withRetries(1)
            ->beforeRetry(fn () => throw new RuntimeException('Metrics are down'));

        $this->assertNotNull((new RequestVatInfo)->using($connection)->handle('01/01/2026', '31/01/2026')->getVatInfo());
    }

    public function test_a_logger_replaces_the_one_before_and_null_stops_logging(): void
    {
        MyData::fake();
        [$first, $second] = [new MemoryLogger, new MemoryLogger];

        MyDataRequest::setLogger($first);
        MyDataRequest::setLogger($second);
        (new SendInvoices)->handle(Invoice::factory()->make());

        MyDataRequest::setLogger(null);
        (new SendInvoices)->handle(Invoice::factory()->make());

        $this->assertCount(0, $first->records);
        $this->assertCount(1, $second->records);
    }

    public function test_vat_numbers_of_the_query_are_logged_only_with_the_bodies(): void
    {
        MyData::fake();
        [$plain, $full] = [new MemoryLogger, new MemoryLogger];

        (new MyData(Connection::make('u', 'k')->withLogger($plain)))->cancelInvoice(400001, '123456789');
        (new MyData(Connection::make('u', 'k')->withLogger($full, withBodies: true)))->cancelInvoice(400001, '123456789');

        $this->assertSame(['mark' => 400001], $plain->records[0]['context']['query']);
        $this->assertSame('123456789', $full->records[0]['context']['query']['entityVatNumber']);
    }
}
