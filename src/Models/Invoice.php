<?php

namespace Firebed\AadeMyData\Models;


use Firebed\AadeMyData\Actions\GenerateUid;
use Firebed\AadeMyData\Actions\SquashInvoiceRows;
use Firebed\AadeMyData\Actions\SummarizeInvoice;
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\DeliveryStatus;
use Firebed\AadeMyData\Enums\TransmissionFailure;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryEvent;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryLifecycle;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\PackagingDetail;
use Firebed\AadeMyData\Traits\HasFactory;
use Firebed\AadeMyData\Xml\InvoicesDocWriter;

class Invoice extends Type
{
    use HasFactory;
    use HasSchemaValidation;

    const VERSION = 'v2.0.1';

    protected array $expectedOrder = [
        'uid',
        'mark',
        'cancelledByMark',
        'authenticationCode',
        'transmissionFailure',
        'issuer',
        'counterpart',
        'invoiceHeader',
        'paymentMethods',
        'invoiceDetails',
        'taxesTotals',
        'invoiceSummary',
        'qrCodeUrl',
        'downloadingInvoiceUrl',
        'packingsDeclarations',
        'invoiceDeliveryStatus',
        'deliveryLifecycle',
    ];

    protected array $casts = [
        'issuer' => Issuer::class,
        'counterpart' => Counterpart::class,
        'invoiceHeader' => InvoiceHeader::class,
        'paymentMethods' => PaymentMethods::class,
        'invoiceDetails' => InvoiceDetails::class,
        'taxesTotals' => TaxesTotals::class,
        'invoiceSummary' => InvoiceSummary::class,
        'packingsDeclarations' => PackagingDetail::class,
        'invoiceDeliveryStatus' => DeliveryStatus::class,
        'deliveryLifecycle' => DeliveryLifecycle::class,
    ];

    /**
     * Property to store the original invoice rows before squashing.
     * @var array|null
     */
    protected ?array $originalInvoiceRows = null;

    /**
     * Συμπληρώνεται από την Υπηρεσία.
     *
     * @return string|null Αναγνωριστικό Παραστατικού
     */
    public function getUid(): ?string
    {
        return $this->get('uid');
    }

    public function guessUid(): ?string
    {
        $generator = new GenerateUid();
        return $generator->handle(
            vatNumber: $this->getIssuer()->getVatNumber(),
            issueDate: $this->getInvoiceHeader()->getIssueDate(),
            branchId: $this->getIssuer()->getBranch(),
            invoiceType: $this->getInvoiceHeader()->getInvoiceType(),
            series: $this->getInvoiceHeader()->getSeries(),
            number: $this->getInvoiceHeader()->getAa(),
            invoiceVariationType: $this->getInvoiceHeader()->getInvoiceVariationType(),
        );
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
     * @param TransmissionFailure|int|null $transmissionFailure Κωδικός αδυναμίας επικοινωνίας παρόχου
     * @return Invoice
     */
    public function setTransmissionFailure(TransmissionFailure|int|null $transmissionFailure): static
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
     * @param Issuer $issuer Εκδότης Παραστατικού
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
     * @param Counterpart|null $counterpart Λήπτης Παραστατικού
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
     * @param PaymentMethods|PaymentMethodDetail[] $paymentMethods
     */
    public function setPaymentMethods(PaymentMethods|array|null $paymentMethods): static
    {
        if ($paymentMethods === null || $paymentMethods instanceof PaymentMethods) {
            return $this->set('paymentMethods', $paymentMethods);
        }

        return $this->set('paymentMethods', new PaymentMethods($paymentMethods));
    }

    public function getTotalPaymentAmount(): float
    {
        return $this->getPaymentMethods()?->getTotalPaymentAmount() ?? 0;
    }

    /**
     * Προσθήκη τρόπου πληρωμής.
     *
     * @param PaymentMethodDetail $paymentMethodDetail Τρόπος Πληρωμής
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
     * @param InvoiceHeader $invoiceHeader Επικεφαλίδα Παραστατικού
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
     * @param InvoiceDetails $invoiceDetails Γραμμή Παραστατικού
     */
    public function addInvoiceDetails(InvoiceDetails $invoiceDetails): static
    {
        return $this->push('invoiceDetails', $invoiceDetails);
    }

    /**
     * @param InvoiceDetails[] $invoiceDetails Γραμμές Παραστατικού
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
     * @param InvoiceSummary $invoiceSummary Περίληψη Παραστατικού
     */
    public function setInvoiceSummary(InvoiceSummary $invoiceSummary): static
    {
        return $this->set('invoiceSummary', $invoiceSummary);
    }

    /**
     * "Squashes" similar invoice lines and sums up their values.
     *
     * @param array{clsLineNumber: bool} $options Squashing options.
     * If 'clsLineNumber' == true the process will add line numbers to classifications.
     *
     * @return $this
     */
    public function squashInvoiceRows(array $options = []): static
    {
        $this->originalInvoiceRows = $this->getInvoiceDetails();

        $squash = new SquashInvoiceRows();
        $this->setInvoiceDetails($squash->handle($this->getInvoiceDetails(), $options));
        return $this;
    }

    /**
     * Returns the original invoice rows before squashing.
     *
     * @return Invoice
     */
    public function unSquashInvoiceRows(): static
    {
        $this->setInvoiceDetails($this->originalInvoiceRows);
        $this->originalInvoiceRows = null;
        return $this;
    }

    /**
     * Check if the invoice rows have been squashed.
     *
     * @return bool
     */
    public function isSquashed(): bool
    {
        return ! empty($this->originalInvoiceRows);
    }

    /**
     * @param array{enableClassificationIds: bool} $options
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
     * @param TaxTotals[]|null $taxTotals
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
     *
     * @deprecated Since version v2.0.1 of myDATA. Do not use.
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
     *
     * @deprecated Since version v2.0.1 of myDATA. Do not use.
     */
    public function addOtherTransportDetail(TransportDetail $transportDetailType): static
    {
        return $this->push('otherTransportDetails', $transportDetailType);
    }

    /**
     * @param TransportDetail[]|null $transportDetails
     * @return $this
     *
     * @deprecated Since version v2.0.1 of myDATA. Do not use.
     */
    public function setOtherTransportDetails(?array $transportDetails): static
    {
        return $this->set('otherTransportDetails', $transportDetails);
    }

    /**
     * URL όπου ο λήπτης του παραστατικού με κλήση αυτού και ορισμό παραμέτρου θα μπορεί να λαμβάνει το παραστατικό
     * Αυτό το url θα πρέπει να χρησιμοποιείται για τη δημιουργία του QR Code που τυπώνεται στα παραστατικά που εκδίδονται μέσω παρόχων
     * Έγκυρο μόνο στην περίπτωση διαβίβασης μέσω κανάλι παρόχου
     *
     * @return string|null
     * @version 1.0.12
     */
    public function getDownloadingInvoiceUrl(): ?string
    {
        return $this->get('downloadingInvoiceUrl');
    }

    /**
     * @param string $downloadingInvoiceUrl
     * @return $this
     * @version 1.0.12
     */
    public function setDownloadingInvoiceUrl(string $downloadingInvoiceUrl): Invoice
    {
        return $this->set('downloadingInvoiceUrl', $downloadingInvoiceUrl);
    }

    /**
     * @return PackagingDetail[]|null Δηλώσεις Συσκευασιών Διακίνησης
     *
     * @version 2.0.1
     */
    public function getPackingsDeclarations(): ?array
    {
        return $this->get('packingsDeclarations');
    }

    /**
     * Έγκυρο μόνο για παραστατικά διακίνησης.
     *
     * @param PackagingDetail[]|null $packingsDeclarations Δηλώσεις Συσκευασιών Διακίνησης
     * @return $this
     *
     * @version 2.0.1
     */
    public function setPackingsDeclarations(?array $packingsDeclarations): static
    {
        return $this->set('packingsDeclarations', $packingsDeclarations);
    }

    /**
     * Προσθήκη Δήλωση Συσκευασίας Διακίνησης.
     * @param PackagingDetail $packagingDetailType Δήλωση Συσκευασίας Διακίνησης
     * @return $this
     */
    public function addPackagingDeclaration(PackagingDetail $packagingDetailType): static
    {
        return $this->push('packingsDeclarations', $packagingDetailType);
    }

    /**
     * Είναι read-only - παρέχεται από το myDATA κατά την ανάκτηση του.
     *
     * @return DeliveryStatus|null Κατάσταση Παραστατικού Δελτίου Διακίνησης
     * @version 2.0.1
     */
    public function getInvoiceDeliveryStatus(): ?DeliveryStatus
    {
        return $this->get('invoiceDeliveryStatus');
    }

    /**
     * Είναι read-only - παρέχεται από το myDATA κατά την ανάκτηση του.
     * @return DeliveryEvent[]|null Το σύνολο των γεγονότων του κύκλου ζωής (lifecycle) του παραστατικού διακίνησης.
     * @version 2.0.1
     */
    public function getDeliveryLifecycle(): ?DeliveryLifecycle
    {
        return $this->get('deliveryLifecycle');
    }

    public function set($key, $value): static
    {
        if (($key === 'invoiceDetails' || $key === 'otherTransportDetails') && ! is_array($value)) {
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
        $xml = (new InvoicesDocWriter())->asXML(new InvoicesDoc($this));

        return $this->validateSchema($xml, 'InvoicesDoc-' . self::VERSION . '.xsd');
    }
}
