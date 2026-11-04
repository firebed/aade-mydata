<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\Traits\HasResponseDom;
use Firebed\AadeMyData\Models\ResponseDoc;
use Firebed\AadeMyData\Xml\ResponseDocReader;

/**
 * A cancellation by mark: CancelInvoice, and on the provider channel CancelDeliveryNote and
 * CancelReceivingNote. They are different myDATA actions with the same parameters, so they
 * share this base instead of extending CancelInvoice.
 */
abstract class CancellationRequest extends MyDataRequest
{
    use HasResponseDom;

    /**
     * @param  string  $mark  Μοναδικός αριθμός καταχώρησης παραστατικού προς ακύρωση
     * @param  string|null  $entityVatNumber  ΑΦΜ οντότητας
     *
     * @throws MyDataException
     */
    public function handle(string|int $mark, string|int|null $entityVatNumber = null): ResponseDoc
    {
        $query = $this->filterArray(['mark' => $mark, 'entityVatNumber' => $entityVatNumber]);

        return $this->read($this->post($query), new ResponseDocReader);
    }
}
