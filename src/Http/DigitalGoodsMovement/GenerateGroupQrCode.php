<?php

namespace Firebed\AadeMyData\Http\DigitalGoodsMovement;

use Firebed\AadeMyData\Exceptions\MyDataAuthenticationException;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\Traits\HasRequestDom;
use Firebed\AadeMyData\Http\Traits\HasResponseDom;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\GroupQrCode;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\GroupQrCodeResponse;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\GroupQrCodeReader;
use Firebed\AadeMyData\Xml\DigitalGoodsMovement\GroupQrCodeWriter;

/**
 * @version 2.0.1
 */
class GenerateGroupQrCode extends MyDataRequest
{
    use HasRequestDom;
    use HasResponseDom;

    protected string $action = 'GenerateGroupQRCode';

    /**
     * 1. Η μέθοδος μπορεί να κληθεί από οποιονδήποτε εξουσιοδοτημένο χρήστη (εκδότη ή
     * μεταφορέα).
     * 2. Απαιτούνται τουλάχιστον 2 qrUrl για τη δημιουργία ομάδας.
     * 3. Η απόκριση περιέχει το groupQrUrl, το οποίο μπορεί να χρησιμοποιηθεί στις
     * μεθόδους RegisterTransfer, ConfirmDeliveryOutcome και RejectDeliveryNote για
     * την ταυτόχρονη ενημέρωση όλων των ΔΑ της ομάδας.
     * 4. Το groupQrUrl έχει περιορισμένη διάρκεια ισχύος, η οποία επιστρέφεται στο
     * πεδίο expiresAt.
     *
     * @throws MyDataAuthenticationException
     * @throws MyDataException
     *
     * @version 2.0.1
     */
    public function handle(GroupQrCode $groupQrCode): GroupQrCodeResponse
    {
        $this->ensureERP();

        $writer = new GroupQrCodeWriter();

        // Create the request XML
        $requestXML = $writer->asXML($groupQrCode);
        $this->requestDom = $writer->getDomDocument();

        // Get the response XML
        $responseXML = $this->post(body: $requestXML);

        $reader = new GroupQrCodeReader();

        // Parse the response XML
        $responseDoc = $reader->parseXML($responseXML);
        $this->responseDom = $reader->getDomDocument();

        return $responseDoc;
    }

    /**
     * Helper method to generate a group QR code from an array of string QR URLs.
     *
     * @param string[] $qrUrls
     * @return GroupQrCodeResponse
     * @throws MyDataAuthenticationException
     * @throws MyDataException
     */
    public function generateFromQrUrls(array $qrUrls): GroupQrCodeResponse
    {
        $group = new GroupQrCode();
        $group->setQrUrls($qrUrls);

        return $this->handle($group);
    }
}