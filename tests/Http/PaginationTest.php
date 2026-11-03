<?php

declare(strict_types=1);

namespace Tests\Http;

use Firebed\AadeMyData\Http\Connection;
use Firebed\AadeMyData\Http\Gateway;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\RequestDocs;
use Firebed\AadeMyData\Http\RequestMyIncome;
use Firebed\AadeMyData\Http\RequestVatInfo;
use Firebed\AadeMyData\Models\Invoice;
use PHPUnit\Framework\TestCase;
use Tests\Traits\UsesStubs;

class PaginationTest extends TestCase
{
    use UsesStubs;

    public function test_pages_follow_the_continuation_token(): void
    {
        $gateway = new PagingGateway([
            $this->getStub('requested-doc-with-continuation-token'),
            $this->getStub('request-doc-response'),
        ]);

        $request = (new RequestDocs)->using(Connection::make('u', 'k')->withGateway($gateway));
        $pages = iterator_to_array($request->pages('0', dateFrom: '01/01/2026', dateTo: '31/01/2026'), false);

        $this->assertCount(2, $pages);
        $this->assertNotNull($pages[0]->getContinuationToken());
        $this->assertNull($pages[1]->getContinuationToken());

        $this->assertSame(['mark' => '0', 'dateFrom' => '01/01/2026', 'dateTo' => '31/01/2026'], $gateway->queries[0]);
        $this->assertSame('01/01/2026', $gateway->queries[1]['dateFrom']);
        $this->assertSame($pages[0]->getContinuationToken()->getNextPartitionKey(), $gateway->queries[1]['nextPartitionKey']);
        $this->assertSame($pages[0]->getContinuationToken()->getNextRowKey(), $gateway->queries[1]['nextRowKey']);
    }

    public function test_pages_are_fetched_lazily(): void
    {
        $gateway = new PagingGateway([
            $this->getStub('requested-doc-with-continuation-token'),
            $this->getStub('request-doc-response'),
        ]);

        $pages = (new RequestDocs)->using(Connection::make('u', 'k')->withGateway($gateway))->pages('0');

        $this->assertCount(0, $gateway->queries);
        $pages->current();
        $this->assertCount(1, $gateway->queries);
    }

    public function test_invoices_flatten_every_page(): void
    {
        $gateway = new PagingGateway([
            $this->getStub('requested-doc-with-continuation-token'),
            $this->getStub('request-doc-response'),
        ]);

        $invoices = iterator_to_array((new RequestDocs)->using(Connection::make('u', 'k')->withGateway($gateway))->invoices('0'), false);

        $this->assertContainsOnlyInstancesOf(Invoice::class, $invoices);
        $this->assertCount(2, $invoices);
    }

    public function test_book_info_pages_end_without_a_continuation_token(): void
    {
        $withToken = $this->getStub('request-my-income-response');
        $lastPage = preg_replace('/<continuationToken>.*?<\/continuationToken>/s', '', $withToken);
        $gateway = new PagingGateway([$withToken, $lastPage]);

        $pages = iterator_to_array((new RequestMyIncome)->using(Connection::make('u', 'k')->withGateway($gateway))->pages('01/01/2026', '31/01/2026'), false);

        $this->assertCount(2, $pages);
        $this->assertCount(11, $pages[0]->getBookInfo());
        $this->assertCount(11, $pages[1]->getBookInfo());
        $this->assertNull($pages[1]->getContinuationToken());
        $this->assertArrayHasKey('nextRowKey', $gateway->queries[1]);
    }

    public function test_pages_resume_from_a_continuation_token(): void
    {
        $gateway = new PagingGateway([$this->getStub('request-doc-response')]);

        $pages = iterator_to_array((new RequestDocs)->using(Connection::make('u', 'k')->withGateway($gateway))->pages('0', nextPartitionKey: 'P1', nextRowKey: 'R1'), false);

        $this->assertCount(1, $pages);
        $this->assertSame(['mark' => '0', 'nextPartitionKey' => 'P1', 'nextRowKey' => 'R1'], $gateway->queries[0]);
    }

    public function test_a_repeated_continuation_token_ends_the_iteration(): void
    {
        $withToken = $this->getStub('requested-doc-with-continuation-token');
        $gateway = new PagingGateway([$withToken, $withToken, $withToken]);

        $pages = iterator_to_array((new RequestDocs)->using(Connection::make('u', 'k')->withGateway($gateway))->pages('0'), false);

        $this->assertCount(2, $pages);
        $this->assertCount(2, $gateway->queries);
    }

    public function test_vat_info_pages(): void
    {
        $gateway = new PagingGateway([$this->getStub('request-vat-info-response')]);

        $pages = iterator_to_array((new RequestVatInfo)->using(Connection::make('u', 'k')->withGateway($gateway))->pages('01/01/2026', '31/01/2026'), false);

        $this->assertCount(1, $pages);
        $this->assertSame(['dateFrom' => '01/01/2026', 'dateTo' => '31/01/2026', 'GroupedPerDay' => 'false'], $gateway->queries[0]);
    }
}

class PagingGateway implements Gateway
{
    /** @var list<array<string, mixed>> */
    public array $queries = [];

    /**
     * @param  list<string>  $responses  One response XML per call, in order
     */
    public function __construct(private array $responses) {}

    public function get(MyDataRequest $request, array $query): string
    {
        $this->queries[] = $query;

        return array_shift($this->responses) ?? throw new \LogicException('No more responses.');
    }

    public function post(MyDataRequest $request, ?array $query = null, ?string $body = null): string
    {
        throw new \LogicException('Not expected.');
    }
}
