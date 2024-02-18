<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\Traits\HasRequestXML;
use Firebed\AadeMyData\Http\Traits\HasResponseXML;
use Firebed\AadeMyData\Models\PaymentMethod;
use Firebed\AadeMyData\Models\ResponseDoc;
use Firebed\AadeMyData\Xml\PaymentMethodsDocWriter;
use Firebed\AadeMyData\Xml\ResponseDocReader;

/**
 * Για ετεροχρονισμένες συναλλαγές, κατά τις οποίες η έκδοση των παραστατικών
 * διενεργείται σε χρόνο προγενέστερο της πληρωμής τους.
 *
 * @version 1.0.8
 */
class SendPaymentsMethod extends MyDataRequest
{
    use HasRequestXML;
    use HasResponseXML;

    /**
     * <ol>
     * <li>Κατά τη χρήση της μεθόδου, τουλάχιστον ένα αντικείμενο
     * PaymentMethodDetail ανά παραστατικό πρέπει να είναι τύπου POS.</li>
     * </ol>
     *
     * @param PaymentMethod|PaymentMethod[] $paymentMethods
     * @return ResponseDoc
     * @throws MyDataException
     */
    public function handle(PaymentMethod|array $paymentMethods): ResponseDoc
    {
        $writer = new PaymentMethodsDocWriter();
        $this->requestXML = $writer->asXML(is_array($paymentMethods) ? $paymentMethods : [$paymentMethods]);
        
        $response = $this->post(body: $this->requestXML);
        $this->responseXML = $response->getBody()->getContents();
        
        $reader = new ResponseDocReader();
        return $reader->parseXML($this->responseXML);
    }
}