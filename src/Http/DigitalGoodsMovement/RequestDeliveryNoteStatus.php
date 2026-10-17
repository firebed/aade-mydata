<?php

namespace Firebed\AadeMyData\Http\DigitalGoodsMovement;

use Firebed\AadeMyData\Exceptions\MyDataAuthenticationException;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\Traits\HasResponseDom;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryNoteStatusResponse;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\DeliveryNoteStatusResponseReader;

class RequestDeliveryNoteStatus extends MyDataRequest
{
    use HasResponseDom;

    protected string $action = 'GetDeliveryNoteStatus';

    /**
     * Αναζήτηση κατάστασης δελτίου με βάση το MARK.
     *
     * @throws MyDataAuthenticationException
     * @throws MyDataException
     */
    public function handle(int $mark, ?string $issuerVatNumber = null): DeliveryNoteStatusResponse
    {
        $this->ensureERP();

        return $this->request($this->filterArray([
            'mark' => $mark,
            'issuerVatNumber' => $issuerVatNumber,
        ]));
    }

    /**
     * Αναζήτηση κατάστασης δελτίου με βάση το URL του QR code (εναλλακτικά του MARK).
     * Το QR code προσδιορίζει το δελτίο, οπότε το issuerVatNumber δεν επιτρέπεται εδώ
     * (§3.2.4: myDATA απαντά HTTP 400 όταν δίνεται μαζί με το qrUrl).
     *
     * @param string|null $issuerVatNumber Deprecated and ignored, kept so existing calls keep working.
     *
     * @throws MyDataAuthenticationException
     * @throws MyDataException
     *
     * @version 2.0.2
     */
    public function handleUsingQrUrl(string $qrUrl, ?string $issuerVatNumber = null): DeliveryNoteStatusResponse
    {
        $this->ensureERP();

        // myDATA hands out QR URLs already percent-encoded (…q=POiz…%2f…%3d), while the
        // query string gets encoded on the way out. Decode once so it isn't encoded twice.
        return $this->request(['qrUrl' => rawurldecode($qrUrl)]);
    }

    /**
     * @throws MyDataException
     */
    private function request(array $query): DeliveryNoteStatusResponse
    {
        return $this->read($this->get($query), new DeliveryNoteStatusResponseReader());
    }
}