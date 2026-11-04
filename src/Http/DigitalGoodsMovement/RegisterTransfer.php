<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http\DigitalGoodsMovement;

use Firebed\AadeMyData\Exceptions\MyDataAuthenticationException;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\Channel;
use Firebed\AadeMyData\Http\MyDataXmlRequest;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\ResponseDoc;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\Transport;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\ResponseDocReader;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\TransportWriter;

class RegisterTransfer extends MyDataXmlRequest
{
    protected const ?Channel CHANNEL = Channel::Erp;

    protected const ?string SCHEMA = 'RegisterTransfer';

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
        return $this->request(new TransportWriter, new ResponseDocReader, $transport);
    }
}
