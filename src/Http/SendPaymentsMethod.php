<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Enums\PaymentMethod;
use Firebed\AadeMyData\Http\Traits\HasRequestXML;
use Firebed\AadeMyData\Http\Traits\HasResponseXML;
use Firebed\AadeMyData\Models\PaymentMethodsDoc;
use Firebed\AadeMyData\Models\ResponseDoc;
use Firebed\AadeMyData\Xml\PaymentMethodsDocReader;
use Firebed\AadeMyData\Xml\ResponseDocReader;
use GuzzleHttp\Exception\GuzzleException;

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
     * @param PaymentMethodsDoc|PaymentMethod[] $paymentMethods
     * @return ResponseDoc
     * @throws GuzzleException
     */
    public function handle(PaymentMethodsDoc|array $paymentMethods): ResponseDoc
    {
        $paymentMethodsDoc = $this->getPaymentMethodsDoc($paymentMethods);

        $this->requestXML = (new PaymentMethodsDocReader())->asXML($paymentMethodsDoc);
        
        $response = $this->post(body: $this->requestXML);
        $this->responseXML = $response->getBody()->getContents();

        return (new ResponseDocReader())->parseXML($this->responseXML);
    }

    private function getPaymentMethodsDoc(PaymentMethodsDoc|array $paymentMethods): PaymentMethodsDoc
    {
        if ($paymentMethods instanceof PaymentMethodsDoc) {
            return $paymentMethods;
        }

        $paymentMethodsDoc = new PaymentMethodsDoc();
        $paymentMethodsDoc->setAttributes($paymentMethods);
        
        return $paymentMethodsDoc;
    }
}