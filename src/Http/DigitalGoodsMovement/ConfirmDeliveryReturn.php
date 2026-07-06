<?php

namespace Firebed\AadeMyData\Http\DigitalGoodsMovement;

use Firebed\AadeMyData\Exceptions\MyDataAuthenticationException;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\Traits\HasRequestDom;
use Firebed\AadeMyData\Http\Traits\HasResponseDom;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryReturn;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\ResponseDoc;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\DeliveryReturnWriter;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\ResponseDocReader;

/**
 * Επιβεβαίωση - Δήλωση Επιστροφής Παραστατικού Διακίνησης από τον Εκδότη.
 *
 * Καλείται από τον Εκδότη του δελτίου για να δηλώσει ολοκλήρωση της διακίνησης
 * επί επιστροφής (ο μεταφορέας δεν παρέδωσε το σύνολο των αγαθών). Με την επιτυχή
 * κλήση το δελτίο μεταβαίνει σε κατάσταση Completed και επιστρέφεται το
 * deliveryReturnMark.
 *
 * @version 2.0.2
 */
class ConfirmDeliveryReturn extends MyDataRequest
{
    use HasRequestDom;
    use HasResponseDom;

    /**
     * @throws MyDataAuthenticationException
     * @throws MyDataException
     *
     * @version 2.0.2
     */
    public function handle(DeliveryReturn $deliveryReturn): ResponseDoc
    {
        $this->ensureERP();

        $writer = new DeliveryReturnWriter();

        // Create the request XML
        $requestXML = $writer->asXML($deliveryReturn);
        $this->requestDom = $writer->getDomDocument();

        // Get the response XML
        $responseXML = $this->post(body: $requestXML);

        $reader = new ResponseDocReader();

        // Parse the response XML
        $responseDoc = $reader->parseXML($responseXML);
        $this->responseDom = $reader->getDomDocument();

        return $responseDoc;
    }
}
