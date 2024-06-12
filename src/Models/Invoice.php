<?php

namespace Firebed\AadeMyData\Models;


use DOMDocument;
use Firebed\AadeMyData\Actions\SquashInvoiceRows;
use Firebed\AadeMyData\Actions\SummarizeInvoice;
use Firebed\AadeMyData\Traits\HasFactory;
use Firebed\AadeMyData\Xml\InvoicesDocWriter;

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
        'issuer' => Issuer::class,
        'counterpart' => Counterpart::class,
        'invoiceHeader' => InvoiceHeader::class,
        'paymentMethods' => PaymentMethods::class,
        'invoiceDetails' => InvoiceDetails::class,
        'taxesTotals' => TaxesTotals::class,
        'invoiceSummary' => InvoiceSummary::class,
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
     * @param  int|null  $transmissionFailure  Κωδικός αδυναμίας επικοινωνίας παρόχου
     */
    public function setTransmissionFailure(?int $transmissionFailure): static
    {
        return $this->set('transmissionFailure', $transmissionFailure);
    }

    /**
     * @return Issuer|null Εκδότης Παραστατικού
     */
    public function getIssuer(): ?Issuer
    {
        return $this->get('issuer');
    }

    /**
     * @param  Issuer  $issuer  Εκδότης Παραστατικού
     */
    public function setIssuer(Issuer $issuer): static
    {
        return $this->set('issuer', $issuer);
    }

    /**
     * @return Counterpart|null Λήπτης Παραστατικού
     */
    public function getCounterpart(): ?Counterpart
    {
        return $this->get('counterpart');
    }

    /**
     * @param  Counterpart|null  $counterpart  Λήπτης Παραστατικού
     */
    public function setCounterpart(Counterpart|null $counterpart): static
    {
        return $this->set('counterpart', $counterpart);
    }

    /**
     * @return PaymentMethods|null Τρόποι Πληρωμής
     */
    public function getPaymentMethods(): ?PaymentMethods
    {
        return $this->get('paymentMethods');
    }

    /**
     * @param  PaymentMethods|PaymentMethodDetail[]  $paymentMethods
     */
    public function setPaymentMethods(PaymentMethods|array|null $paymentMethods): static
    {
        if ($paymentMethods == null || $paymentMethods instanceof PaymentMethods) {
            return $this->set('paymentMethods', $paymentMethods);
        }

        return $this->set('paymentMethods', new PaymentMethods($paymentMethods));
    }

    /**
     * Προσθήκη τρόπου πληρωμής.
     *
     * @param  PaymentMethodDetail  $paymentMethodDetail  Τρόπος Πληρωμής
     */
    public function addPaymentMethod(PaymentMethodDetail $paymentMethodDetail): static
    {
        $paymentMethods = $this->getPaymentMethods() ?? new PaymentMethods();
        $paymentMethods->add($paymentMethodDetail);
        return $this->set('paymentMethods', $paymentMethods);
    }

    /**
     * @return InvoiceHeader|null Επικεφαλίδα Παραστατικού
     */
    public function getInvoiceHeader(): ?InvoiceHeader
    {
        return $this->get('invoiceHeader');
    }

    /**
     * @param  InvoiceHeader  $invoiceHeader  Επικεφαλίδα Παραστατικού
     */
    public function setInvoiceHeader(InvoiceHeader $invoiceHeader): static
    {
        return $this->set('invoiceHeader', $invoiceHeader);
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
     * @param  InvoiceDetails  $invoiceDetails  Γραμμή Παραστατικού
     */
    public function addInvoiceDetails(InvoiceDetails $invoiceDetails): static
    {
        return $this->push('invoiceDetails', $invoiceDetails);
    }

    /**
     * @param  InvoiceDetails[]  $invoiceDetails  Γραμμές Παραστατικού
     */
    public function setInvoiceDetails(array $invoiceDetails): static
    {
        return $this->set('invoiceDetails', $invoiceDetails);
    }

    /**
     * @return InvoiceSummary|null Περίληψη Παραστατικού
     */
    public function getInvoiceSummary(): ?InvoiceSummary
    {
        return $this->get('invoiceSummary');
    }

    /**
     * @param  InvoiceSummary  $invoiceSummary  Περίληψη Παραστατικού
     */
    public function setInvoiceSummary(InvoiceSummary $invoiceSummary): static
    {
        return $this->set('invoiceSummary', $invoiceSummary);
    }

    /**
     * "Squashes" similar invoice lines and sums up their values.
     *
     * @param  array{clsLineNumber: bool}  $options  Squashing options.
     * If 'clsLineNumber' == true the process will add line numbers to classifications.
     *
     * @return $this
     */
    public function squashInvoiceRows(array $options = []): static
    {
        $squash = new SquashInvoiceRows();
        $this->setInvoiceDetails($squash->handle($this->getInvoiceDetails(), $options));
        return $this;
    }

    /**
     * @param  array{enableClassificationIds: bool}  $options
     * @return static
     */
    public function summarizeInvoice(array $options = []): static
    {
        $summary = (new SummarizeInvoice)->handle($this, $options);
        $this->setInvoiceSummary($summary);

        return $this;
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
    public function addTaxesTotals(TaxTotals $taxTotals): static
    {
        $taxesTotals = $this->getTaxesTotals();
        $taxesTotals->add($taxTotals);
        return $this->set('taxesTotals', $taxesTotals);
    }

    /**
     * @param  TaxTotals[]|null  $taxTotals
     */
    public function setTaxesTotals(TaxesTotals|array|null $taxTotals): static
    {
        if ($taxTotals === null || $taxTotals instanceof TaxesTotals) {
            return $this->set('taxesTotals', $taxTotals);
        }

        return $this->set('taxesTotals', new TaxesTotals($taxTotals));
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
     * @param  TransportDetail  $transportDetailType  Λεπτομέρειες Διακίνησης
     *
     * @version 1.0.7
     */
    public function addOtherTransportDetail(TransportDetail $transportDetailType): static
    {
        return $this->push('otherTransportDetails', $transportDetailType);
    }

    /**
     * @param  TransportDetail[]|null  $transportDetails
     * @return $this
     */
    public function setOtherTransportDetails(?array $transportDetails): static
    {
        return $this->set('otherTransportDetails', $transportDetails);
    }

    public function set($key, $value): static
    {
        if (($key === 'invoiceDetails' || $key === 'otherTransportDetails') && !is_array($value)) {
            return $this->push($key, $value);
        }

        return parent::set($key, $value);
    }

    public function toXml(bool $asInvoicesDoc = false): string
    {
        $writer = new InvoicesDocWriter();
        $fullXml = $writer->asXML(new InvoicesDoc($this));
        
        if ($asInvoicesDoc) {
            return $fullXml;
        }
        
        $doc = $writer->getDomDocument();
        return $doc->saveXML($doc->getElementsByTagName('invoice')->item(0));
    }

    public function validate(): array
    {
        libxml_use_internal_errors(true);
        libxml_clear_errors();

        $writer = new InvoicesDocWriter();
        $xml = $writer->asXML(new InvoicesDoc($this));

        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $dom->schemaValidate(__DIR__.'/../../xsd/InvoicesDoc-v1.0.8.xsd');

        return array_map(function ($error) {
            preg_match("/Element '(.+?)':( \[.*?])? (.+)/", $error->message, $matches);
            return [
                'field' => $matches[1] ?? null,
                'message' => $matches[3] ?? null,
            ];
        }, libxml_get_errors());
    }
}
