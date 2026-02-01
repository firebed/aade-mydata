<?php

namespace Firebed\AadeMyData\Http\DigitalGoodsMovement;

use Firebed\AadeMyData\Exceptions\MyDataAuthenticationException;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\Traits\HasRequestDom;
use Firebed\AadeMyData\Http\Traits\HasResponseDom;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\ResponseDoc;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\Transport;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\ResponseDocReader;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\TransportWriter;

class RegisterTransfer extends MyDataRequest
{
    use HasRequestDom;
    use HasResponseDom;

    /**
     * 1. Η μέθοδος καλείται από τον μεταφορέα για να δηλώσει την παραλαβή των αγαθών
     *    και την έναρξη της διακίνησης, ή την παραλαβή από προηγούμενο μεταφορέα (μεταφόρτωση).
     * 2. Με την επιτυχή κλήση, το Δελτίο Αποστολής μεταβαίνει σε κατάσταση InTransit.
     * 3. Σε περίπτωση επιτυχίας, η απόκριση περιέχει το `transferMark`, το οποίο είναι ο Μοναδικός Αριθμός Καταχώρησης του γεγονότος μεταφοράς.
     *
     * @throws MyDataAuthenticationException
     * @throws MyDataException
     *
     * @version 2.0.1
     */
    public function handle(Transport $transport): ResponseDoc
    {
        $this->ensureERP();

        $writer = new TransportWriter();

        // Create the request XML
        $requestXML = $writer->asXML($transport);
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