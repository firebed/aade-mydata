<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Enums\PaymentMethod;

class PaymentMethodDetail extends Type
{
    /**
     * @return string|null Τύπος Πληρωμής
     */
    public function getType(): ?string
    {
        return $this->get('type');
    }

    /**
     * @param PaymentMethod|string $type Τύπος Πληρωμής
     */
    public function setType(PaymentMethod|string $type): void
    {
        $this->put('type', $type);
    }

    /**
     * @return float|null Ποσό Πληρωμής
     */
    public function getAmount(): ?float
    {
        return $this->get('amount');
    }

    /**
     * Το πεδίο amount μπορεί να αντιστοιχεί σε ένα τμήμα της συνολικής
     * αξίας του παραστατικού.
     * 
     * <ul>
     * <li>Ελάχιστη τιμή = 0</li>
     * <li>Δεκαδικά ψηφία = 2</li>
     * </ul>
     * 
     * @param float $amount Ποσό Πληρωμής
     */
    public function setAmount(float $amount): void
    {
        $this->put('amount', $amount);
    }

    /**
     * @return string|null Πληροφορίες γραμμής
     */
    public function getPaymentMethodInfo(): ?string
    {
        return $this->get('paymentMethodInfo');
    }

    /**
     * Το πεδίο Πληροφορίες μπορεί να περιέχει επιπλέον πληροφορίες σχετικά με
     * τον συγκεκριμένο τύπο (πχ Αρ. Λογαριασμού Τραπέζης)
     * 
     * @param string $paymentMethodInfo Πληροφορίες γραμμής
     */
    public function setPaymentMethodInfo(string $paymentMethodInfo): void
    {
        $this->put('paymentMethodInfo', $paymentMethodInfo);
    }
}