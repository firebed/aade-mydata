<?php

namespace Firebed\AadeMyData\Models;

/**
 * <ol>
 * <li>Σε περίπτωση που θα επιστρέφεται το στοιχείο continuationToken τα πεδία
 * nextPartitionKey και nextRowKey θα είναι συμπληρωμένα από την υπηρεσία και
 * χρησιμοποιούνται στην επόμενη κλήση της ίδιας μεθόδου που είχε καλεστεί από τον
 * χρήστη.</li>
 *
 * <li>Κάθε γραμμή αντιστοιχεί σε ένα μοναδικό σύνολο τιμών για τα πεδία
 * counterVatNumber, issueDate, invTyp και τον ΑΦΜ αναφοράς.</li>
 *
 * <li>Οι παράμετροι minMark maxMark αντιστοιχούν στον ελάχιστο και μέγιστο mark που
 * απαρτίζουν το σύνολο των παραστατικών που αντιστοιχούν για τη συγκεκριμένη
 * γραμμή.</li>
 *
 * <li>Τα παραστατικά που έχουν αυτοτιμολόγηση ανακτώνται σε διαφορετική γραμμή, με
 * την αντίστοιχη τιμή στο συγκεκριμένο πεδίο.</li>
 *
 * <li>Οι γραμμές των παραστατικών 1.5 (Εκκαθάριση – Αμοιβή Τρίτων) ανακτώνται σε
 * διαφορετικές γραμμές, με την αντίστοιχη τιμή στο συγκεκριμένο πεδίο.</li>
 * </ol>
 */
class BookInfo extends Type
{
    /**
     * @return ContinuationToken|null Στοιχείο για την τμηματική λήψη αποτελεσμάτων
     */
    public function getContinuationToken(): ?ContinuationToken
    {
        return $this->get('continuationToken');
    }

    /**
     * @return string ΑΦΜ λήπτη
     */
    public function getCounterVatNumber(): string
    {
        return $this->get('counterVatNumber');
    }

    /**
     * @return string Ημερομηνία έκδοσης Παραστατικού
     */
    public function getIssueDate(): string
    {
        return $this->get('issueDate');
    }

    /**
     * @return string Τύπος Παραστατικού
     */
    public function getInvType(): string
    {
        return $this->get('invType');
    }

    /**
     * @return string Αυτοτιμολόγηση
     */
    public function getSelfPricing(): string
    {
        return $this->get('selfpricing');
    }

    /**
     * @return string Επισήμανση
     */
    public function getInvoiceDetailType(): string
    {
        return $this->get('invoiceDetailType');
    }

    /**
     * @return string Καθαρή αξία
     */
    public function getNetValue(): string
    {
        return $this->get('netValue');
    }

    /**
     * @return string Ποσό ΦΠΑ
     */
    public function getVatAmount(): string
    {
        return $this->get('vatAmount');
    }

    /**
     * @return string Ποσό Παρακράτησης Φόρου
     */
    public function getWithheldAmount(): string
    {
        return $this->get('withheldAmount');
    }

    /**
     * @return string Ποσό Λοιπών Φόρων
     */
    public function getOtherTaxesAmount(): string
    {
        return $this->get('otherTaxesAmount');
    }

    /**
     * @return string Ποσό Χαρτοσήμου
     */
    public function getStampDutyAmount(): string
    {
        return $this->get('stampDutyAmount');
    }

    /**
     * @return string Ποσό Τελών
     */
    public function getFeesAmount(): string
    {
        return $this->get('feesAmount');
    }

    /**
     * @return string Ποσό Κρατήσεων
     */
    public function getDeductionsAmount(): string
    {
        return $this->get('deductionsAmount');
    }

    /**
     * @return string Ποσό Περί Τρίτων
     */
    public function getThirdPartyAmount(): string
    {
        return $this->get('thirdPartyAmount');
    }

    /**
     * @return string Συνολική Αξία
     */
    public function getGrossValue(): string
    {
        return $this->get('grossValue');
    }

    /**
     * @return string Πλήθος
     */
    public function getCount(): string
    {
        return $this->get('count');
    }

    /**
     * @return string Ελάχιστο ΜΑΡΚ πλήθους
     */
    public function getMinMark(): string
    {
        return $this->get('minMark');
    }

    /**
     * @return string Μέγιστο ΜΑΡΚ πλήθους
     */
    public function getMaxMark(): string
    {
        return $this->get('maxMark');
    }
}