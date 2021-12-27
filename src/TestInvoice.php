<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace Firebed\AadeMyData;

use Firebed\AadeMyData\Models\Enums\IncomeClassificationCode;
use Firebed\AadeMyData\Models\InvoiceType;
use Firebed\AadeMyData\Models\AddressType;
use Firebed\AadeMyData\Models\Counterpart;
use Firebed\AadeMyData\Models\IncomeClassificationType;
use Firebed\AadeMyData\Models\InvoiceHeaderType;
use Firebed\AadeMyData\Models\InvoiceRowType;
use Firebed\AadeMyData\Models\InvoiceSummaryType;
use Firebed\AadeMyData\Models\Issuer;
use Firebed\AadeMyData\Models\PaymentMethodDetailType;

class TestInvoice extends InvoiceType
{
    public function __construct()
    {
        $issuer = new Issuer();
        $issuer->setVatNumber(146654575);
        $issuer->setCountry('GR');
        $issuer->setBranch(0);

        $counterpart = new Counterpart();
        $counterpart->setVatNumber(800853858);
        $counterpart->setCountry('GR');
        $counterpart->setBranch(0);
        $address = new AddressType();
        $address->setPostalCode(22222);
        $address->setCity("IRAKLIO");
        $counterpart->setAddress($address);

        $this->setIssuer($issuer);
        $this->setCounterpart($counterpart);
    }

    public function setHeaders(string $series, int $aa, string $date, string $invoice_type): void
    {
        $header = new InvoiceHeaderType();
        $header->setSeries($series);
        $header->setAa($aa);
        $header->setIssueDate($date);
        $header->setInvoiceType($invoice_type);
        $header->setCurrency('EUR');

        $this->setInvoiceHeader($header);
    }

    public function addPaymentMethodType(string $method, float $amount): void
    {
        $paymentMethod = new PaymentMethodDetailType();
        $paymentMethod->setType($method);
        $paymentMethod->setAmount($amount);

        $this->addPaymentMethod($paymentMethod);
    }

    public function setInvoiceSummaryType(float $total_net_value, float $total_vat_amount, float $total_gross_value, string $classification_type, string $classification_category, float $amount): void
    {
        $summary = new InvoiceSummaryType();
        $summary->setTotalNetValue($total_net_value);
        $summary->setTotalVatAmount($total_vat_amount);
        $summary->setTotalWithheldAmount(0);
        $summary->setTotalFeesAmount(0);
        $summary->setTotalStampDutyAmount(0);
        $summary->setTotalOtherTaxesAmount(0);
        $summary->setTotalDeductionsAmount(0);
        $summary->setTotalGrossValue($total_gross_value);

        $ic = new IncomeClassificationType();
        $ic->setClassificationType($classification_type);
        $ic->setClassificationCategory($classification_category);
        $ic->setAmount($amount);
        $summary->addIncomeClassification($ic);
        
        $this->setInvoiceSummary($summary);
    }

    public function addInvoiceRowType(int $lineNumber, float $net_value, int $vat_category_type, float $vat_amount, string $classification_type, string $classification_category, float $amount): void
    {
        $row = new InvoiceRowType();
        $row->setLineNumber($lineNumber);
        $row->setNetValue($net_value);
        $row->setVatCategory($vat_category_type);
        $row->setVatAmount($vat_amount);

        $classification = new IncomeClassificationType();
        $classification->setClassificationType($classification_type);
        $classification->setClassificationCategory($classification_category);
        $classification->setAmount($amount);
        $row->addIncomeClassification($classification);

        $this->addInvoiceRow($row);
    }
}