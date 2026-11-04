<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http\DigitalGoodsMovement;

use Firebed\AadeMyData\Exceptions\MyDataAuthenticationException;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\MyDataXmlRequest;
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
class ConfirmDeliveryReturn extends MyDataXmlRequest
{
    protected const ?string SCHEMA = 'ConfirmDeliveryReturn';

    /**
     * @throws MyDataAuthenticationException
     * @throws MyDataException
     *
     * @version 2.0.2
     */
    public function handle(DeliveryReturn $deliveryReturn): ResponseDoc
    {
        $this->ensureERP();

        return $this->request(new DeliveryReturnWriter, new ResponseDocReader, $deliveryReturn);
    }
}
