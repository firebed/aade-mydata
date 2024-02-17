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
     * @param ContinuationToken $continuationToken Στοιχείο για την τμηματική λήψη αποτελεσμάτων
     */
    public function setContinuationToken(ContinuationToken $continuationToken): void
    {
        $this->set('continuationToken', $continuationToken);
    }

    /**
     * @return string ΑΦΜ λήπτη
     */
    public function getCounterVatNumber(): string
    {
        return $this->get('counterVatNumber');
    }

    /**
     * @param string $counterVatNumber ΑΦΜ λήπτη
     */
    public function setCounterVatNumber(string $counterVatNumber): void
    {
        $this->set('counterVatNumber', $counterVatNumber);
    }

    /**
     * @return string Ημερομηνία έκδοσης Παραστατικού
     */
    public function getIssueDate(): string
    {
        return $this->get('issueDate');
    }

    /**
     * @param string $issueDate Ημερομηνία έκδοσης Παραστατικού
     */
    public function setIssueDate(string $issueDate): void
    {
        $this->set('issueDate', $issueDate);
    }

    /**
     * @return string Τύπος Παραστατικού
     */
    public function getInvType(): string
    {
        return $this->get('invType');
    }

    /**
     * @param string $invType Τύπος Παραστατικού
     */
    public function setInvType(string $invType): void
    {
        $this->set('invType', $invType);
    }

    /**
     * @return string Αυτοτιμολόγηση
     */
    public function getSelfPricing(): string
    {
        return $this->get('selfpricing');
    }

    /**
     * @param string $selfPricing Αυτοτιμολόγηση
     */
    public function setSelfPricing(string $selfPricing): void
    {
        $this->set('selfpricing', $selfPricing);
    }

    /**
     * @return string Επισήμανση
     */
    public function getInvoiceDetailType(): string
    {
        return $this->get('invoiceDetailType');
    }

    /**
     * @param string $invoiceDetailType Επισήμανση
     */
    public function setInvoiceDetailType(string $invoiceDetailType): void
    {
        $this->set('invoiceDetailType', $invoiceDetailType);
    }

    /**
     * @return string Καθαρή αξία
     */
    public function getNetValue(): string
    {
        return $this->get('netValue');
    }

    /**
     * @param string $netValue Καθαρή αξία
     */
    public function setNetValue(string $netValue): void
    {
        $this->set('netValue', $netValue);
    }

    /**
     * @return string Ποσό ΦΠΑ
     */
    public function getVatAmount(): string
    {
        return $this->get('vatAmount');
    }

    /**
     * @param string $vatAmount Ποσό ΦΠΑ
     */
    public function setVatAmount(string $vatAmount): void
    {
        $this->set('vatAmount', $vatAmount);
    }

    /**
     * @return string Ποσό Παρακράτησης Φόρου
     */
    public function getWithheldAmount(): string
    {
        return $this->get('withheldAmount');
    }

    /**
     * @param string $withheldAmount Ποσό Παρακράτησης Φόρου
     */
    public function setWithheldAmount(string $withheldAmount): void
    {
        $this->set('withheldAmount', $withheldAmount);
    }

    /**
     * @return string Ποσό Λοιπών Φόρων
     */
    public function getOtherTaxesAmount(): string
    {
        return $this->get('otherTaxesAmount');
    }

    /**
     * @param string $otherTaxesAmount Ποσό Λοιπών Φόρων
     */
    public function setOtherTaxesAmount(string $otherTaxesAmount): void
    {
        $this->set('otherTaxesAmount', $otherTaxesAmount);
    }

    /**
     * @return string Ποσό Χαρτοσήμου
     */
    public function getStampDutyAmount(): string
    {
        return $this->get('stampDutyAmount');
    }

    /**
     * @param string $stampDutyAmount Ποσό Χαρτοσήμου
     */
    public function setStampDutyAmount(string $stampDutyAmount): void
    {
        $this->set('stampDutyAmount', $stampDutyAmount);
    }

    /**
     * @return string Ποσό Τελών
     */
    public function getFeesAmount(): string
    {
        return $this->get('feesAmount');
    }

    /**
     * @param string $feesAmount Ποσό Τελών
     */
    public function setFeesAmount(string $feesAmount): void
    {
        $this->set('feesAmount', $feesAmount);
    }

    /**
     * @return string Ποσό Κρατήσεων
     */
    public function getDeductionsAmount(): string
    {
        return $this->get('deductionsAmount');
    }

    /**
     * @param string $deductionsAmount Ποσό Κρατήσεων
     */
    public function setDeductionsAmount(string $deductionsAmount): void
    {
        $this->set('deductionsAmount', $deductionsAmount);
    }

    /**
     * @return string Ποσό Περί Τρίτων
     */
    public function getThirdPartyAmount(): string
    {
        return $this->get('thirdPartyAmount');
    }

    /**
     * @param string $thirdPartyAmount Ποσό Περί Τρίτων
     */
    public function setThirdPartyAmount(string $thirdPartyAmount): void
    {
        $this->set('thirdPartyAmount', $thirdPartyAmount);
    }

    /**
     * @return string Συνολική Αξία
     */
    public function getGrossValue(): string
    {
        return $this->get('grossValue');
    }

    /**
     * @param string $grossValue Συνολική Αξία
     */
    public function setGrossValue(string $grossValue): void
    {
        $this->set('grossValue', $grossValue);
    }

    /**
     * @return string Πλήθος
     */
    public function getCount(): string
    {
        return $this->get('count');
    }

    /**
     * @param string $count Πλήθος
     */
    public function setCount(string $count): void
    {
        $this->set('count', $count);
    }

    /**
     * @return string Ελάχιστο ΜΑΡΚ πλήθους
     */
    public function getMinMark(): string
    {
        return $this->get('minMark');
    }

    /**
     * @param string $minMark Ελάχιστο ΜΑΡΚ πλήθους
     */
    public function setMinMark(string $minMark): void
    {
        $this->set('minMark', $minMark);
    }

    /**
     * @return string Μέγιστο ΜΑΡΚ πλήθους
     */
    public function getMaxMark(): string
    {
        return $this->get('maxMark');
    }

    /**
     * @param string $maxMark Μέγιστο ΜΑΡΚ πλήθους
     */
    public function setMaxMark(string $maxMark): void
    {
        $this->set('maxMark', $maxMark);
    }
}