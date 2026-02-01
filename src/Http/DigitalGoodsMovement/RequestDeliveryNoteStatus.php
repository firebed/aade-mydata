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
     * @throws MyDataAuthenticationException
     * @throws MyDataException
     */
    public function handle(int $mark, ?string $issuerVatNumber = null): DeliveryNoteStatusResponse
    {
        $this->ensureERP();

        $query = $this->filterArray([
            'mark' => $mark,
            'issuerVatNumber' => $issuerVatNumber,
        ]);

        $reader = new DeliveryNoteStatusResponseReader();
        $response = $reader->parseXml($this->get($query));

        $this->responseDom = $reader->getDomDocument();

        return $response;
    }
}