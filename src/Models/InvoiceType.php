<?php

namespace Firebed\AadeMyData\Models;


class InvoiceType extends Type
{
    /**
     * @return string|null Αναγνωριστικό Παραστατικού
     */
    public function getUID(): ?string
    {
        return $this->get('uid');
    }

    /**
     * <h2>Αναγνωριστικό Παραστατικού</h2>
     *
     * <p>Το uid αποτελεί το αναγνωριστικό κάθε παραστατικού και συμπληρώνεται από την
     * Υπηρεσία. Υπολογίζεται από το SHA-1 hash των παρακάτω πεδίων του παραστατικού:
     * <ul>
     * <li>ΑΦΜ Eκδότη</li>
     * <li>Ημερομηνία Έκδοσης</li>
     * <li>Αριθμός Εγκατάστασης στο Μητρώο του Taxis</li>
     * <li>Τύπος Παραστατικού</li>
     * <li>Σειρά</li>
     * <li>ΑΑ</li>
     * </ul>
     * <p>Κατά τη χρήση του αλγόριθμου SHA-1 χρησιμοποιείται κωδικοποίηση ISO-8859-7.</p>
     *
     * @param string $uid Μήκος= 40
     */
    public function setUID(string $uid): self
    {
        return $this->put('uid', $uid);
    }

    /**
     * @return string|null Μοναδικός Αριθμός Καταχώρησης Παραστατικού
     */
    public function getMark(): ?string
    {
        return $this->get('mark');
    }

    /**
     * <h2>Μοναδικός Αριθμός Καταχώρησης Παραστατικού</h2>
     *
     * <p>Το mark αποτελεί τον Μοναδικό Αριθμό Καταχώρησης του παραστατικού (Μ.ΑΡ.Κ)
     * και συμπληρώνεται από την Υπηρεσία.</p>
     *
     * @param string $mark
     * @return self
     */
    public function setMark(string $mark): self
    {
        return $this->put('mark', $mark);
    }

    /**
     * @return string|null Μοναδικός Αριθμός Καταχώρησης Ακυρωτικού
     */
    public function getCancelledByMark(): ?string
    {
        return $this->get('cancelledByMark');
    }

    /**
     * <h2>Μοναδικός Αριθμός Καταχώρησης Ακυρωτικού</h2>
     *
     * <p>Ο Μοναδικός Αριθμός Καταχώρησης Ακυρωτικού συμπληρώνεται από την υπηρεσία
     * και εμφανίζεται κατά τη λήψη μόνο εφόσον το εν λόγω παραστατικό έχει ακυρωθεί.</p>
     *
     * @param string $cancelledByMark
     * @return self
     */
    public function setCancelledByMark(string $cancelledByMark): self
    {
        return $this->put('cancelledByMark', $cancelledByMark);
    }

    /**
     * @return string|null Συμβολοσειρά Αυθεντικοποίησης
     */
    public function getAuthenticationCode(): ?string
    {
        return $this->get('authenticationCode');
    }

    /**
     * <h2>Συμβολοσειρά Αυθεντικοποίησης</h2>
     *
     * <p>Το authenticationCode αποτελεί τη συμβολοσειρά αυθεντικοποίησης κάθε
     * παραστατικού και συμπληρώνεται από την Υπηρεσία για την περίπτωση που η
     * αποστολή γίνεται μέσω Παρόχου Ηλεκτρονικής Τιμολόγησης. Υπολογίζεται από το
     * SHA-1 hash των παρακάτω πεδίων του παραστατικού:</p>
     * <ul>
     * <li>ΑΦΜ Eκδότη</li>
     * <li>Ημερομηνία Έκδοσης</li>
     * <li>Αριθμός Εγκατάστασης στο Μητρώο του Taxis</li>
     * <li>Τύπος Παραστατικού</li>
     * <li>Σειρά</li>
     * <li>ΑΑ</li>
     * <li>Μ.ΑΡ.Κ Παραστατικού</li>
     * <li>Συνολική Αξία Παραστατικού</li>
     * <li>Σύνολο Αξίας Φ.Π.Α. Παραστατικού</li>
     * <li>ΑΦΜ Λήπτη</li>
     * </ul>
     *
     * @param string $authenticationCode
     * @return self
     */
    public function setAuthenticationCode(string $authenticationCode): self
    {
        return $this->put('authenticationCode', $authenticationCode);
    }

    /**
     * @return int|null Αδυναμία Επικοινωνίας Παρόχου
     */
    public function getTransmissionFailure(): ?int
    {
        return $this->get('transmissionFailure');
    }

    /**
     * <h2>Αδυναμία Επικοινωνίας Παρόχου</h2>
     *
     * <p>Αποδεκτό μόνο στην περίπτωση αποστολής από παρόχους.</p>
     * <ul>Επιτρεπτές τιμές:
     * <li>1: Στην περίπτωση αδυναμίας επικοινωνίας οντότητας με τον
     * πάροχο κατά την έκδοση / διαβίβαση παραστατικού</li>
     * <li>2: Στην περίπτωση αδυναμίας επικοινωνίας του παρόχου με το
     * myDATA κατά την έκδοση / διαβίβαση παραστατικού</li>
     *
     * @param int $transmissionFailure
     * @return self
     */
    public function setTransmissionFailure(int $transmissionFailure): self
    {
        return $this->put('transmissionFailure', $transmissionFailure);
    }

    /**
     * @return Issuer|null Εκδότης Παραστατικού
     */
    public function getIssuer(): ?Issuer
    {
        return $this->get('issuer');
    }

    /**
     * <h2>Εκδότης Παραστατικού</h2>
     * @param Issuer $issuer
     * @return $this
     */
    public function setIssuer(Issuer $issuer): self
    {
        return $this->put('issuer', $issuer);
    }

    /**
     * @return Counterpart|null Λήπτης Παραστατικού
     */
    public function getCounterpart(): ?Counterpart
    {
        return $this->get('counterpart');
    }

    /**
     * <h2>Λήπτης Παραστατικού</h2>
     *
     * @param Counterpart $counterpart
     * @return $this
     */
    public function setCounterpart(Counterpart $counterpart): self
    {
        return $this->put('counterpart', $counterpart);
    }

    /**
     * @return PaymentMethods|null Τρόποι Πληρωμής
     */
    public function getPaymentMethods(): ?PaymentMethods
    {
        return $this->get('paymentMethods');
    }

    /**
     * <h2>Τρόποι Πληρωμής</h2>
     *
     * <p>Προσθήκη τρόπου πληρωμής</p>
     *
     * @param PaymentMethodDetailType $paymentMethod
     * @return $this
     */
    public function addPaymentMethod(PaymentMethodDetailType $paymentMethod): self
    {
        $paymentMethods = $this->getPaymentMethods();
        if ($paymentMethods === null) {
            $paymentMethods = new PaymentMethods();
            $this->put('paymentMethods', $paymentMethods);
        }
        $paymentMethods->addPaymentMethod($paymentMethod);
        return $this;
    }

    /**
     * @return InvoiceHeaderType Επικεφαλίδα Παραστατικού
     */
    public function getInvoiceHeader(): InvoiceHeaderType
    {
        return $this->get('invoiceHeader');
    }

    /**
     * <h2>Επικεφαλίδα Παραστατικού</h2>
     *
     * @param InvoiceHeaderType $invoiceHeader
     * @return $this
     */
    public function setInvoiceHeader(InvoiceHeaderType $invoiceHeader): self
    {
        return $this->put('invoiceHeader', $invoiceHeader);
    }

    /**
     * @return array Γραμμές Παραστατικού
     */
    public function getInvoiceDetails(): array
    {
        return array_filter($this->attributes, static fn($property) => $property instanceof InvoiceRowType);
    }

    /**
     * <h2>Γραμμές Παραστατικού</h2>
     *
     * <p>Προσθήκη γραμμής παραστατικού</h2>
     *
     * @param InvoiceRowType $invoiceRow
     * @return $this
     */
    public function addInvoiceRow(InvoiceRowType $invoiceRow): self
    {
        return $this->put('invoiceDetails', $invoiceRow);
    }

    /**
     * @return TaxesTotals|null Σύνολα Φόρων
     */
    public function getTaxesTotals(): ?TaxesTotals
    {
        return $this->get('taxesTotals');
    }

    /**
     * <h2>Σύνολα Φόρων</h2>
     *
     * <p>Προσθήκη συνόλου φόρων</p>
     *
     * <p>Στο στοιχείο taxesTotals θα περιλαμβάνονται φόροι όλων των κατηγοριών, εκτός
     * του ΦΠΑ, οι οποίοι αφορούν όλο το παραστατικό σαν σύνολο. Σε περίπτωση που ο
     * χρήστης κάνει χρήση αυτού του στοιχείου, δε θα μπορεί να εισάγει φόρους εκτός
     * του ΦΠΑ σε κάθε γραμμή του παραστατικού ξεχωριστά.</p>
     *
     * @param TaxTotalsType $taxTotalsType
     * @return $this
     */
    public function addTaxesTotals(TaxTotalsType $taxTotalsType): self
    {
        $totals = $this->getTaxesTotals();
        if ($totals === null) {
            $totals = new TaxesTotals();
            $this->put('taxesTotals', $totals);
        }
        $totals->addTaxes($taxTotalsType);
        return $this;
    }

    /**
     * @return InvoiceSummaryType Περίληψη Παραστατικού
     */
    public function getInvoiceSummary(): InvoiceSummaryType
    {
        return $this->get('invoiceSummary');
    }

    /**
     * <h2>Περίληψη Παραστατικού</h2>
     *
     * @param InvoiceSummaryType $invoiceSummary
     * @return $this
     */
    public function setInvoiceSummary(InvoiceSummaryType $invoiceSummary): self
    {
        return $this->put('invoiceSummary', $invoiceSummary);
    }

    public function put($key, $value): self
    {
        if ($key === 'invoiceDetails') {
            $this->attributes[$key][] = $value;
            return $this;
        }

        return parent::put($key, $value);
    }
}