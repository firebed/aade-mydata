<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class ContinuationTokenTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_responses_doc_continuation_token()
    {
        $doc = $this->getRequestedDocFromXml('requested-doc-with-continuation-token');

        $this->assertIsObject($doc->getContinuationToken());
        $this->assertEquals('78A4SD5FG5GH55W5DFV5HJN5', $doc->getContinuationToken()->getNextPartitionKey());
        $this->assertEquals('AS25AS45F45GD55', $doc->getContinuationToken()->getNextRowKey());
    }
}