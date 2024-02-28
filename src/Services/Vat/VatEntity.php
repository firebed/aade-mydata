<?php

namespace Firebed\AadeMyData\Services\Vat;

use JetBrains\PhpStorm\ArrayShape;

class VatEntity
{
    /**
     * @var string|null ΑΦΜ
     */
    public ?string $vatNumber = null;

    /**
     * @var ?string Κωδικός ΔΟΥ
     */
    public ?string $tax_authority_id = null;

    /**
     * @var ?string Περιγραφή ΔΟΥ
     */
    public ?string $tax_authority_name = null;

    /**
     * @var ?string Ένδειξη εάν πρόκειται για Φυσικό Πρόσωπο ή Μη Φυσικό Πρόσωπο
     */
    public ?string $flag_description = null;

    /**
     * @var bool true εάν ο Α.Φ.Μ. είναι ενεργός, false για απενεργοποιημένος
     */
    public ?bool $valid = null;

    /**
     * @var ?string Περιγραφή Ενεργός ή Ανενεργός
     */
    public ?string $validity_description = null;

    /**
     * @var ?string Ένδειξη εάν πρόκειται για επιτηδευματία, μη επιτηδευματία ή πρώην επιτηδευματία
     */
    public ?string $firm_flag_description = null;

    /**
     * @var ?string Επωνυμία Επιχείρησης
     */
    public ?string $legalName = null;

    /**
     * @var ?string Τίτλος Επιχείρησης
     */
    public ?string $commerce_title = null;

    /**
     * @var ?string ΠΕΡΙΓΡΑΦΗ ΜΟΡΦΗΣ ΜΗ Φ.Π.
     */
    public ?string $legal_status_description = null;

    /**
     * @var ?string Διεύθυνση Έδρας Επιχείρησης (Οδός)
     */
    public ?string $street = null;

    /**
     * @var ?string Διεύθυνση Έδρας Επιχείρησης (Αριθμός οδού)
     */
    public ?string $street_number = null;

    /**
     * @var ?string Διεύθυνση Έδρας Επιχείρησης (ΤΚ)
     */
    public ?string $postcode = null;

    /**
     * @var ?string Διεύθυνση Έδρας Επιχείρησης (Πόλη)
     */
    public ?string $city = null;

    /**
     * @var ?string Ημερομηνία Έναρξης Επιχείρησης yyyy-mm-dd
     */
    public ?string $registration_date = null;

    /**
     * @var ?string Ημερομηνία Διακοπής Επιχείρησης yyyy-mm-dd
     */
    public ?string $stop_date = null;

    /**
     * @var bool Ένδειξη Κανονικού Καθεστώτος Φ.Π.Α.
     */
    public ?bool $normal_vat = null;

    /**
     * @var array Δραστηριότητες Επιχείρησης
     */
    #[ArrayShape([['code' => 'string', 'description' => 'string', 'kind' => 'string', 'kind_description' => 'string']])]
    public array $firms = [];
}