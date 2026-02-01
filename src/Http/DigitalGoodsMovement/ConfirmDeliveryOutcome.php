<?php

namespace Firebed\AadeMyData\Http\DigitalGoodsMovement;

use Firebed\AadeMyData\Exceptions\MyDataAuthenticationException;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\Traits\HasRequestDom;
use Firebed\AadeMyData\Http\Traits\HasResponseDom;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryOutcome;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\ResponseDoc;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\DeliveryOutcomeWriter;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\ResponseDocReader;

/**
 * @version 2.0.1
 */
class ConfirmDeliveryOutcome extends MyDataRequest
{
    use HasRequestDom;
    use HasResponseDom;

    /**
     * 1. Η μέθοδος καλείται είτε από τον Μεταφορέα για να δηλώσει το αποτέλεσμα της
     * παράδοσης, είτε από τον Λήπτη για να επιβεβαιώσει την παραλαβή.
     * 2. Αν κληθεί από Μεταφορέα σε B2B συναλλαγή, θέτει το ΔΑ σε
     * κατάσταση DeliveredByCarrier.
     * 3. Αν κληθεί από Μεταφορέα σε B2C συναλλαγή, θέτει το ΔΑ σε
     * κατάσταση Completed.
     * 4. Αν κληθεί από Λήπτη, θέτει το ΔΑ σε κατάσταση Completed.
     * 5. Η τιμή NONE για το πεδίο outcome θέτει το ΔΑ σε κατάσταση FailedDelivery.
     *
     * @throws MyDataAuthenticationException
     * @throws MyDataException
     *
     * @version 2.0.1
     */
    public function handle(DeliveryOutcome $deliveryOutcome): ResponseDoc
    {
        $this->ensureERP();

        $writer = new DeliveryOutcomeWriter();

        // Create the request XML
        $requestXML = $writer->asXML($deliveryOutcome);
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