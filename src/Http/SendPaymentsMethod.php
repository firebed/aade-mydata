<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Models\PaymentMethod;
use Firebed\AadeMyData\Models\PaymentMethodsDoc;
use Firebed\AadeMyData\Models\ResponseDoc;
use Firebed\AadeMyData\Xml\PaymentMethodsDocWriter;
use Firebed\AadeMyData\Xml\ResponseDocReader;

/**
 * Για ετεροχρονισμένες συναλλαγές, κατά τις οποίες η έκδοση των παραστατικών
 * διενεργείται σε χρόνο προγενέστερο της πληρωμής τους.
 *
 * @version 1.0.8
 */
class SendPaymentsMethod extends MyDataXmlRequest
{
    private ?PaymentMethodsDoc $paymentMethodsDoc = null;

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
    public function handle(PaymentMethodsDoc|PaymentMethod|array $paymentMethods): ResponseDoc
    {
        if (! $paymentMethods instanceof PaymentMethodsDoc) {
            $paymentMethods = new PaymentMethodsDoc($paymentMethods);
        }

        $this->paymentMethodsDoc = $paymentMethods;

        return $this->request(new PaymentMethodsDocWriter(), new ResponseDocReader(), $paymentMethods);
    }

    /**
     * The payment methods handed to handle(), as models. A Gateway that routes through an
     * e-invoicing provider reads them here instead of re-parsing the request XML.
     */
    public function getPaymentMethodsDoc(): ?PaymentMethodsDoc
    {
        return $this->paymentMethodsDoc;
    }
}