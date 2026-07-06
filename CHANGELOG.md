# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/),
and this project adheres to [Semantic Versioning](https://semver.org/).

## [Unreleased]

Support for the myDATA API **v2.0.2** (AADE sandbox / preofficial, June 2026).
The release focuses on the Digital Delivery Note and the new Receiving Note flow
(Δελτίο Ποσοτικής Παραλαβής, invoice types 10.1 / 10.2). All new fields are
optional and backwards compatible.

### Added

- `ConfirmDeliveryReturn` ERP request (`Http\DigitalGoodsMovement\ConfirmDeliveryReturn`) with its `DeliveryReturn` request model and `DeliveryReturnWriter`. Called by the issuer to declare completion of a movement on return; the response carries `deliveryReturnMark`.
- `CancelReceivingNote` provider request (`Http\CancelReceivingNote`) to cancel a Receiving Note (types 10.1 / 10.2) by `mark` + `entityVatNumber`.
- `ReceivingNotePurpose` enum (codes 1–7) for the receiving-note issue reason.
- `InvoiceHeader` fields: `receivingNotePurpose`, `otherReceivingNotePurposeTitle`, `nonObligatedRecipient`, `withoutDigitalTransportTracking`.
- `TransportDetails::packingsDeclaration` — an unbounded list of `PackagingDetail` for carrier packaging declarations.
- `Response::getDeliveryReturnMark()` for the digital-goods-movement response.
- `DeliveryStatus::IN_TRANSIT_RETURN` (9) and `DeliveryEventType::CONFIRM_RETURN` / `REGISTER_TRANSFER_RETURN`.
- `RequestDeliveryNoteStatus::handleUsingQrUrl()` to look up a delivery note status by `qrUrl` as an alternative to `mark`.
- `FuelCode` — fuel codes that were in the XSD but missing from the enum: `14`, `15` and `33`–`38`. The enum now covers all 29 codes from myDATA section 8.17 (previously such values were dropped when reading invoices).
- `ExpenseClassificationType` — codes `E3_881_001`–`E3_881_004` (Πωλήσεις για λογαριασμό Τρίτων), previously missing from the enum.

### Changed

- `InvoiceType::supportsDeliveryNote()` now also allows types `1.4`, `3.1`, `3.2` and `11.5`.
- Bundled XSD schemas updated to the v2.0.2 set (incl. the new `ConfirmDeliveryReturn-v2.0.2.xsd`).

### Fixed

- `InvoiceHeader` `$casts` key for `reverseDeliveryNotePurpose` was mis-cased (`ReverseDeliveryNotePurpose`), so an integer value was never cast to the enum.
- `FuelCode` label typos, verified against the v2.0.2 documentation: `Diesel Heatnn` → `Diesel Heating` (30), `Diesel Heat premium` → `Diesel Heating premium` (31), `Diesel Linht` → `Diesel Light` (32).
- Documentation example corrections (README delivery-note quick example, `request-docs`/`request-transmitted-docs` continuation examples, `ecr-token` example, date format) and several `@return` PHPDoc types on `Transport`.

## [5.10.0] - 2026-03-12

myDATA API **v2.0.1** support, including Digital Goods Movement (Ψηφιακή Διακίνηση Αγαθών) for the ERP channel.

### Added

- Digital Goods Movement — six HTTP methods: `RegisterTransfer`, `ConfirmDeliveryOutcome`, `RejectDeliveryNote` (by MARK or QR URL), `RequestDeliveryNoteStatus`, `GenerateGroupQrCode` and `RequestGroupQrDetails`.
- `ProvidersSignature` now supports the `EndToEndReferenceID` field and its XML serialization.

### Changed

- Upgraded all XSD schema files to v2.0.1.
- `Invoice` model now uses a version constant for schema validation.
- PHP 8.4 compatibility — fixed implicitly nullable parameter declarations across 25+ files.

### Removed

- Deprecated `TransportDetail` factory.

### Fixed

- `Invoice::setPackingsDeclarations()` ignored its parameter and always set `null`.
- `Invoice::setPaymentMethods()` used a loose `==` null check — changed to strict `===`.
- `MyDataRequest` — added a null-safe operator on `getResponse()?->getBody()`.
- `Type` no longer pushes `null` values.

## [5.9.0] - 2025-12-30

### Added

- `LiableUserCategory` enum.

### Changed

- Restructured the `ProviderType` and `RecallStatus` namespaces.

## [5.8.1] - 2025-12-15

Patch release.

## [5.8.0] - 2025-12-10

### Added

- Statements API — `SendStatement`, `RecallStatement` and `RequestStatements` methods.

## [5.7.0] - 2025-11-13

### Changed

- Implemented myDATA **v1.0.12** changes.
- Updated documentation links and versions to myDATA v1.0.12.

## [5.6.4] - 2025-08-25

### Fixed

- Incorrect classification amount after squashing invoice rows.

## [5.6.3] - 2025-08-22

### Changed

- Renamed "Stamp Duty" (Χαρτόσημο) references to "Digital Transaction Fee" (Ψηφιακό Τέλος συναλλαγής).

## [5.6.2] - 2025-08-07

### Fixed

- Expense and income classification categories now return the correct types.
- `EnumCacheTest` made compatible with PHP 8.3.

## [5.6.1] - 2025-08-07

### Fixed

- Method return types in the `InvoiceType` enum.

## [5.6.0] - 2025-07-02

myDATA API **v1.0.11** support.

### Added

- `qrUrl` returned on invoices from electronic-invoicing providers (usable to build a URL-type QR code pointing to an AADE page).
- New move purpose `20 - Μεταφορές – Ταχυμεταφορές`.
- New special invoice category `13 - Δυσχέρεια Συσχέτισης για προσυμπλήρωση Φ2 και Ε3`.
- New `transmissionFailure` value for providers (t + 2).
- New provider method `CancelDeliveryNote` for cancelling delivery documents.
- Digital Transaction Fee can be transmitted on payroll (17.1); types 11.5 and the FUEL format can be transmitted from ΦΗΜ.

### Changed

- Move purposes renamed: `7 - Επεξεργασία - Συναρμολόγηση - Αποσυναρμολόγηση`, `8 - Ενδοδιακίνηση`.
- Move purposes `6, 15, 16, 17, 18` can no longer be transmitted.
- "Stamp Duty" converted to "Digital Transaction Fee" (naming only).

## [5.5.4] - 2025-04-07

### Added

- `Invoice::unSquashInvoiceRows()` to restore an invoice to its original state.
- `Invoice::isSquashed()` to check whether an invoice is squashed.
- `InvoicesDocReader` to convert a transmitted `InvoicesDoc` XML invoice back into an invoice model.

## [5.5.3] - 2025-04-02

### Fixed

- Support invoice detail type in the squashing logic.

## [5.5.2] - 2025-03-12

### Fixed

- Added missing codes in the classifications config file.

## [5.5.0] - 2025-03-09

### Added

- Unit-of-measure support check per invoice type.
- Tax classification validation for tax types and categories.

## [5.4.1] - 2025-02-11

### Added

- Timeout handling for myDATA requests.

## [5.4.0] - 2025-01-26

### Added

- Support for sending income and expense classifications to myDATA.

## [5.3.0] - 2025-01-10

### Fixed

- Gross value calculation now ignores informational taxes.

## [5.2.5] - 2025-01-03

myDATA API **v1.0.10** support.

### Added

- New method for E3 record retrieval (by invoice or day).
- Fuel invoice transmission via the ERP channel (`fuelInvoice = true`).
- Dispatch note transmission (9.3) via ERP and providers (FUEL format).
- New XSD files (v1.0.10).

### Changed

- Allow a past `dispatchDate` with `transmissionFailure`.

### Removed

- Active business check for wholesale.

## [5.2.4] - 2024-11-19

### Added

- Handle empty `receptionEmails` in `Response` (sets a new `ReceptionEmails` object).

### Fixed

- `TypeArray::count()` now defaults to an empty array to avoid a potential issue.

## [5.2.2] - 2024-10-20

### Changed

- Added comments and fixed code in `HasIterator` and `TypeClassification`.

## [5.2.1] - 2024-10-16

### Changed

- `InvoicesDocWriter` document version set to v1.0.9.
- Better environment detection.

### Fixed

- Handle special characters in XML generation.

## [5.2.0] - 2024-10-12

### Changed

- Renamed `InvoiceSummary::setTotalInformationTaxAmount` to `setTotalInformationalTaxAmount` and the `$totalInformationalTaxAmount` property for consistency (possible low-probability breaking change).
- Changed property visibilities to `protected`.

## [5.1.4] - 2024-10-12

### Added

- Enum type-checking cache (with `enumCache` test coverage).
- `InvalidResponseException` thrown when the myDATA API response is empty.

### Fixed

- Validate `expensesClassification` and `incomeClassification` in the `set` method.
- Round total gross values inside the getter for consistency.

## [5.1.0] - 2024-08-27

### Fixed

- `RequestVatInfo::handle` — `$groupedPerDay` defaults to `false` for backwards compatibility.

## [5.0.0] - 2024-08-21

Major release.

### Added

- Squashing invoice rows (`Invoice::squashInvoiceRows()`).
- Invoice XML validation against XSD files (`Invoice::validate()`).
- Invoice XML preview (`Invoice::toXml()`).
- Model constructors that populate attributes from mixed array values.
- Fluent (chainable) model setters.

## [4.0.7] - 2024-06-07

### Fixed

- `setOtherMeasurementUnitQuantity` and `setOtherMeasurementUnitTitle`.

## [4.0.6] - 2024-02-29

### Fixed

- Null values are now removed from the generated XML.

## [4.0.5] - 2024-02-28

### Added

- Services for verifying the validity of a VAT number (TaxisNet and Vies registry lookup).

## [4.0.0] - 2024-02-25

Major release. See the upgrade guide for migrating from 3.x to 4.x.

## [3.1.6] - 2024-01-18

### Fixed

- Ensure simple values do not throw an exception.

## [3.1.5] - 2023-12-20

Patch release.

## [3.1.4] - 2023-02-01

### Added

- Ability to change the `GuzzleHttp\Client` verification (works around cURL error 60: SSL certificate problem).

## [3.1.3] - 2023-01-31

### Changed

- New developer test API key URL.

## [3.1.2] - 2023-01-18

Patch release.

## [3.1.1] - 2023-01-16

### Changed

- Updated the development URLs and the developer portal URL to match the official ones.

## [3.1.0] - 2022-10-08

Maintenance release.

## [3.0.2] - 2022-10-01

Patch release.

## [3.0.1] - 2022-10-01

Patch release.

## [3.0.0] - 2022-10-01

Major release (no published release notes).

## [2.0.0] - 2022-03-07

### Changed

- Migrated to PHP 8.1.
- Synchronized with myDATA v1.0.5.
- Introduced PHP enum cases instead of class constants.
- Better XML parsing.

## [1.0.1] - 2022-03-05

### Fixed

- Variable naming.

## [1.0.0] - 2022-01-28

Initial release.

[Unreleased]: https://github.com/firebed/aade-mydata/compare/v5.10.0...HEAD
[5.10.0]: https://github.com/firebed/aade-mydata/compare/v5.9.0...v5.10.0
[5.9.0]: https://github.com/firebed/aade-mydata/compare/v5.8.1...v5.9.0
[5.8.1]: https://github.com/firebed/aade-mydata/compare/v5.8.0...v5.8.1
[5.8.0]: https://github.com/firebed/aade-mydata/compare/v5.7.0...v5.8.0
[5.7.0]: https://github.com/firebed/aade-mydata/compare/v5.6.4...v5.7.0
[5.6.4]: https://github.com/firebed/aade-mydata/compare/v5.6.3...v5.6.4
[5.6.3]: https://github.com/firebed/aade-mydata/compare/v5.6.2...v5.6.3
[5.6.2]: https://github.com/firebed/aade-mydata/compare/v5.6.1...v5.6.2
[5.6.1]: https://github.com/firebed/aade-mydata/compare/v5.6.0...v5.6.1
[5.6.0]: https://github.com/firebed/aade-mydata/compare/v5.5.4...v5.6.0
[5.5.4]: https://github.com/firebed/aade-mydata/compare/v5.5.3...v5.5.4
[5.5.3]: https://github.com/firebed/aade-mydata/compare/v5.5.2...v5.5.3
[5.5.2]: https://github.com/firebed/aade-mydata/compare/v5.5.0...v5.5.2
[5.5.0]: https://github.com/firebed/aade-mydata/compare/v5.4.1...v5.5.0
[5.4.1]: https://github.com/firebed/aade-mydata/compare/v5.4.0...v5.4.1
[5.4.0]: https://github.com/firebed/aade-mydata/compare/v5.3.0...v5.4.0
[5.3.0]: https://github.com/firebed/aade-mydata/compare/v5.2.5...v5.3.0
[5.2.5]: https://github.com/firebed/aade-mydata/compare/v5.2.4...v5.2.5
[5.2.4]: https://github.com/firebed/aade-mydata/compare/v5.2.2...v5.2.4
[5.2.2]: https://github.com/firebed/aade-mydata/compare/v5.2.1...v5.2.2
[5.2.1]: https://github.com/firebed/aade-mydata/compare/v5.2.0...v5.2.1
[5.2.0]: https://github.com/firebed/aade-mydata/compare/v5.1.4...v5.2.0
[5.1.4]: https://github.com/firebed/aade-mydata/compare/v5.1.0...v5.1.4
[5.1.0]: https://github.com/firebed/aade-mydata/compare/v5.0.0...v5.1.0
[5.0.0]: https://github.com/firebed/aade-mydata/compare/v4.0.7...v5.0.0
[4.0.7]: https://github.com/firebed/aade-mydata/compare/v4.0.6...v4.0.7
[4.0.6]: https://github.com/firebed/aade-mydata/compare/v4.0.5...v4.0.6
[4.0.5]: https://github.com/firebed/aade-mydata/compare/v4.0.0...v4.0.5
[4.0.0]: https://github.com/firebed/aade-mydata/compare/v3.1.6...v4.0.0
[3.1.6]: https://github.com/firebed/aade-mydata/compare/v3.1.5...v3.1.6
[3.1.5]: https://github.com/firebed/aade-mydata/compare/v3.1.4...v3.1.5
[3.1.4]: https://github.com/firebed/aade-mydata/compare/v3.1.3...v3.1.4
[3.1.3]: https://github.com/firebed/aade-mydata/compare/v3.1.2...v3.1.3
[3.1.2]: https://github.com/firebed/aade-mydata/compare/v3.1.1...v3.1.2
[3.1.1]: https://github.com/firebed/aade-mydata/compare/v3.1.0...v3.1.1
[3.1.0]: https://github.com/firebed/aade-mydata/compare/v3.0.2...v3.1.0
[3.0.2]: https://github.com/firebed/aade-mydata/compare/v3.0.1...v3.0.2
[3.0.1]: https://github.com/firebed/aade-mydata/compare/v3.0.0...v3.0.1
[3.0.0]: https://github.com/firebed/aade-mydata/compare/v2.0.0...v3.0.0
[2.0.0]: https://github.com/firebed/aade-mydata/compare/v1.0.1...v2.0.0
[1.0.1]: https://github.com/firebed/aade-mydata/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/firebed/aade-mydata/releases/tag/v1.0.0
