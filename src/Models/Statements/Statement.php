<?php

namespace Firebed\AadeMyData\Models\Statements;

use Firebed\AadeMyData\Enums\ProviderType;
use Firebed\AadeMyData\Models\Type;
use Firebed\AadeMyData\Traits\HasFactory;
use Firebed\AadeMyData\Xml\Statements\StatementDocWriter;

/**
 * @version 1.0.12
 */
class Statement extends Type
{
    use HasFactory;

    /**
     * @var array<string, class-string>
     */
    protected array $casts = [
        'liableUserCategory' => ProviderType::class,
        'providerType' => ProviderType::class,
    ];

    protected array $expectedOrder = [
        'statementId',
        'submissionDateTime',
        'entityVatNumber',
        'liableUserCategory',
        'providerType',
        'isB2BTransactions',
        'isB2CTransactions',
        'isB2GTransactions',
        'providerVatNumber',
        'providerLicenceNumber',
        'providerContractNumber',
        'providerContractConclusionDate',
        'providerContractActivationDate',
        'issueStartDate',
        'issueStopDate',
        'internetProvider',
        'internetProviderContractNumber',
        'internetProviderContractDate',
    ];

    /**
     * @return int|null Μοναδικός Αριθμός Δήλωσης (Συμπληρώνεται από την Υπηρεσία)
     */
    public function getStatementId(): ?int
    {
        return $this->get('statementId');
    }

    /**
     * @return string|null Ημερομηνία Yποβολής Δήλωσης (Συμπληρώνεται από την Υπηρεσία)
     */
    public function getSubmissionDateTime(): ?string
    {
        return $this->get('submissionDateTime');
    }

    /**
     * @return string|null ΑΦΜ Υπόχρεης Οντότητας
     */
    public function getEntityVatNumber(): ?string
    {
        return $this->get('entityVatNumber');
    }

    /**
     * @param  string  $entityVatNumber  ΑΦΜ Υπόχρεης Οντότητας
     * @return static
     */
    public function setEntityVatNumber(string $entityVatNumber): static
    {
        return $this->set('entityVatNumber', $entityVatNumber);
    }

    /**
     * @return ProviderType|null Κατηγορία Υπόχρεου
     */
    public function getLiableUserCategory(): ?ProviderType
    {
        return $this->get('liableUserCategory');
    }

    /**
     * @param  ProviderType|int  $liableUserCategory  Κατηγορία Υπόχρεου
     * @return static
     */
    public function setLiableUserCategory(ProviderType|int $liableUserCategory): static
    {
        return $this->set('liableUserCategory', $liableUserCategory);
    }

    /**
     * @return ProviderType|null Τύπος Παρόχου
     */
    public function getProviderType(): ?ProviderType
    {
        return $this->get('providerType');
    }

    /**
     * @param  ProviderType|int  $providerType  Τύπος Παρόχου
     * @return static
     */
    public function setProviderType(ProviderType|int $providerType): static
    {
        return $this->set('providerType', $providerType);
    }

    /**
     * @return bool|null Συναλλαγές B2B
     */
    public function getIsB2BTransactions(): ?bool
    {
        return $this->get('isB2BTransactions');
    }

    /**
     * @param  bool|null  $isB2BTransactions  Συναλλαγές B2B
     * @return static
     */
    public function setIsB2BTransactions(?bool $isB2BTransactions): static
    {
        return $this->set('isB2BTransactions', $isB2BTransactions);
    }

    /**
     * @return bool|null Συναλλαγές B2C
     */
    public function getIsB2CTransactions(): ?bool
    {
        return $this->get('isB2CTransactions');
    }

    /**
     * @param  bool|null  $isB2CTransactions  Συναλλαγές B2C
     * @return static
     */
    public function setIsB2CTransactions(?bool $isB2CTransactions): static
    {
        return $this->set('isB2CTransactions', $isB2CTransactions);
    }

    /**
     * @return bool|null Συναλλαγές B2G
     */
    public function getIsB2GTransactions(): ?bool
    {
        return $this->get('isB2GTransactions');
    }

    /**
     * @param  bool|null  $isB2GTransactions  Συναλλαγές B2G
     * @return static
     */
    public function setIsB2GTransactions(?bool $isB2GTransactions): static
    {
        return $this->set('isB2GTransactions', $isB2GTransactions);
    }

    /**
     * @return string|null ΑΦΜ Παρόχου
     */
    public function getProviderVatNumber(): ?string
    {
        return $this->get('providerVatNumber');
    }

    /**
     * @param  string  $providerVatNumber  ΑΦΜ Παρόχου
     * @return static
     */
    public function setProviderVatNumber(string $providerVatNumber): static
    {
        return $this->set('providerVatNumber', $providerVatNumber);
    }

    /**
     * @return string|null Αριθμός Αδείας Παρόχου
     */
    public function getProviderLicenceNumber(): ?string
    {
        return $this->get('providerLicenceNumber');
    }

    /**
     * @param  string  $providerLicenceNumber  Αριθμός Αδείας Παρόχου
     * @return static
     */
    public function setProviderLicenceNumber(string $providerLicenceNumber): static
    {
        return $this->set('providerLicenceNumber', $providerLicenceNumber);
    }

    /**
     * @return string|null Αριθμός Σύμβασης Παρόχου
     */
    public function getProviderContractNumber(): ?string
    {
        return $this->get('providerContractNumber');
    }

    /**
     * @param  string  $providerContractNumber  Αριθμός Σύμβασης Παρόχου
     * @return static
     */
    public function setProviderContractNumber(string $providerContractNumber): static
    {
        return $this->set('providerContractNumber', $providerContractNumber);
    }

    /**
     * @return string|null Ημερομηνία σύναψης σύμβασης οντότητας με τον Πάροχο
     */
    public function getProviderContractConclusionDate(): ?string
    {
        return $this->get('providerContractConclusionDate');
    }

    /**
     * Ημερομηνία σύναψης σύμβασης οντότητας με τον Πάροχο
     * Το πεδίο πρέπει να είναι σε UTC μορφή. Για παράδειγμα, : yyyy-MM-ddTHH:mm:ssZ
     *
     * @param  string  $providerContractConclusionDate  Ημερομηνία σύναψης σύμβασης οντότητας με τον Πάροχο
     * @return static
     */
    public function setProviderContractConclusionDate(string $providerContractConclusionDate): static
    {
        return $this->set('providerContractConclusionDate', $providerContractConclusionDate);
    }

    /**
     * @return string|null Ημερομηνία έναρξης ισχύος σύμβασης
     */
    public function getProviderContractActivationDate(): ?string
    {
        return $this->get('providerContractActivationDate');
    }

    /**
     * Ημερομηνία έναρξης ισχύος της σύμβασης
     * Το πεδίο πρέπει να είναι σε UTC μορφή. Για παράδειγμα, : yyyy-MM-ddTHH:mm:ssZ
     *
     * @param  string  $providerContractActivationDate  Ημερομηνία έναρξης ισχύος σύμβασης
     * @return static
     */
    public function setProviderContractActivationDate(string $providerContractActivationDate): static
    {
        return $this->set('providerContractActivationDate', $providerContractActivationDate);
    }

    /**
     * @return string|null Ημερομηνία έναρξης έκδοσης
     */
    public function getIssueStartDate(): ?string
    {
        return $this->get('issueStartDate');
    }

    /**
     * Ημερομηνία έναρξης έκδοσης στοιχείων για συναλλαγές
     * Το πεδίο πρέπει να έχει την ακόλουθη μορφή : YYYY-MM-DD
     *
     * @param  string|null  $issueStartDate  Ημερομηνία έναρξης έκδοσης
     * @return static
     */
    public function setIssueStartDate(?string $issueStartDate): static
    {
        return $this->set('issueStartDate', $issueStartDate);
    }

    /**
     * @return string|null Ημερομηνία διακοπής έκδοσης
     */
    public function getIssueStopDate(): ?string
    {
        return $this->get('issueStopDate');
    }

    /**
     * Ημερομηνία διακοπής έκδοσης στοιχείων για συναλλαγές
     * Το πεδίο πρέπει να έχει την ακόλουθη μορφή : YYYY-MM-DD
     *
     * @param  string|null  $issueStopDate  Ημερομηνία διακοπής έκδοσης
     * @return static
     */
    public function setIssueStopDate(?string $issueStopDate): static
    {
        return $this->set('issueStopDate', $issueStopDate);
    }

    /**
     * @return string|null Πάροχος Διαδικτύου Οντότητας-Εκδότη
     */
    public function getInternetProvider(): ?string
    {
        return $this->get('internetProvider');
    }

    /**
     * @param  string|null  $internetProvider  Πάροχος Διαδικτύου Οντότητας-Εκδότη
     * @return static
     */
    public function setInternetProvider(?string $internetProvider): static
    {
        return $this->set('internetProvider', $internetProvider);
    }

    /**
     * @return string|null Αριθμός Σύμβασης με Πάροχο Διαδικτύου
     */
    public function getInternetProviderContractNumber(): ?string
    {
        return $this->get('internetProviderContractNumber');
    }

    /**
     * @param  string|null  $internetProviderContractNumber  Αριθμός Σύμβασης με Πάροχο Διαδικτύου
     * @return static
     */
    public function setInternetProviderContractNumber(?string $internetProviderContractNumber): static
    {
        return $this->set('internetProviderContractNumber', $internetProviderContractNumber);
    }

    /**
     * @return string|null Ημερομηνία Σύμβασης με Πάροχο Διαδικτύου
     */
    public function getInternetProviderContractDate(): ?string
    {
        return $this->get('internetProviderContractDate');
    }

    /**
     * Ημερομηνία Σύμβασης Οντότητας-Εκδότη με Πάροχο Διαδικτύου
     * Το πεδίο πρέπει να είναι σε UTC μορφή. Για παράδειγμα, : yyyy-MM-ddTHH:mm:ssZ
     *
     * @param  string|null  $internetProviderContractDate  Ημερομηνία Σύμβασης με Πάροχο Διαδικτύου
     * @return static
     */
    public function setInternetProviderContractDate(?string $internetProviderContractDate): static
    {
        return $this->set('internetProviderContractDate', $internetProviderContractDate);
    }

    /**
     * Converts the statement into its XML representation.
     *
     * @param  bool  $asStatementDoc  If true, returns the full XML document. Otherwise, returns only the XML of the root statement node.
     * @return string The XML representation of the statement.
     */
    public function toXml(bool $asStatementDoc = false): string
    {
        $writer = new StatementDocWriter();
        $fullXml = $writer->asXML((new StatementDoc())->setStatement($this));

        if ($asStatementDoc) {
            return $fullXml;
        }

        $doc = $writer->getDomDocument();
        return $doc->saveXML($doc->getElementsByTagName('statement')->item(0));
    }
}
