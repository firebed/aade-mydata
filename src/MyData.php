<?php

declare(strict_types=1);

namespace Firebed\AadeMyData;

use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\Statements\RecallStatus;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\CancelDeliveryNote;
use Firebed\AadeMyData\Http\CancelInvoice;
use Firebed\AadeMyData\Http\CancelReceivingNote;
use Firebed\AadeMyData\Http\Connection;
use Firebed\AadeMyData\Http\DigitalGoodsMovement\ConfirmDeliveryOutcome;
use Firebed\AadeMyData\Http\DigitalGoodsMovement\ConfirmDeliveryReturn;
use Firebed\AadeMyData\Http\DigitalGoodsMovement\GenerateGroupQrCode;
use Firebed\AadeMyData\Http\DigitalGoodsMovement\RegisterTransfer;
use Firebed\AadeMyData\Http\DigitalGoodsMovement\RejectDeliveryNote;
use Firebed\AadeMyData\Http\DigitalGoodsMovement\RequestDeliveryNoteStatus;
use Firebed\AadeMyData\Http\DigitalGoodsMovement\RequestGroupQrDetails;
use Firebed\AadeMyData\Http\Environment;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\RequestDocs;
use Firebed\AadeMyData\Http\RequestE3Info;
use Firebed\AadeMyData\Http\RequestMyExpenses;
use Firebed\AadeMyData\Http\RequestMyIncome;
use Firebed\AadeMyData\Http\RequestProviderDocs;
use Firebed\AadeMyData\Http\RequestReceiverInfo;
use Firebed\AadeMyData\Http\RequestTransmittedDocs;
use Firebed\AadeMyData\Http\RequestVatInfo;
use Firebed\AadeMyData\Http\SendExpensesClassification;
use Firebed\AadeMyData\Http\SendIncomeClassification;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Http\SendPaymentsMethod;
use Firebed\AadeMyData\Http\SendUnsignedInvoices;
use Firebed\AadeMyData\Http\Statements\RecallStatement;
use Firebed\AadeMyData\Http\Statements\RequestStatements;
use Firebed\AadeMyData\Http\Statements\SendStatement;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryNoteStatusResponse;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryOutcome;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryRejection;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryReturn;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\GroupQrCode;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\GroupQrCodeResponse;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\GroupQrDetailsResponse;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\ResponseDoc as DeliveryResponseDoc;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\Transport;
use Firebed\AadeMyData\Models\ExpensesClassificationsDoc;
use Firebed\AadeMyData\Models\IncomeClassificationsDoc;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceExpensesClassification;
use Firebed\AadeMyData\Models\InvoiceIncomeClassification;
use Firebed\AadeMyData\Models\InvoicesDoc;
use Firebed\AadeMyData\Models\PaymentMethod;
use Firebed\AadeMyData\Models\PaymentMethodsDoc;
use Firebed\AadeMyData\Models\ReceiverInfoDoc;
use Firebed\AadeMyData\Models\RequestedBookInfo;
use Firebed\AadeMyData\Models\RequestedDoc;
use Firebed\AadeMyData\Models\RequestedE3Info;
use Firebed\AadeMyData\Models\RequestedProviderDoc;
use Firebed\AadeMyData\Models\RequestedVatInfo;
use Firebed\AadeMyData\Models\ResponseDoc;
use Firebed\AadeMyData\Models\Statements\RequestedStatementDoc;
use Firebed\AadeMyData\Models\Statements\Statement;
use Firebed\AadeMyData\Models\Statements\StatementDoc;
use Firebed\AadeMyData\Models\Statements\StatementResponseDoc;
use Firebed\AadeMyData\Testing\FakeGateway;

/**
 * A myDATA client bound to one Connection, for code that serves several tenants or
 * simply prefers an object over the static MyDataRequest configuration.
 *
 * <pre>
 * $mydata = MyData::connect($userId, $subscriptionKey, 'prod');
 * $response = $mydata->sendInvoices($invoice);
 *
 * // Or with a fully configured connection
 * $mydata = new MyData(Connection::make($userId, $subscriptionKey)->withRetries(3)->withTimeout(30));
 * </pre>
 *
 * Every method builds the matching request, binds it to the connection and handles it.
 * The request object itself (e.g. for getRequestXml() / getResponseXML()) is available
 * from lastRequest(), or build one yourself with request().
 */
final class MyData
{
    private ?MyDataRequest $lastRequest = null;

    public function __construct(private readonly Connection $connection) {}

    public static function connect(string $userId, string $subscriptionKey, Environment|string $environment = Environment::Development, bool $provider = false): self
    {
        return new self(Connection::make($userId, $subscriptionKey, $environment, $provider));
    }

    /**
     * Answer every request with fake responses instead of sending it to myDATA, whatever
     * connection it uses, and record it for assertions. Call stopFaking() when the test ends.
     *
     * <pre>
     * $fake = MyData::fake([
     *     SendInvoices::class => FakeGateway::rejected(101, 'Invalid VAT number'),
     *     RequestDocs::class => FakeGateway::sequence($page1, $page2),
     * ]);
     *
     * // … run the code under test …
     *
     * $fake->assertSent(SendInvoices::class, fn (SendInvoices $request) => $request->getInvoicesDoc()->count() === 1);
     * </pre>
     *
     * @param  array<class-string<MyDataRequest>|'*', mixed>  $responses  Responses by request class, see FakeGateway
     */
    public static function fake(array $responses = []): FakeGateway
    {
        $fake = new FakeGateway($responses);
        MyDataRequest::intercept($fake);

        return $fake;
    }

    /**
     * Send requests to myDATA again, after fake().
     */
    public static function stopFaking(): void
    {
        MyDataRequest::intercept(null);
    }

    public function connection(): Connection
    {
        return $this->connection;
    }

    /**
     * A client on a changed connection, e.g. <code>$mydata->with(fn (Connection $c) => $c->asProvider())</code>.
     *
     * @param  callable(Connection): Connection  $change
     */
    public function with(callable $change): self
    {
        return new self($change($this->connection));
    }

    /**
     * Binds a request to this connection. Use it for a request this client has no method for,
     * or to keep the request object around.
     *
     * @template TRequest of MyDataRequest
     *
     * @param  TRequest  $request
     * @return TRequest
     */
    public function request(MyDataRequest $request): MyDataRequest
    {
        $this->lastRequest = $request;

        return $request->using($this->connection);
    }

    /**
     * The request handled last by this client, for its request and response XML.
     */
    public function lastRequest(): ?MyDataRequest
    {
        return $this->lastRequest;
    }

    /**
     * @param  InvoicesDoc|Invoice|list<Invoice>  $invoices
     *
     * @throws MyDataException
     */
    public function sendInvoices(InvoicesDoc|Invoice|array $invoices): ResponseDoc
    {
        return $this->request(new SendInvoices)->handle($invoices);
    }

    /**
     * @throws MyDataException
     */
    public function cancelInvoice(string|int $mark, string|int|null $entityVatNumber = null): ResponseDoc
    {
        return $this->request(new CancelInvoice)->handle($mark, $entityVatNumber);
    }

    /**
     * @param  IncomeClassificationsDoc|InvoiceIncomeClassification|list<InvoiceIncomeClassification>  $incomeClassifications
     *
     * @throws MyDataException
     */
    public function sendIncomeClassification(IncomeClassificationsDoc|InvoiceIncomeClassification|array $incomeClassifications, bool $postPerInvoice = false): ResponseDoc
    {
        return $this->request(new SendIncomeClassification)->handle($incomeClassifications, $postPerInvoice);
    }

    /**
     * @param  ExpensesClassificationsDoc|list<InvoiceExpensesClassification>  $expensesClassifications
     *
     * @throws MyDataException
     */
    public function sendExpensesClassification(ExpensesClassificationsDoc|array $expensesClassifications, bool $postPerInvoice = false): ResponseDoc
    {
        return $this->request(new SendExpensesClassification)->handle($expensesClassifications, $postPerInvoice);
    }

    /**
     * @param  PaymentMethodsDoc|PaymentMethod|list<PaymentMethod>  $paymentMethods
     *
     * @throws MyDataException
     */
    public function sendPaymentsMethod(PaymentMethodsDoc|PaymentMethod|array $paymentMethods): ResponseDoc
    {
        return $this->request(new SendPaymentsMethod)->handle($paymentMethods);
    }

    /**
     * @throws MyDataException
     */
    public function requestDocs(string $mark = '', ?string $dateFrom = null, ?string $dateTo = null, ?string $receiverVatNumber = null, ?string $entityVatNumber = null, InvoiceType|string|null $invType = null, ?string $maxMark = null, ?string $nextPartitionKey = null, ?string $nextRowKey = null): RequestedDoc
    {
        return $this->request(new RequestDocs)->handle($mark, $dateFrom, $dateTo, $receiverVatNumber, $entityVatNumber, $invType, $maxMark, $nextPartitionKey, $nextRowKey);
    }

    /**
     * @throws MyDataException
     */
    public function requestTransmittedDocs(string $mark = '', ?string $dateFrom = null, ?string $dateTo = null, ?string $receiverVatNumber = null, ?string $entityVatNumber = null, InvoiceType|string|null $invType = null, ?string $maxMark = null, ?string $nextPartitionKey = null, ?string $nextRowKey = null): RequestedDoc
    {
        return $this->request(new RequestTransmittedDocs)->handle($mark, $dateFrom, $dateTo, $receiverVatNumber, $entityVatNumber, $invType, $maxMark, $nextPartitionKey, $nextRowKey);
    }

    /**
     * The invoices this provider transmitted for an issuer, with a mark greater than $mark (provider channel).
     *
     * @throws MyDataException
     */
    public function requestProviderDocs(string $issuerVat, string|int $mark = 0, ?string $nextPartitionKey = null, ?string $nextRowKey = null): RequestedProviderDoc
    {
        return $this->request(new RequestProviderDocs)->handle($issuerVat, $mark, $nextPartitionKey, $nextRowKey);
    }

    /**
     * The providers an entity invoices through and the emails it accepts invoices at (provider channel).
     *
     * @throws MyDataException
     */
    public function requestReceiverInfo(string $vatNumber): ReceiverInfoDoc
    {
        return $this->request(new RequestReceiverInfo)->handle($vatNumber);
    }

    /**
     * The providers signatures of invoices under issue that were not issued (provider channel).
     *
     * @param  InvoicesDoc|Invoice|list<Invoice>  $invoices
     *
     * @throws MyDataException
     */
    public function sendUnsignedInvoices(InvoicesDoc|Invoice|array $invoices): ResponseDoc
    {
        return $this->request(new SendUnsignedInvoices)->handle($invoices);
    }

    /**
     * @throws MyDataException
     */
    public function requestMyIncome(string $dateFrom, string $dateTo, ?string $counterVatNumber = null, ?string $entityVatNumber = null, InvoiceType|string|null $invType = null, ?string $nextPartitionKey = null, ?string $nextRowKey = null): RequestedBookInfo
    {
        return $this->request(new RequestMyIncome)->handle($dateFrom, $dateTo, $counterVatNumber, $entityVatNumber, $invType, $nextPartitionKey, $nextRowKey);
    }

    /**
     * @throws MyDataException
     */
    public function requestMyExpenses(string $dateFrom, string $dateTo, ?string $counterVatNumber = null, ?string $entityVatNumber = null, InvoiceType|string|null $invType = null, ?string $nextPartitionKey = null, ?string $nextRowKey = null): RequestedBookInfo
    {
        return $this->request(new RequestMyExpenses)->handle($dateFrom, $dateTo, $counterVatNumber, $entityVatNumber, $invType, $nextPartitionKey, $nextRowKey);
    }

    /**
     * @throws MyDataException
     */
    public function requestVatInfo(string $dateFrom, string $dateTo, ?string $entityVatNumber = null, bool $groupedPerDay = false, ?string $nextPartitionKey = null, ?string $nextRowKey = null): RequestedVatInfo
    {
        return $this->request(new RequestVatInfo)->handle($dateFrom, $dateTo, $entityVatNumber, $groupedPerDay, $nextPartitionKey, $nextRowKey);
    }

    /**
     * @throws MyDataException
     */
    public function requestE3Info(string $dateFrom, string $dateTo, ?string $entityVatNumber = null, bool $groupedPerDay = false, ?string $nextPartitionKey = null, ?string $nextRowKey = null): RequestedE3Info
    {
        return $this->request(new RequestE3Info)->handle($dateFrom, $dateTo, $entityVatNumber, $groupedPerDay, $nextPartitionKey, $nextRowKey);
    }

    /**
     * @throws MyDataException
     */
    public function cancelDeliveryNote(string|int $mark, string|int|null $entityVatNumber = null): ResponseDoc
    {
        return $this->request(new CancelDeliveryNote)->handle($mark, $entityVatNumber);
    }

    /**
     * @throws MyDataException
     */
    public function cancelReceivingNote(string|int $mark, string|int|null $entityVatNumber = null): ResponseDoc
    {
        return $this->request(new CancelReceivingNote)->handle($mark, $entityVatNumber);
    }

    /**
     * @throws MyDataException
     */
    public function registerTransfer(Transport $transport): DeliveryResponseDoc
    {
        return $this->request(new RegisterTransfer)->handle($transport);
    }

    /**
     * @throws MyDataException
     */
    public function confirmDeliveryOutcome(DeliveryOutcome $deliveryOutcome): DeliveryResponseDoc
    {
        return $this->request(new ConfirmDeliveryOutcome)->handle($deliveryOutcome);
    }

    /**
     * @throws MyDataException
     */
    public function confirmDeliveryReturn(DeliveryReturn $deliveryReturn): DeliveryResponseDoc
    {
        return $this->request(new ConfirmDeliveryReturn)->handle($deliveryReturn);
    }

    /**
     * @throws MyDataException
     */
    public function rejectDeliveryNote(DeliveryRejection $rejection): DeliveryResponseDoc
    {
        return $this->request(new RejectDeliveryNote)->handle($rejection);
    }

    /**
     * @throws MyDataException
     */
    public function requestDeliveryNoteStatus(int $mark, ?string $issuerVatNumber = null): DeliveryNoteStatusResponse
    {
        return $this->request(new RequestDeliveryNoteStatus)->handle($mark, $issuerVatNumber);
    }

    /**
     * @throws MyDataException
     */
    public function requestDeliveryNoteStatusByQrUrl(string $qrUrl): DeliveryNoteStatusResponse
    {
        return $this->request(new RequestDeliveryNoteStatus)->handleUsingQrUrl($qrUrl);
    }

    /**
     * @throws MyDataException
     */
    public function generateGroupQrCode(GroupQrCode $groupQrCode): GroupQrCodeResponse
    {
        return $this->request(new GenerateGroupQrCode)->handle($groupQrCode);
    }

    /**
     * @throws MyDataException
     */
    public function requestGroupQrDetails(string $groupId): GroupQrDetailsResponse
    {
        return $this->request(new RequestGroupQrDetails)->handle($groupId);
    }

    /**
     * @throws MyDataException
     */
    public function sendStatement(StatementDoc|Statement $statement): StatementResponseDoc
    {
        return $this->request(new SendStatement)->handle($statement);
    }

    /**
     * @throws MyDataException
     */
    public function requestStatements(string $stmtId = '', ?string $maxstmtId = null, ?string $entityVatNumber = null): RequestedStatementDoc
    {
        return $this->request(new RequestStatements)->handle($stmtId, $maxstmtId, $entityVatNumber);
    }

    /**
     * @throws MyDataException
     */
    public function recallStatement(string $statementId, string $entityVatNumber, RecallStatus|int $recallStatus, ?string $recallDate = null): StatementResponseDoc
    {
        return $this->request(new RecallStatement)->handle($statementId, $entityVatNumber, $recallStatus, $recallDate);
    }
}
