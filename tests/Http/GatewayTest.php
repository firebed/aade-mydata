<?php

namespace Tests\Http;

use Firebed\AadeMyData\Exceptions\ErrorResponseException;
use Firebed\AadeMyData\Exceptions\InvalidResponseException;
use Firebed\AadeMyData\Exceptions\MyDataAuthenticationException;
use Firebed\AadeMyData\Exceptions\TransmissionFailedException;
use Firebed\AadeMyData\Http\Gateway;
use Firebed\AadeMyData\Http\GuzzleGateway;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\RequestVatInfo;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Http\SendPaymentsMethod;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\PaymentMethod;
use Firebed\AadeMyData\Models\PaymentMethodDetail;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response as HttpResponse;
use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;

class GatewayTest extends MyDataHttpTestCase
{
    protected function tearDown(): void
    {
        MyDataRequest::setGateway(null);
        MyDataRequest::setHandler(null);

        // Credentials and environment are global, so leave them as setUp() found them
        $this->initErpDev();
    }

    public function test_default_gateway_is_guzzle(): void
    {
        $this->assertInstanceOf(GuzzleGateway::class, MyDataRequest::gateway());
    }

    public function test_post_requests_are_dispatched_to_the_registered_gateway(): void
    {
        $gateway = new RecordingGateway();
        MyDataRequest::setGateway($gateway);

        $invoice = new Invoice();
        $sendInvoices = new SendInvoices();
        $this->assertNull($sendInvoices->getInvoicesDoc());

        $responseDoc = $sendInvoices->handle($invoice);

        $this->assertSame($sendInvoices, $gateway->request);
        $this->assertSame($invoice, $sendInvoices->getInvoicesDoc()->first());
        $this->assertNull($gateway->query);
        $this->assertStringContainsString('<InvoicesDoc', $gateway->body);
        $this->assertSame('400001', $responseDoc->first()->getInvoiceMark());
        $this->assertStringContainsString('<invoiceMark>400001</invoiceMark>', $sendInvoices->getResponseXML());
        $this->assertStringContainsString('<InvoicesDoc', $sendInvoices->getRequestXml());
    }

    public function test_payment_method_requests_are_dispatched_to_the_registered_gateway(): void
    {
        $gateway = new RecordingGateway('<?xml version="1.0" encoding="utf-8"?><ResponseDoc><response><index>1</index><invoiceMark>400001</invoiceMark><paymentMethodMark>500001</paymentMethodMark><statusCode>Success</statusCode></response></ResponseDoc>');
        MyDataRequest::setGateway($gateway);

        $paymentMethod = (new PaymentMethod())->setInvoiceMark(400001)
            ->addPaymentMethodDetails((new PaymentMethodDetail())->setType(7)->setAmount(12.4));

        $request = new SendPaymentsMethod();
        $this->assertNull($request->getPaymentMethodsDoc());

        $responseDoc = $request->handle($paymentMethod);

        $this->assertSame($request, $gateway->request);
        $this->assertSame($paymentMethod, $request->getPaymentMethodsDoc()->first());
        $this->assertNull($gateway->query);
        $this->assertStringContainsString('<PaymentMethodsDoc', $gateway->body);
        $this->assertSame('500001', $responseDoc->first()->getPaymentMethodMark());
    }

    public function test_get_requests_are_dispatched_to_the_registered_gateway(): void
    {
        $gateway = new RecordingGateway($this->getStub('request-vat-info-response'));
        MyDataRequest::setGateway($gateway);

        $request = new RequestVatInfo();
        $request->handle('01/01/2024', '31/12/2024');

        $this->assertSame($request, $gateway->request);
        $this->assertSame(['dateFrom' => '01/01/2024', 'dateTo' => '31/12/2024', 'GroupedPerDay' => 'false'], $gateway->query);
        $this->assertNull($gateway->body);
    }

    public function test_a_gateway_can_be_registered_for_a_single_request(): void
    {
        $global = new RecordingGateway();
        $perRequest = new RecordingGateway();
        MyDataRequest::setGateway($global);

        $request = new SendInvoices();
        $request->usingGateway($perRequest)->handle(new Invoice());

        $this->assertSame($request, $perRequest->request);
        $this->assertNull($global->request);
        $this->assertSame($global, MyDataRequest::gateway());

        // The global gateway keeps serving every other request
        $other = new SendInvoices();
        $other->handle(new Invoice());
        $this->assertSame($other, $global->request);
    }

    public function test_a_per_request_gateway_can_be_cleared(): void
    {
        $global = new RecordingGateway();
        MyDataRequest::setGateway($global);

        $request = new SendInvoices();
        $request->usingGateway(new RecordingGateway())->usingGateway(null)->handle(new Invoice());

        $this->assertSame($request, $global->request);
    }

    public function test_empty_gateway_responses_are_rejected(): void
    {
        MyDataRequest::setGateway(new RecordingGateway('   '));

        $this->expectException(InvalidResponseException::class);
        (new SendInvoices())->handle(new Invoice());
    }

    public function test_setting_null_restores_the_default_gateway(): void
    {
        MyDataRequest::setGateway(new RecordingGateway());
        MyDataRequest::setGateway(null);

        $this->assertInstanceOf(GuzzleGateway::class, MyDataRequest::gateway());
    }

    public function test_default_gateway_uses_the_mock_handler(): void
    {
        MyDataRequest::setHandler(new MockHandler([
            new HttpResponse(200, body: $this->getStub('send-invoices-single-response')),
        ]));

        $responseDoc = (new SendInvoices())->handle(new Invoice());

        $this->assertSame('480301204040191', $responseDoc->first()->getInvoiceMark());
    }

    public function test_default_gateway_requires_credentials(): void
    {
        MyDataRequest::setCredentials('', '');

        $this->expectException(MyDataAuthenticationException::class);
        (new SendInvoices())->handle(new Invoice());
    }

    public static function unauthorizedReasons(): array
    {
        $refusal = 'User 110250755 is not authorized to view status for invoice 400001. Only the issuer, recipient, or related carriers/third parties can view the status.';
        $apim = 'Access denied due to invalid subscription key. Make sure to provide a valid key for an active subscription.';
        $oauth = '{"error":"invalid_token","error_description":"The token has expired"}';

        return [
            'plain text' => [$refusal, $refusal],
            'JSON message' => [json_encode(['statusCode' => 401, 'message' => $apim]), $apim],
            'JSON string' => ['"Access denied"', 'Access denied'],
            'JSON without a message' => [$oauth, $oauth],
            'short multibyte' => [str_repeat('Δ', 300), str_repeat('Δ', 300)],
            'long, capped' => [str_repeat('Δ', 2000), str_repeat('Δ', 500) . '…'],
            'not UTF-8, capped' => [str_repeat("\xC4", 2000), str_repeat('?', 500) . '…'],
        ];
    }

    #[DataProvider('unauthorizedReasons')]
    public function test_unauthorized_response_reports_its_reason(string $body, string $expected): void
    {
        $this->assertSame($expected, $this->unauthorized($body)->getMessage());
    }

    public static function unusableReasons(): array
    {
        return [
            'empty body' => [''],
            'HTML page' => ['<html><head><title>401 Unauthorized</title></head><body>...</body></html>'],
        ];
    }

    #[DataProvider('unusableReasons')]
    public function test_unauthorized_response_without_a_usable_reason_reports_bad_credentials(string $body): void
    {
        $e = $this->unauthorized($body);

        $this->assertSame(401, $e->getCode());
        $this->assertStringContainsString('Please check your user id and subscription key', $e->getMessage());
    }

    public function test_http_error_with_an_error_document_throws_its_errors(): void
    {
        MyDataRequest::setHandler(new MockHandler([
            new HttpResponse(400, body: '<?xml version="1.0" encoding="utf-8"?><ResponseDoc><response><statusCode>ValidationError</statusCode><errors><error><message>Invalid request</message><code>101</code></error></errors></response></ResponseDoc>'),
        ]));

        try {
            (new RequestVatInfo())->handle('01/01/2026', '31/01/2026');
            $this->fail('An ErrorResponseException was expected.');
        } catch (ErrorResponseException $e) {
            $this->assertSame('101: Invalid request', $e->getMessage());
            $this->assertSame('101', $e->getErrors()->first()->getCode());
            // The HTTP status stays the code, and callers catching the previous exception type keep working
            $this->assertSame(400, $e->getCode());
            $this->assertInstanceOf(TransmissionFailedException::class, $e);
        }
    }

    public function test_http_error_without_an_error_document_reports_its_body(): void
    {
        MyDataRequest::setHandler(new MockHandler([
            new HttpResponse(404, body: '{"error":"Not found","message":"Invoice not found"}'),
        ]));

        try {
            (new RequestVatInfo())->handle('01/01/2026', '31/01/2026');
            $this->fail('A TransmissionFailedException was expected.');
        } catch (TransmissionFailedException $e) {
            $this->assertNotInstanceOf(ErrorResponseException::class, $e);
            $this->assertSame('{"error":"Not found","message":"Invoice not found"}', $e->getMessage());
            $this->assertSame(404, $e->getCode());
        }
    }

    private function unauthorized(string $body): MyDataAuthenticationException
    {
        MyDataRequest::setHandler(new MockHandler([new HttpResponse(401, body: $body)]));

        try {
            (new RequestVatInfo())->handle('01/01/2026', '31/01/2026');
        } catch (MyDataAuthenticationException $e) {
            return $e;
        }

        $this->fail('A MyDataAuthenticationException was expected.');
    }

    public function test_has_credentials(): void
    {
        MyDataRequest::setCredentials('user', 'key');
        $this->assertTrue(MyDataRequest::hasCredentials());

        MyDataRequest::setCredentials('', 'key');
        $this->assertFalse(MyDataRequest::hasCredentials());
    }
}

class RecordingGateway implements Gateway
{
    public ?MyDataRequest $request = null;
    public ?array $query = null;
    public ?string $body = null;

    public function __construct(private ?string $responseXml = null)
    {
    }

    public function get(MyDataRequest $request, array $query): string
    {
        $this->request = $request;
        $this->query = $query;
        $this->body = null;

        return $this->responseXml ?? throw new LogicException('No response XML configured for GET.');
    }

    public function post(MyDataRequest $request, ?array $query = null, ?string $body = null): string
    {
        $this->request = $request;
        $this->query = $query;
        $this->body = $body;

        return $this->responseXml ?? '<?xml version="1.0" encoding="utf-8"?><ResponseDoc><response><index>1</index><invoiceMark>400001</invoiceMark><statusCode>Success</statusCode></response></ResponseDoc>';
    }
}
