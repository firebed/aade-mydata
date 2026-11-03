<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http\DigitalGoodsMovement;

use Firebed\AadeMyData\Exceptions\MyDataAuthenticationException;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Exceptions\UnsupportedChannelException;
use Firebed\AadeMyData\Http\MyDataXmlRequest;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryRejection;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\ResponseDoc;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\DeliveryRejectionWriter;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\ResponseDocReader;

class RejectDeliveryNote extends MyDataXmlRequest
{
    /**
     * 1. Η μέθοδος καλείται αποκλειστικά από τον Λήπτη για να δηλώσει την ολική
     * απόρριψη των ειδών του Δελτίου Αποστολής.
     * 2. Με την επιτυχή κλήση, το Δελτίο Αποστολής μεταβαίνει στην τελική
     * κατάσταση Rejected.
     * 3. Σε περίπτωση επιτυχίας, η απόκριση περιέχει το rejectMark, το οποίο είναι ο
     * Μοναδικός Αριθμός Καταχώρησης του γεγονότος απόρριψης.
     * 4. Η κλήση της RejectDeliveryNote μπορεί εναλλακτικά να γίνει και με το ΜΑΡΚ του παραστατικού (αντί για το QR url)
     *
     * @throws MyDataAuthenticationException
     * @throws MyDataException
     * @throws UnsupportedChannelException If the request is not made through the ERP channel
     *
     * @version 2.0.1
     */
    public function handle(DeliveryRejection $rejection): ResponseDoc
    {
        $this->ensureERP();

        return $this->request(new DeliveryRejectionWriter, new ResponseDocReader, $rejection);
    }

    /**
     * @throws MyDataAuthenticationException
     * @throws MyDataException
     */
    public function rejectUsingMark(int $mark, ?string $rejectionReason = null): ResponseDoc
    {
        return $this->handle(new DeliveryRejection($mark, $rejectionReason));
    }

    /**
     * @throws MyDataAuthenticationException
     * @throws MyDataException
     */
    public function rejectUsingQrUrl(string $qrUrl, ?string $rejectionReason = null): ResponseDoc
    {
        return $this->handle(new DeliveryRejection($qrUrl, $rejectionReason));
    }
}
