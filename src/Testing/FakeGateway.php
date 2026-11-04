<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Testing;

use Closure;
use Error as PhpError;
use Firebed\AadeMyData\Http\CancelInvoice;
use Firebed\AadeMyData\Http\Gateway;
use Firebed\AadeMyData\Http\MyDataGetRequest;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\RequestBookInfo;
use Firebed\AadeMyData\Http\RequestE3Info;
use Firebed\AadeMyData\Http\RequestProviderDocs;
use Firebed\AadeMyData\Http\RequestReceiverInfo;
use Firebed\AadeMyData\Http\RequestVatInfo;
use Firebed\AadeMyData\Http\SendExpensesClassification;
use Firebed\AadeMyData\Http\SendIncomeClassification;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Http\SendPaymentsMethod;
use Firebed\AadeMyData\Http\SendUnsignedInvoices;
use Firebed\AadeMyData\Models\Error;
use Firebed\AadeMyData\Models\Errors;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoicesDoc;
use Firebed\AadeMyData\Models\Model;
use Firebed\AadeMyData\Models\RequestedDoc;
use Firebed\AadeMyData\Models\Response;
use Firebed\AadeMyData\Models\ResponseDoc;
use Firebed\AadeMyData\Xml\ModelWriter;
use LogicException;
use PHPUnit\Framework\Assert;
use Throwable;

/**
 * A gateway that answers requests with fake responses and records them, so tests run
 * without reaching myDATA. Install it with MyData::fake(); it then carries every
 * request, whatever connection it uses.
 *
 * Responses are given per request class (the request's own class first, then a parent
 * class in the order given, then '*' for any request), as:
 * - a response XML string,
 * - a model: a ResponseDoc, a Response (wrapped in a ResponseDoc), a RequestedDoc, …,
 * - a list of Response models (a ResponseDoc) or of Invoice models (a RequestedDoc),
 * - an exception to throw (e.g. new RateLimitExceededException(retryAfter: 300)),
 * - a closure that receives the request and the RecordedRequest and returns one of the above,
 * - a sequence of the above, one per request (FakeGateway::sequence()).
 *
 * Without a response, the requests that send documents and the cancellations succeed,
 * with a mark per document numbered from 400000000000001, and the searches return
 * nothing. The other requests need a response. In a ResponseDoc, a response without an
 * index gets its position, and a successful response without a mark gets the next one.
 */
final class FakeGateway implements Gateway
{
    /**
     * The mark a successful response carries, by request class (a parent class matches its children).
     *
     * @var array<class-string<MyDataRequest>, string>
     */
    private const array MARKS = [
        SendInvoices::class => 'invoiceMark',
        SendIncomeClassification::class => 'classificationMark',
        SendExpensesClassification::class => 'classificationMark',
        SendPaymentsMethod::class => 'paymentMethodMark',
        CancelInvoice::class => 'cancellationMark', // and the delivery and receiving notes
    ];

    /**
     * What a search finds when given no response: nothing.
     *
     * @var array<class-string<MyDataRequest>, string>
     */
    private const array NOTHING_FOUND = [
        MyDataGetRequest::class => '<RequestedDoc/>', // RequestDocs, RequestTransmittedDocs
        RequestBookInfo::class => '<RequestedBookInfo/>',
        RequestVatInfo::class => '<RequestedVatInfo/>',
        RequestE3Info::class => '<RequestedE3Info/>',
        RequestProviderDocs::class => '<RequestedProviderDoc/>',
        RequestReceiverInfo::class => '<ReceiverInfoDoc/>',
    ];

    /** @var array<class-string<MyDataRequest>|'*', mixed> Responses by request class */
    private array $responses = [];

    /** @var list<RecordedRequest> */
    private array $recorded = [];

    private int $mark = 400_000_000_000_000;

    /**
     * @param  array<class-string<MyDataRequest>|'*', mixed>  $responses  Responses by request class, e.g. [SendInvoices::class => $responseDoc]
     */
    public function __construct(array $responses = [])
    {
        foreach ($responses as $request => $response) {
            $this->respond($request, $response);
        }
    }

    /**
     * Answer the requests of this class (or of its subclasses; '*' for any request) with the response.
     *
     * @param  class-string<MyDataRequest>|'*'  $request
     */
    public function respond(string $request, mixed $response): self
    {
        $this->responses[$request] = $response;

        return $this;
    }

    /**
     * One response per request, in order.
     */
    public static function sequence(mixed ...$responses): ResponseSequence
    {
        return new ResponseSequence(array_values($responses));
    }

    /**
     * A successful response, with the given marks or the fake's next mark.
     */
    public static function accepted(string|int|null $mark = null, ?string $uid = null): Response
    {
        return new Response(array_filter(['statusCode' => 'Success', 'invoiceUid' => $uid, 'invoiceMark' => $mark === null ? null : (string) $mark]));
    }

    /**
     * A response with an error, as myDATA rejects a document.
     */
    public static function rejected(string|int $code, string $message, string $statusCode = 'ValidationError'): Response
    {
        return new Response([
            'statusCode' => $statusCode,
            'errors' => new Errors([new Error(['message' => $message, 'code' => (string) $code])]),
        ]);
    }

    public function get(MyDataRequest $request, array $query): string
    {
        return $this->handle(new RecordedRequest($request, 'GET', $query, null));
    }

    public function post(MyDataRequest $request, ?array $query = null, ?string $body = null): string
    {
        return $this->handle(new RecordedRequest($request, 'POST', $query ?? [], $body));
    }

    /**
     * The recorded requests of a request class (all of them by default) that pass the filter.
     *
     * @template TRequest of MyDataRequest
     *
     * @param  class-string<TRequest>  $request
     * @param  (Closure(TRequest, RecordedRequest): bool)|null  $filter
     * @return list<RecordedRequest>
     */
    public function recorded(string $request = MyDataRequest::class, ?Closure $filter = null): array
    {
        $matches = [];

        foreach ($this->recorded as $recorded) {
            $sent = $recorded->request;

            if ($sent instanceof $request && ($filter === null || $filter($sent, $recorded))) {
                $matches[] = $recorded;
            }
        }

        return $matches;
    }

    /**
     * @template TRequest of MyDataRequest
     *
     * @param  class-string<TRequest>  $request
     * @param  (Closure(TRequest, RecordedRequest): bool)|null  $filter
     */
    public function assertSent(string $request, ?Closure $filter = null): void
    {
        Assert::assertNotEmpty($this->recorded($request, $filter), "No $request request matching the expectation was sent.");
    }

    /**
     * @template TRequest of MyDataRequest
     *
     * @param  class-string<TRequest>  $request
     * @param  (Closure(TRequest, RecordedRequest): bool)|null  $filter
     */
    public function assertNotSent(string $request, ?Closure $filter = null): void
    {
        Assert::assertEmpty($this->recorded($request, $filter), "An unexpected $request request was sent.");
    }

    /**
     * @param  class-string<MyDataRequest>  $request
     */
    public function assertSentCount(int $count, string $request = MyDataRequest::class): void
    {
        Assert::assertCount($count, $this->recorded($request), "The number of $request requests sent does not match.");
    }

    public function assertNothingSent(): void
    {
        Assert::assertSame([], $this->recorded, 'Requests were sent: '.implode(', ', array_map(fn (RecordedRequest $r) => $r->action(), $this->recorded)).'.');
    }

    private function handle(RecordedRequest $recorded): string
    {
        $this->recorded[] = $recorded;

        return $this->resolve($this->responseFor($recorded->request), $recorded);
    }

    private function responseFor(MyDataRequest $request): mixed
    {
        return $this->responses[$request::class] ?? self::lookup($this->responses, $request) ?? $this->responses['*'] ?? null;
    }

    /**
     * The value of the first class in the map the request is an instance of.
     *
     * @template TValue
     *
     * @param  array<string, TValue>  $map
     * @return TValue|null
     */
    private static function lookup(array $map, MyDataRequest $request): mixed
    {
        foreach ($map as $class => $value) {
            if ($class !== '*' && $request instanceof $class) {
                return $value;
            }
        }

        return null;
    }

    private function resolve(mixed $response, RecordedRequest $recorded): string
    {
        while ($response instanceof ResponseSequence || $response instanceof Closure) {
            $response = $response instanceof Closure ? $response($recorded->request, $recorded) : $response->next();
        }

        if (is_array($response) && array_is_list($response)) {
            $response = match (true) {
                $response === [] => null,
                $response[0] instanceof Invoice => new RequestedDoc(['invoicesDoc' => new InvoicesDoc($response)]),
                default => new ResponseDoc($response),
            };
        }

        if ($response instanceof Response) {
            $response = new ResponseDoc([$response]);
        }

        if ($response instanceof ResponseDoc) {
            // Completed on copies: a registered response serves every request afresh
            $response = new ResponseDoc(array_map(fn (Response $r) => clone $r, $response->all()));
            $this->complete($response, $recorded);
        }

        return match (true) {
            $response instanceof Throwable => throw $response,
            is_string($response) => $response,
            $response instanceof Model => ModelWriter::write($response),
            $response === null => $this->default($recorded),
            default => throw new LogicException('A fake response must be an XML string, a model, a list of responses or invoices, an exception, a closure or a sequence; got '.get_debug_type($response).'.'),
        };
    }

    /**
     * The response of a request that was given none: success for the requests that send
     * documents and the cancellations, nothing found for the searches.
     */
    private function default(RecordedRequest $recorded): string
    {
        $request = $recorded->request;

        if (self::lookup(self::MARKS, $request) !== null) {
            // One success per document sent; a cancellation sends none and gets one
            return $this->resolve(array_map(fn () => self::accepted(), range(1, max(1, $recorded->count()))), $recorded);
        }

        return self::lookup(self::NOTHING_FOUND, $request)
            ?? throw new LogicException("No fake response for {$request->action()}: pass one to MyData::fake([".$request::class.'::class => …]).');
    }

    /**
     * Numbers the responses and gives every successful one without a mark the next mark
     * of the kind the request returns, plus the uid of the invoice it answers.
     */
    private function complete(ResponseDoc $doc, RecordedRequest $recorded): void
    {
        $request = $recorded->request;
        $markKey = self::lookup(self::MARKS, $request);
        $invoices = $request instanceof SendInvoices ? $request->getInvoicesDoc()?->all() ?? [] : [];

        foreach ($doc->all() as $i => $response) {
            if (! $response->has('index')) {
                $response->setIndex($i + 1);
            }

            // An invoice under issue that was not issued gets no mark, only its uid
            if ($request instanceof SendUnsignedInvoices) {
                if ($response->isSuccessful() && ! $response->has('invoiceUid')) {
                    $response->set('invoiceUid', self::uid($invoices[$i] ?? null));
                }

                continue;
            }

            if ($markKey === null || ! $response->isSuccessful() || $response->has($markKey)) {
                continue;
            }

            $mark = (string) ++$this->mark;
            $response->set($markKey, $mark);

            if ($request instanceof SendInvoices) {
                $response->set('invoiceUid', $response->getInvoiceUid() ?? self::uid($invoices[$i] ?? null))->setAuthenticationCode(strtoupper(sha1($mark)));
            }
        }
    }

    /**
     * The uid myDATA gives the invoice, when the invoice has what it is computed from.
     */
    private static function uid(?Invoice $invoice): ?string
    {
        try {
            return $invoice?->guessUid();
        } catch (PhpError) {
            return null;
        }
    }
}
