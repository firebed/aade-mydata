<?php

declare(strict_types=1);

namespace Tests;

use Firebed\AadeMyData\Models\Duplicate;
use Firebed\AadeMyData\Models\Response;
use Firebed\AadeMyData\Xml\ResponseDocReader;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Traits\UsesStubs;

class ResponseDuplicateTest extends TestCase
{
    use UsesStubs;

    /**
     * @return list<Response>
     */
    private function responses(): array
    {
        return (new ResponseDocReader)->parseXML($this->getStub('response-doc-duplicate'))->all();
    }

    public function test_a_duplicate_names_the_mark_and_authentication_code(): void
    {
        $duplicate = $this->responses()[0]->getDuplicate();

        $this->assertEquals(new Duplicate('0000000000000000000000000000000000000B01', 900000000000201, '000000000000000000000000000000000000AC11'), $duplicate);
        $this->assertTrue($duplicate->isStored());
    }

    public function test_a_duplicate_still_queued_has_no_authentication_code(): void
    {
        $duplicate = $this->responses()[1]->getDuplicate();

        $this->assertSame(900000000000202, $duplicate?->mark);
        $this->assertNull($duplicate?->authenticationCode);
        $this->assertFalse($duplicate?->isStored());
    }

    #[DataProvider('queuedWordings')]
    public function test_the_authentication_code_may_be_left_out_or_empty(string $message): void
    {
        $response = Response::fromArray(['statusCode' => 'ValidationError', 'errors' => ['error' => [['code' => '228', 'message' => $message]]]]);

        $this->assertSame(900000000000202, $response->getDuplicate()?->mark);
        $this->assertNull($response->getDuplicate()?->authenticationCode);
    }

    public static function queuedWordings(): array
    {
        return [
            'left out' => ['The UID: 0000000000000000000000000000000000000B02 is invalid . It has already been sent for another invoice (MARK:900000000000202)'],
            'empty' => ['The UID: 0000000000000000000000000000000000000B02 is invalid . It has already been sent for another invoice (MARK:900000000000202 , AUTHENTICATION_CODE:)'],
        ];
    }

    public function test_other_answers_are_no_duplicate(): void
    {
        $this->assertNull($this->responses()[2]->getDuplicate(), 'A resend dated a past day (238) is no duplicate');

        $sameRequest = Response::fromArray(['statusCode' => 'ValidationError', 'errors' => ['error' => [['code' => '228', 'message' => 'The UID is invalid . It exists for another invoice contained in the same request']]]]);
        $this->assertNull($sameRequest->getDuplicate(), 'A uid repeated in one request names no mark');

        $this->assertNull(Response::fromArray(['statusCode' => 'Success', 'invoiceMark' => '1'])->getDuplicate());
    }

    public function test_a_duplicate_becomes_the_success_response_my_data_gave(): void
    {
        $response = $this->responses()[0]->getDuplicate()?->toResponse(1);

        $this->assertTrue($response?->isSuccessful());
        $this->assertSame(1, $response->getIndex());
        $this->assertSame('900000000000201', $response->getInvoiceMark());
        $this->assertSame('0000000000000000000000000000000000000B01', $response->getInvoiceUid());
        $this->assertSame('000000000000000000000000000000000000AC11', $response->getAuthenticationCode());
        $this->assertNull($response->getQrUrl());

        $this->assertFalse($this->responses()[1]->getDuplicate()?->toResponse()->has('authenticationCode'));
    }

    public function test_a_provider_response_names_the_reception_providers(): void
    {
        $response = (new ResponseDocReader)->parseXML($this->getStub('send-invoices-provider-response'))->first();

        $this->assertSame(['800000001'], $response?->getReceptionProviders()?->vatNumbers());
        $this->assertSame(['accounting@example.com'], $response->getReceptionEmails()?->all());
        $this->assertNull($response->getDuplicate());
    }
}
