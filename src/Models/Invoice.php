<?php

namespace Firebed\AadeMyData\Models;


use Firebed\AadeMyData\Actions\SummarizeInvoice;
use Firebed\AadeMyData\Traits\HasFactory;

class Invoice extends Type
{
    use HasFactory;

    protected array $expectedOrder = [
        'issuer',
        'counterpart',
        'invoiceHeader',
        'paymentMethods',
        'invoiceDetails',
        'taxesTotals',
        'invoiceSummary',
        'otherTransportDetails',
    ];

    protected array $casts = [
        'issuer'                => Issuer::class,
        'counterpart'           => Counterpart::class,
        'invoiceHeader'         => InvoiceHeader::class,
        'paymentMethods'        => PaymentMethods::class,
        'invoiceDetails'        => InvoiceDetails::class,
        'taxesTotals'           => TaxesTotals::class,
        'invoiceSummary'        => InvoiceSummary::class,
        'otherTransportDetails' => TransportDetail::class,
    ];

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
     * <li>Στην περίπτωση αδυναμίας επικοινωνίας του ERP με το myDATA κατά την έκδοση / διαβίβαση παραστατικού</li>
     * </ol>
     *
     * @param int $transmissionFailure Κωδικός αδυναμίας επικοινωνίας παρόχου
     */
    public function setTransmissionFailure(int $transmissionFailure): void
    {
        $this->set('transmissionFailure', $transmissionFailure);
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
        $this->set('issuer', $issuer);
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
        $this->set('counterpart', $counterpart);
    }

    /**
     * @return PaymentMethods|null Τρόποι Πληρωμής
     */
    public function getPaymentMethods(): ?PaymentMethods
    {
        return $this->get('paymentMethods');
    }

    /**
     * @param PaymentMethods|PaymentMethodDetail[] $paymentMethods
     * @return void
     */
    public function setPaymentMethods(PaymentMethods|array $paymentMethods): void
    {
        if ($paymentMethods instanceof PaymentMethods) {
            $this->set('paymentMethods', $paymentMethods);
        } else {
            $this->set('paymentMethods', new PaymentMethods($paymentMethods));
        }
    }

    /**
     * Προσθήκη τρόπου πληρωμής.
     *
     * @param PaymentMethodDetail $paymentMethodDetail Τρόπος Πληρωμής
     */
    public function addPaymentMethod(PaymentMethodDetail $paymentMethodDetail): void
    {
        $paymentMethods = $this->getPaymentMethods();
        if ($paymentMethods) {
            $paymentMethods->add($paymentMethodDetail);
        } else {
            $this->set('paymentMethods', new PaymentMethods($paymentMethodDetail));
        }
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
        $this->set('invoiceHeader', $invoiceHeader);
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
        $this->set('invoiceSummary', $invoiceSummary);
    }

    /**
     * @param array{
     *     enableClassificationIds: bool,
     * } $options
     * @return InvoiceSummary
     */
    public function summarizeInvoice(array $options = []): InvoiceSummary
    {
        $summary = (new SummarizeInvoice)->handle($this, $options);
        $this->setInvoiceSummary($summary);
        
        return $summary;
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
        return $this->get('taxesTotals') ?? new TaxesTotals();
    }

    /**
     * Προσθήκη συνόλου φόρων.
     */
    public function addTaxesTotals(TaxTotals $taxTotals): void
    {
        $taxesTotals = $this->getTaxesTotals();
        $taxesTotals->add($taxTotals);
        $this->set('taxesTotals', $taxesTotals);
    }

    /**
     * Συμπληρώνεται από την Υπηρεσία.
     *
     * Κωδικοποιημένο αλφαριθμητικό για να χρησιμοποιηθεί από τα
     * προγράμματα για τη δημιουργία QR Code τύπου Url.
     *
     * @version 1.0.7
     */
    public function getQrCodeUrl(): ?string
    {
        return $this->get('qrCodeUrl');
    }

    /**
     * @return TransportDetail[]|null Λοιπές Λεπτομέρειες Διακίνησης (Ορισμός - Αλλαγή Μτφ Μέσων)
     *
     * @version 1.0.7
     */
    public function getOtherTransportDetails(): ?array
    {
        return $this->get('otherTransportDetails');
    }

    /**
     * Προσθήκη Λεπτομέρειες Διακίνησης (Ορισμός - Αλλαγή Μτφ Μέσων).
     *
     * @param TransportDetail $transportDetailType Λεπτομέρειες Διακίνησης
     *
     * @version 1.0.7
     */
    public function addOtherTransportDetail(TransportDetail $transportDetailType): void
    {
        $this->push('otherTransportDetails', $transportDetailType);
    }

    public function set($key, $value): void
    {
        if ($key === 'invoiceDetails' || $key === 'otherTransportDetails') {
            if (is_array($value)) {
                parent::set($key, $value);
            } else {
                $this->push($key, $value);
            }
            return;
        }

        parent::set($key, $value);
    }
}