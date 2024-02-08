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

    /**
     * @return float|null Ποσό φιλοδωρήματος
     * @version 1.0.8
     */
    public function getTipAmount(): ?float
    {
        return $this->get('tipAmount');
    }

    /**
     * <ul>
     * <li>Ελάχιστη τιμή = 0</li>
     * <li>Δεκαδικά ψηφία = 2</li>
     * </ul>
     *
     * @param float|null $tipAmount Ποσό φιλοδωρήματος
     * @version 1.0.8
     */
    public function setTipAmount(?float $tipAmount): void
    {
        $this->put('tipAmount', $tipAmount);
    }

    /**
     * @return string|null Μοναδική Ταυτότητα Πληρωμής
     * @version 1.0.8
     */
    public function getTransactionId(): ?string
    {
        return $this->get('transactionId');
    }

    /**
     * Το πεδίο transactionId διαβιβάζεται στην περίπτωση πληρωμών με type = 7
     *
     * @param string|null $transactionId Μοναδική Ταυτότητα Πληρωμής
     * @version 1.0.8
     */
    public function setTransactionId(?string $transactionId): void
    {
        $this->put('transactionId', $transactionId);
    }

    /**
     * Το πεδίο ProvidersSignature διαβιβάζεται στην περίπτωση
     * πληρωμών με type = 7 και όταν η διαβίβαση γίνεται από το κανάλι του παρόχου
     *
     * @param ProvidersSignature|null $providersSignature Υπογραφή Πληρωμής Παρόχου
     * @version 1.0.8
     */
    public function setProvidersSignature(?ProvidersSignature $providersSignature): void
    {
        $this->put('ProvidersSignature', $providersSignature);
    }

    /**
     * @return ProvidersSignature|null Υπογραφή Πληρωμής Παρόχου
     * @version 1.0.8
     */
    public function getProvidersSignature(): ?ProvidersSignature
    {
        return $this->get('ProvidersSignature');
    }

    /**
     * Το πεδίο ECRToken διαβιβάζεται στην περίπτωση πληρωμών με type = 7 και
     * όταν η διαβίβαση γίνεται από ERP.
     *
     * @param ECRToken|null $ecrToken Υπογραφή Πληρωμής ΦΗΜ με σύστημα λογισμικού (ERP)
     * @version 1.0.8
     */
    public function setECRToken(?ECRToken $ecrToken): void
    {
        $this->put('ECRToken', $ecrToken);
    }

    /**
     * @return ECRToken|null Υπογραφή Πληρωμής ΦΗΜ με σύστημα λογισμικού (ERP)
     * @version 1.0.8
     */
    public function getECRToken(): ?ECRToken
    {
        return $this->get('ECRToken');
    }
}