<?php

namespace Tests\Http;

use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\RequestMyExpenses;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tests\Traits\UsesStubs;

class RequestMyExpensesTest extends TestCase
{
    use UsesStubs;

    protected function setUp(): void
    {
        MyDataRequest::init('test_user_id', 'test_user_secret', 'dev');
    }
    
    /**
     * @throws GuzzleException
     */
    public function test_it_returns_docs()
    {
        MyDataRequest::setHandler(new MockHandler([
            new Response(200, body: $this->getStub('request-my-expenses-response')),
        ]));

        $request = new RequestMyExpenses();
        $bookInfo = $request->handle('01/01/2024', '31/12/2024');

        $this->assertCount(11, $bookInfo->getBookInfo());
    }
}