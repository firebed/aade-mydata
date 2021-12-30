<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Models\Enums\PaymentMethods;

class PaymentMethodDetailType extends Type
{
    /**
     * @return int Τύπος Πληρωμής
     */
    public function getType(): int
    {
        return $this->get('type');
    }

    /**
     * <h2>Τύπος Πληρωμής</h2>
     *
     * <p>Οι τιμές του πεδίου type περιγράφονται σε αντίστοιχο πίνακα του παραρτήματος.</p>
     *
     * @param int $type Ελάχιστη τιμή = 1, Μέγιστη τιμή = 5
     * @see PaymentMethods
     */
    public function setType(int $type): self
    {
        return $this->put('type', $type);
    }

    /**
     * @return float Ποσό Πληρωμής
     */
    public function getAmount(): float
    {
        return $this->get('amount');
    }

    /**
     * <h2>Ποσό Πληρωμής</h2>
     *
     * <p>Το πεδίο amount μπορεί να αντιστοιχεί σε ένα τμήμα της συνολικής αξίας του
     * παραστατικού</p>
     *
     * @param float $amount Ελάχιστη τιμή = 0, Δεκαδικά ψηφία = 2
     */
    public function setAmount(float $amount): self
    {
        return $this->put('amount', number_format($amount, 2, '.', ''));
    }

    public function getPaymentMethodInfo(): ?string
    {
        return $this->get('paymentMethodInfo');
    }

    /**
     * <h2>Πληροφορίες γραμμής</h2>
     *
     * <p>Το πεδίο Πληροφορίες μπορεί να περιέχει επιπλέον πληροφορίες σχετικά με τον
     * συγκεκριμένο τύπο (πχ Αρ. Λογαριασμού Τραπέζης)</p>
     *
     * @param string $paymentMethodInfo Πληροφορίες γραμμής
     */
    public function setPaymentMethodInfo(string $paymentMethodInfo): self
    {
        return $this->put('paymentMethod', $paymentMethodInfo);
    }
}