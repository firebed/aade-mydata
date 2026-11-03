<?php

declare(strict_types=1);

namespace Tests;

use Firebed\AadeMyData\Models\Error;
use Firebed\AadeMyData\Models\Errors;
use Firebed\AadeMyData\Models\Model;
use Firebed\AadeMyData\Models\PaymentMethodDetail;
use Firebed\AadeMyData\Models\PaymentMethods;
use Firebed\AadeMyData\Models\ReceptionEmails;
use Firebed\AadeMyData\Models\Response;
use Firebed\AadeMyData\Models\ResponseDoc;
use Firebed\AadeMyData\Xml\ResponseDocReader;
use PHPUnit\Framework\TestCase;

class ModelCollectionTest extends TestCase
{
    public function test_an_empty_doc_can_be_iterated(): void
    {
        $this->assertSame([], iterator_to_array(new ResponseDoc));
        $this->assertCount(0, new ResponseDoc);
        $this->assertTrue((new ResponseDoc)->isEmpty());
    }

    public function test_first_and_last_return_null_on_an_empty_doc(): void
    {
        $this->assertNull((new ResponseDoc)->first());
        $this->assertNull((new ResponseDoc)->last());
    }

    public function test_a_parsed_doc_without_children_is_empty(): void
    {
        $doc = (new ResponseDocReader)->parseXML('<?xml version="1.0" encoding="UTF-8"?><ResponseDoc xmlns="http://www.aade.gr/myDATA/invoice/v1.0"/>');

        $this->assertCount(0, $doc);
        $this->assertSame([], iterator_to_array($doc));
        $this->assertNull($doc->first());
    }

    public function test_the_child_key_comes_from_the_list_cast(): void
    {
        $this->assertSame('paymentMethodDetails', (new PaymentMethods)->childKey());
        $this->assertSame('email', (new ReceptionEmails)->childKey());
        $this->assertSame('response', (new ResponseDoc)->childKey());
    }

    public function test_it_is_built_from_a_list_a_single_item_or_the_array_form(): void
    {
        $a = new PaymentMethodDetail(['type' => 3, 'amount' => 10]);
        $b = new PaymentMethodDetail(['type' => 5, 'amount' => 20]);

        $this->assertSame([$a, $b], (new PaymentMethods([$a, $b]))->all());
        $this->assertSame([$a], (new PaymentMethods($a))->all());
        $this->assertSame([], (new PaymentMethods([]))->all());

        $fromArray = new PaymentMethods(['paymentMethodDetails' => [['type' => 3, 'amount' => 10]]]);
        $this->assertCount(1, $fromArray);
        $this->assertSame(10.0, $fromArray->first()->getAmount());
    }

    public function test_a_single_item_given_as_attributes_is_one_item(): void
    {
        $methods = new PaymentMethods(['type' => 3, 'amount' => 10]);

        $this->assertCount(1, $methods);
        $this->assertSame(10.0, $methods->first()->getAmount());
    }

    public function test_offset_set_casts_like_push_and_drops_empty_values(): void
    {
        $emails = new ReceptionEmails(['a@test.gr']);
        $emails[0] = 'b@test.gr';
        $emails[5] = '';

        $this->assertSame(['b@test.gr'], $emails->all());

        $errors = Model::lenient(function () {
            $errors = new Errors;
            $errors[0] = ['code' => '1', 'message' => 'x'];

            return $errors;
        });
        $this->assertSame('1', $errors->first()->getCode());
    }

    public function test_items_are_cast_to_the_declared_type(): void
    {
        $methods = new PaymentMethods([['type' => 3, 'amount' => '10.5']]);

        $this->assertInstanceOf(PaymentMethodDetail::class, $methods[0]);
        $this->assertSame(10.5, $methods[0]->getAmount());

        $emails = new ReceptionEmails(['a@test.gr', 'b@test.gr']);
        $this->assertSame(['a@test.gr', 'b@test.gr'], $emails->all());
    }

    public function test_set_with_a_single_value_appends_and_with_a_list_replaces(): void
    {
        $doc = new ResponseDoc;
        $doc->set('response', $first = new Response(['index' => 1]));
        $doc->set('response', $second = new Response(['index' => 2]));

        $this->assertSame([$first, $second], $doc->all());

        $doc->set('response', [$second]);
        $this->assertSame([$second], $doc->all());

        $doc->set('response', null);
        $this->assertTrue($doc->isEmpty());
    }

    public function test_push_add_and_array_access(): void
    {
        $errors = new Errors;
        $errors->set('error', [$a = new Error(['code' => '1']), $b = new Error(['code' => '2'])]);
        $errors->add($c = new Error(['code' => '3']));
        $errors[] = $d = new Error(['code' => '4']);

        $this->assertSame([$a, $b, $c, $d], $errors->all());
        $this->assertSame($a, $errors->first());
        $this->assertSame($d, $errors->last());
        $this->assertTrue(isset($errors[1]));
        $this->assertNull($errors[9]);

        unset($errors[0]);
        $this->assertSame([$b, $c, $d], $errors->all());
        $this->assertSame(['2', '3', '4'], $errors->map(fn (Error $e) => $e->getCode()));
        $this->assertSame([$c], $errors->filter(fn (Error $e) => $e->getCode() === '3'));
    }

    public function test_to_array_and_json_use_the_child_key(): void
    {
        $methods = new PaymentMethods([new PaymentMethodDetail(['type' => 3, 'amount' => 10])]);

        $this->assertSame(['paymentMethodDetails' => [['type' => 3, 'amount' => 10.0]]], $methods->toArray());
        $this->assertSame('{"paymentMethodDetails":[{"type":3,"amount":10.0}]}', $methods->toJson());
    }
}
