<?php

declare(strict_types=1);

namespace Tests;

use Firebed\AadeMyData\Casts\Arr;
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\DeliveryStatus;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\TransmissionFailure;
use Firebed\AadeMyData\Exceptions\InvalidAttributeException;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryNoteStatusResponse;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\Location;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\PackagingDetail;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\PackingsDeclaration;
use Firebed\AadeMyData\Models\IncomeClassification;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Models\InvoiceHeader;
use Firebed\AadeMyData\Models\Model;
use Firebed\AadeMyData\Models\Response;
use PHPUnit\Framework\TestCase;

class ModelCastsTest extends TestCase
{
    public function test_casts_are_resolved_from_the_casts_method(): void
    {
        $invoice = new Invoice;

        $this->assertTrue($invoice->cast('invoiceHeader')->isModel());
        $this->assertTrue($invoice->cast('paymentMethods')->isCollection());
        $this->assertTrue($invoice->cast('invoiceDeliveryStatus')->isEnum());
        $this->assertTrue($invoice->cast('invoiceDetails')->list);
        $this->assertSame(InvoiceDetails::class, $invoice->cast('invoiceDetails')->type);
        $this->assertNull($invoice->cast('nonexistent'));
    }

    public function test_scalar_casts_come_from_the_getter_return_type(): void
    {
        $response = new Response(['index' => '3']);
        $this->assertSame(3, $response->getIndex());
        $this->assertTrue($response->cast('index')->isScalar());

        $header = new InvoiceHeader(['aa' => 12, 'selfPricing' => 'true', 'exchangeRate' => '1.5']);
        $this->assertSame('12', $header->getAa());
        $this->assertTrue($header->isSelfPricing());
        $this->assertSame(1.5, $header->getExchangeRate());
    }

    public function test_enum_values_are_cast_from_their_backing_value(): void
    {
        $header = new InvoiceHeader(['invoiceType' => '1.1']);
        $this->assertSame(InvoiceType::TYPE_1_1, $header->getInvoiceType());

        $header->setInvoiceType(InvoiceType::TYPE_2_1);
        $this->assertSame(InvoiceType::TYPE_2_1, $header->getInvoiceType());

        $invoice = new Invoice(['transmissionFailure' => TransmissionFailure::ERP_CONNECTION_FAILURE]);
        $this->assertSame(1, $invoice->getTransmissionFailure());
    }

    public function test_an_enum_case_name_is_accepted_when_the_enum_supports_it(): void
    {
        $status = new DeliveryNoteStatusResponse(['status' => 'InTransit (Return)']);

        $this->assertSame(DeliveryStatus::IN_TRANSIT_RETURN, $status->getStatus());
    }

    public function test_an_invalid_enum_value_throws(): void
    {
        $this->expectException(InvalidAttributeException::class);

        new InvoiceHeader(['invoiceType' => '99.9']);
    }

    public function test_an_invalid_value_reads_as_null_in_lenient_mode(): void
    {
        $header = Model::lenient(fn () => new InvoiceHeader(['invoiceType' => '99.9', 'series' => 'A']));

        $this->assertNull($header->getInvoiceType());
        $this->assertSame('A', $header->getSeries());

        // strict again afterwards
        $this->expectException(InvalidAttributeException::class);
        $header->setInvoiceType('99.9');
    }

    public function test_an_empty_string_means_no_value(): void
    {
        $classification = new IncomeClassification(['classificationType' => '', 'amount' => '']);

        $this->assertNull($classification->getClassificationType());
        $this->assertNull($classification->getAmount());
    }

    public function test_a_list_attribute_collects_single_values_and_replaces_with_a_list(): void
    {
        $header = new InvoiceHeader;
        $header->set('correlatedInvoices', '400001');
        $header->set('correlatedInvoices', 400002);

        $this->assertSame([400001, 400002], $header->getCorrelatedInvoices());

        $header->setCorrelatedInvoices([400003]);
        $this->assertSame([400003], $header->getCorrelatedInvoices());

        $header->setCorrelatedInvoices(null);
        $this->assertNull($header->getCorrelatedInvoices());
    }

    public function test_a_list_of_models_is_built_from_arrays(): void
    {
        $row = new InvoiceDetails(['incomeClassification' => [
            ['classificationType' => 'E3_561_001', 'classificationCategory' => 'category1_1', 'amount' => '10'],
            ['classificationType' => 'E3_561_002', 'classificationCategory' => 'category1_1', 'amount' => 5],
        ]]);

        $this->assertCount(2, $row->getIncomeClassification());
        $this->assertSame(IncomeClassificationType::E3_561_001, $row->getIncomeClassification()[0]->getClassificationType());
        $this->assertSame(10.0, $row->getIncomeClassification()[0]->getAmount());
    }

    public function test_empty_list_items_are_left_out(): void
    {
        $header = Model::lenient(fn () => new InvoiceHeader(['correlatedInvoices' => ['', '400001', 'x'], 'otherCorrelatedEntities' => ['']]));

        $this->assertSame([400001], $header->getCorrelatedInvoices());
        $this->assertSame([], $header->getOtherCorrelatedEntities());

        $header->push('correlatedInvoices', '');
        $header->push('multipleConnectedMarks', '');
        $this->assertSame([400001], $header->getCorrelatedInvoices());
        $this->assertNull($header->getMultipleConnectedMarks());
    }

    public function test_a_decimal_string_is_not_an_int_backed_enum_value(): void
    {
        $this->assertNull(Model::lenient(fn () => new InvoiceHeader(['specialInvoiceCategory' => '1.5']))->getSpecialInvoiceCategory());

        $this->expectException(InvalidAttributeException::class);
        new InvoiceHeader(['specialInvoiceCategory' => '1.5']);
    }

    public function test_a_model_with_a_positional_constructor_is_built_from_attributes(): void
    {
        $declaration = new PackingsDeclaration([['packagingType' => 1, 'quantity' => '3']]);

        $this->assertInstanceOf(PackagingDetail::class, $declaration->first());
        $this->assertSame(3, $declaration->first()->getQuantity());

        $location = Location::fromArray(['latitude' => '40.1', 'longitude' => '22.5']);
        $this->assertSame(40.1, $location->getLatitude());
    }

    public function test_a_collection_attribute_accepts_a_plain_list(): void
    {
        $invoice = new Invoice(['packingsDeclarations' => [new PackagingDetail(quantity: 2)]]);

        $this->assertInstanceOf(PackingsDeclaration::class, $invoice->get('packingsDeclarations'));
        $this->assertCount(1, $invoice->getPackingsDeclarations());
    }

    public function test_a_wrong_type_throws(): void
    {
        $this->expectException(InvalidAttributeException::class);
        $this->expectExceptionMessage("Attribute 'issuer' expects Firebed\AadeMyData\Models\Issuer, 'nope' given.");

        new Invoice(['issuer' => 'nope']);
    }

    public function test_an_unknown_cast_type_throws(): void
    {
        $this->expectException(InvalidAttributeException::class);

        $model = new class extends Model
        {
            protected function casts(): array
            {
                return ['x' => Arr::of('nope')];
            }
        };

        $model->set('x', 1);
    }

    public function test_to_array_round_trips_through_the_constructor(): void
    {
        $invoice = Invoice::factory()->make();
        $copy = new Invoice($invoice->toArray());

        $this->assertSame($invoice->toArray(), $copy->toArray());
        $this->assertSame($invoice->toXml(), $copy->toXml());
    }

    public function test_json(): void
    {
        $header = new InvoiceHeader(['series' => 'A', 'aa' => 1, 'invoiceType' => InvoiceType::TYPE_1_1]);

        $this->assertSame('{"series":"A","aa":"1","invoiceType":"1.1"}', $header->toJson());
        $this->assertSame($header->toArray(), json_decode(json_encode($header), true));
    }
}
