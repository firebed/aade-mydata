<?php

declare(strict_types=1);

namespace Tests\Http;

use Firebed\AadeMyData\Http\CancelInvoice;
use Firebed\AadeMyData\Http\Connection;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\MyData;
use PHPUnit\Framework\TestCase;

class ListenerRemovalTest extends TestCase
{
    /** @var list<string> */
    private array $calls = [];

    protected function setUp(): void
    {
        MyData::fake();
    }

    protected function tearDown(): void
    {
        MyData::stopFaking();
        MyDataRequest::setDefaultConnection(null);
    }

    private function record(string $name): \Closure
    {
        return function () use ($name) {
            $this->calls[] = $name;
        };
    }

    public function test_a_keyed_listener_replaces_the_one_with_the_same_key(): void
    {
        $connection = Connection::make('u', 'k')
            ->afterSending($this->record('first'), key: 'audit')
            ->afterSending($this->record('second'), key: 'audit')
            ->afterSending($this->record('unkeyed'));

        (new MyData($connection))->cancelInvoice(1);

        $this->assertSame(['second', 'unkeyed'], $this->calls);
    }

    public function test_a_listener_is_removed_by_key_from_both_lists(): void
    {
        $connection = Connection::make('u', 'k')
            ->afterSending($this->record('audit'), key: 'audit')
            ->beforeRetry($this->record('retry'), key: 'audit')
            ->afterSending($this->record('metrics'), key: 'metrics')
            ->withoutListener('audit');

        (new MyData($connection))->cancelInvoice(1);

        $this->assertSame(['metrics'], $this->calls);
    }

    public function test_a_listener_is_removed_by_itself(): void
    {
        $audit = $this->record('audit');

        $connection = Connection::make('u', 'k')
            ->afterSending($audit)
            ->afterSending($this->record('metrics'));

        (new MyData($connection->withoutListener($audit)))->cancelInvoice(1);
        $this->assertSame(['metrics'], $this->calls);

        // The connection it came from keeps it
        (new MyData($connection))->cancelInvoice(1);
        $this->assertSame(['metrics', 'audit', 'metrics'], $this->calls);
    }

    public function test_every_listener_is_removed_but_the_logger_stays(): void
    {
        $logger = new MemoryLogger;

        $connection = Connection::make('u', 'k')
            ->withLogger($logger)
            ->afterSending($this->record('audit'), key: 'audit')
            ->afterSending($this->record('metrics'))
            ->withoutListeners();

        (new MyData($connection))->cancelInvoice(1);

        $this->assertSame([], $this->calls);
        $this->assertCount(1, $logger->records);
    }

    public function test_the_default_connection_listeners_can_be_removed(): void
    {
        MyDataRequest::afterSending($this->record('audit'), key: 'audit');
        MyDataRequest::afterSending($this->record('audit again'), key: 'audit');
        MyDataRequest::afterSending($this->record('metrics'), key: 'metrics');
        (new CancelInvoice)->handle(1);

        MyDataRequest::removeListener('audit');
        (new CancelInvoice)->handle(1);

        MyDataRequest::removeListeners();
        (new CancelInvoice)->handle(1);

        $this->assertSame(['audit again', 'metrics', 'metrics'], $this->calls);
    }
}
