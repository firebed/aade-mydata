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
     *
     * @throws MyDataAuthenticationException
     * @throws MyDataException
     *
     * @version 2.0.2
     */
    public function handleUsingQrUrl(string $qrUrl, ?string $issuerVatNumber = null): DeliveryNoteStatusResponse
    {
        $this->ensureERP();

        return $this->request($this->filterArray([
            'qrUrl' => $qrUrl,
            'issuerVatNumber' => $issuerVatNumber,
        ]));
    }

    /**
     * @throws MyDataException
     */
    private function request(array $query): DeliveryNoteStatusResponse
    {
        $reader = new DeliveryNoteStatusResponseReader();
        $response = $reader->parseXml($this->get($query));

        $this->responseDom = $reader->getDomDocument();

        return $response;
    }
}