<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http\DigitalGoodsMovement;

use Firebed\AadeMyData\Exceptions\MyDataAuthenticationException;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\MyDataXmlRequest;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryOutcome;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\ResponseDoc;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\DeliveryOutcomeWriter;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\ResponseDocReader;

/**
 * @version 2.0.1
 */
class ConfirmDeliveryOutcome extends MyDataXmlRequest
{
    protected const ?string SCHEMA = 'ConfirmDeliveryOutcome';

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

        return $this->request(new DeliveryOutcomeWriter, new ResponseDocReader, $deliveryOutcome);
    }
}
