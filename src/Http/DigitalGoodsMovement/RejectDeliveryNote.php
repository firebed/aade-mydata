<?php

namespace Firebed\AadeMyData\Http\DigitalGoodsMovement;

use Firebed\AadeMyData\Exceptions\MyDataAuthenticationException;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Exceptions\UnsupportedChannelException;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\Traits\HasRequestDom;
use Firebed\AadeMyData\Http\Traits\HasResponseDom;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryRejection;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\ResponseDoc;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\DeliveryRejectionWriter;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\ResponseDocReader;

class RejectDeliveryNote extends MyDataRequest
{
    use HasRequestDom;
    use HasResponseDom;

    /**
     * 1. Η μέθοδος καλείται αποκλειστικά από τον Λήπτη για να δηλώσει την ολική
     * απόρριψη των ειδών του Δελτίου Αποστολής.
     * 2. Με την επιτυχή κλήση, το Δελτίο Αποστολής μεταβαίνει στην τελική
     * κατάσταση Rejected.
     * 3. Σε περίπτωση επιτυχίας, η απόκριση περιέχει το rejectMark, το οποίο είναι ο
     * Μοναδικός Αριθμός Καταχώρησης του γεγονότος απόρριψης.
     * 4. Η κλήση της RejectDeliveryNote μπορεί εναλλακτικά να γίνει και με το ΜΑΡΚ του παραστατικού (αντί για το QR url)
     *
     * @param DeliveryRejection $rejection
     * @return ResponseDoc
     * @throws MyDataAuthenticationException
     * @throws MyDataException
     * @throws UnsupportedChannelException If the request is not made through the ERP channel
     *
     * @version 2.0.1
     */
    public function handle(DeliveryRejection $rejection): ResponseDoc
    {
        $this->ensureERP();

        $writer = new DeliveryRejectionWriter();

        // Create the request XML
        $requestXML = $writer->asXML($rejection);
        $this->requestDom = $writer->getDomDocument();

        // Get the response XML
        $responseXML = $this->post(body: $requestXML);

        $reader = new ResponseDocReader();

        // Parse the response XML
        $responseDoc = $reader->parseXML($responseXML);
        $this->responseDom = $reader->getDomDocument();

        return $responseDoc;
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