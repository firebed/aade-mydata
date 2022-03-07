<?php

namespace Firebed\AadeMyData\Models;


final class Invoice extends Type
{
    /**
     * Συμπληρώνεται από την Υπηρεσία.
     *
     * @return string|null Αναγνωριστικό Παραστατικού
     */
    public function getUid(): ?string
    {
        return $this->get('uid');
    }

    /**
     * Συμπληρώνεται από την Υπηρεσία.
     *
     * @return string|null Μοναδικός Αριθμός Καταχώρησης Παραστατικού
     */
    public function getMark(): ?string
    {
        return $this->get('mark');
    }

    /**
     * Συμπληρώνεται από την υπηρεσία και εμφανίζεται κατά τη λήψη μόνο εφόσον το εν λόγω παραστατικό έχει ακυρωθεί.
     *
     * @return string|null Μοναδικός Αριθμός Καταχώρησης Ακυρωτικού
     */
    public function getCancelledByMark(): ?string
    {
        return $this->get('cancelledByMark');
    }

    /**
     * Συμπληρώνεται από την Υπηρεσία για την περίπτωση που η αποστολή γίνεται μέσω Παρόχου Ηλεκτρονικής Τιμολόγησης.
     *
     * @return string|null Συμβολοσειρά Αυθεντικοποίησης
     */
    public function getAuthenticationCode(): ?string
    {
        return $this->get('authenticationCode');
    }

    /**
     * @return int|null Κωδικός αδυναμίας επικοινωνίας παρόχου
     */
    public function getTransmissionFailure(): ?int
    {
        return $this->get('transmissionFailure');
    }

    /**
     * <p>Αποδεκτό μόνο στην περίπτωση αποστολής από παρόχους.</p>
     * <ol>Επιτρεπτές τιμές:
     * <li>Στην περίπτωση αδυναμίας επικοινωνίας οντότητας με τον πάροχο κατά την έκδοση / διαβίβαση παραστατικού</li>
     * <li>Στην περίπτωση αδυναμίας επικοινωνίας του παρόχου με το myDATA κατά την έκδοση / διαβίβαση παραστατικού</li>
     * </ol>
     * 
     * @param int $transmissionFailure Κωδικός αδυναμίας επικοινωνίας παρόχου
     */
    public function setTransmissionFailure(int $transmissionFailure): void
    {
        $this->put('transmissionFailure', $transmissionFailure);
    }

    /**
     * @return Issuer|null Εκδότης Παραστατικού
     */
    public function getIssuer(): ?Issuer
    {
        return $this->get('issuer');
    }

    /**
     * @param Issuer $issuer Εκδότης Παραστατικού
     */
    public function setIssuer(Issuer $issuer): void
    {
        $this->put('issuer', $issuer);
    }

    /**
     * @return Counterpart|null Λήπτης Παραστατικού
     */
    public function getCounterpart(): ?Counterpart
    {
        return $this->get('counterpart');
    }

    /**
     * @param Counterpart $counterpart Λήπτης Παραστατικού
     */
    public function setCounterpart(Counterpart $counterpart): void
    {
        $this->put('counterpart', $counterpart);
    }

    /**
     * @return PaymentMethods|null Τρόποι Πληρωμής
     */
    public function getPaymentMethods(): ?PaymentMethods
    {
        return $this->get('paymentMethods');
    }

    /**
     * Προσθήκη τρόπου πληρωμής.
     * 
     * @param PaymentMethodDetail $paymentMethod Τρόπος Πληρωμής
     */
    public function addPaymentMethod(PaymentMethodDetail $paymentMethod): void
    {
        $paymentMethods = $this->getPaymentMethods() ?? new PaymentMethods();
        $paymentMethods->addPaymentMethod($paymentMethod);
        $this->put('paymentMethods', $paymentMethods);
    }

    /**
     * @return InvoiceHeader|null Επικεφαλίδα Παραστατικού
     */
    public function getInvoiceHeader(): ?InvoiceHeader
    {
        return $this->get('invoiceHeader');
    }

    /**
     * @param InvoiceHeader $invoiceHeader Επικεφαλίδα Παραστατικού
     */
    public function setInvoiceHeader(InvoiceHeader $invoiceHeader): void
    {
        $this->put('invoiceHeader', $invoiceHeader);
    }

    /**
     * @return InvoiceDetails[]|null Γραμμές Παραστατικού
     */
    public function getInvoiceDetails(): ?array
    {
        return $this->get('invoiceDetails');
    }

    /**
     * Προσθήκη γραμμής παραστατικού.
     * 
     * @param InvoiceDetails $invoiceDetails Γραμμή Παραστατικού
     */
    public function addInvoiceDetails(InvoiceDetails $invoiceDetails): void
    {
        $this->push('invoiceDetails', $invoiceDetails);
    }

    /**
     * @return InvoiceSummary|null Περίληψη Παραστατικού
     */
    public function getInvoiceSummary(): ?InvoiceSummary
    {
        return $this->get('invoiceSummary');
    }

    /**
     * @param InvoiceSummary $invoiceSummary Περίληψη Παραστατικού
     */
    public function setInvoiceSummary(InvoiceSummary $invoiceSummary): void
    {
        $this->put('invoiceSummary', $invoiceSummary);
    }

    /**
     * Στο στοιχείο taxesTotals θα περιλαμβάνονται φόροι όλων των κατηγοριών, εκτός
     * του ΦΠΑ, οι οποίοι αφορούν όλο το παραστατικό σαν σύνολο. Σε περίπτωση που ο
     * χρήστης κάνει χρήση αυτού του στοιχείου, δε θα μπορεί να εισάγει φόρους εκτός
     * του ΦΠΑ σε κάθε γραμμή του παραστατικού ξεχωριστά.
     * 
     * @return TaxesTotals|null
     */
    public function getTaxesTotals(): ?TaxesTotals
    {
        return $this->get('taxesTotals');
    }
    
    /**
     * Προσθήκη συνόλου φόρων.
     */
    public function addTaxesTotals(TaxTotals $taxTotalsType): void
    {
        $taxesTotals = $this->getTaxesTotals() ?? new TaxesTotals();
        $taxesTotals->addTaxes($taxTotalsType);
        $this->put('taxesTotals', $taxesTotals);
    }

    public function put($key, $value): void
    {
        if ($key === 'invoiceDetails') {
            $this->addInvoiceDetails($value);
            return;
        }
        
        parent::put($key, $value);
    }
}