<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Exceptions\UnsupportedChannelException;
use Firebed\AadeMyData\Http\Traits\HasResponseDom;
use Firebed\AadeMyData\Models\ProviderInvoice;
use Firebed\AadeMyData\Models\RequestedProviderDoc;
use Firebed\AadeMyData\Xml\RequestedProviderDocReader;
use Generator;

/**
 * The invoices the provider transmitted for one issuer: the provider channel's
 * RequestTransmittedDocs (myDataProvider/RequestTransmittedDocs). It answers with each
 * invoice's mark, uid and authentication code, not the invoices themselves, which a
 * provider cannot read without the issuer's own credentials.
 *
 * myDATA lists an invoice only once it has stored it, which can be hours after the
 * SendInvoices that registered it answered: an invoice missing here may still be on its way.
 * Invoices sent with SendUnsignedInvoices are not listed. On the sandbox, mark 0 listed every
 * invoice, while a mark with fewer digits than the issuer's marks (8 instead of 15) listed none:
 * pass 0 or a mark myDATA gave.
 */
class RequestProviderDocs extends MyDataRequest
{
    use HasResponseDom;

    protected const ?Channel CHANNEL = Channel::Provider;

    protected string $action = 'RequestTransmittedDocs';

    /**
     * Με αυτή την μέθοδο ο πάροχος λαμβάνει πληροφορίες για τις συνόψεις των παραστατικών
     * που είχε διαβιβάσει για μια οντότητα, με ΜΑΡΚ μεγαλύτερο της παραμέτρου.
     *
     * @param  string  $issuerVat  ΑΦΜ οντότητας (εκδότη)
     * @param  string|int  $mark  Μοναδικός αριθμός καταχώρησης: επιστρέφονται όσα έχουν μεγαλύτερο ΜΑΡΚ
     * @param  string|null  $nextPartitionKey  Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     * @param  string|null  $nextRowKey  Παράμετρος για την τμηματική λήψη των αποτελεσμάτων
     *
     * @throws UnsupportedChannelException When the connection is not the provider channel
     * @throws MyDataException
     */
    public function handle(string $issuerVat, string|int $mark = 0, ?string $nextPartitionKey = null, ?string $nextRowKey = null): RequestedProviderDoc
    {
        $query = ['issuervat' => $issuerVat, 'mark' => $mark] + $this->filterArray(compact('nextPartitionKey', 'nextRowKey'));

        return $this->read($this->get($query), new RequestedProviderDocReader);
    }

    /**
     * Every page of the results, following the continuation token. Pass $nextPartitionKey
     * and $nextRowKey to resume from a page's continuation token.
     *
     * @return Generator<int, RequestedProviderDoc>
     *
     * @throws MyDataException
     */
    public function pages(string $issuerVat, string|int $mark = 0, ?string $nextPartitionKey = null, ?string $nextRowKey = null): Generator
    {
        return $this->paginate(fn (?string $npk, ?string $nrk) => $this->handle($issuerVat, $mark, $npk, $nrk), $nextPartitionKey, $nextRowKey);
    }

    /**
     * Every invoice of every page, see pages().
     *
     * @return Generator<int, ProviderInvoice>
     *
     * @throws MyDataException
     */
    public function invoices(string $issuerVat, string|int $mark = 0, ?string $nextPartitionKey = null, ?string $nextRowKey = null): Generator
    {
        foreach ($this->pages($issuerVat, $mark, $nextPartitionKey, $nextRowKey) as $page) {
            yield from $page->getInvoices();
        }
    }
}
