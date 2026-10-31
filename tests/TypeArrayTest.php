<?php

namespace Tests;

use Firebed\AadeMyData\Models\ResponseDoc;
use Firebed\AadeMyData\Xml\ResponseDocReader;
use PHPUnit\Framework\TestCase;

class TypeArrayTest extends TestCase
{
    public function test_an_empty_doc_can_be_iterated(): void
    {
        $items = [];
        foreach (new ResponseDoc() as $item) {
            $items[] = $item;
        }

        $this->assertSame([], $items);
    }

    public function test_first_returns_null_on_an_empty_doc(): void
    {
        $this->assertNull((new ResponseDoc())->first());
    }

    public function test_a_parsed_doc_without_children_is_empty(): void
    {
        $doc = (new ResponseDocReader())->parseXML('<?xml version="1.0" encoding="UTF-8"?><ResponseDoc xmlns="http://www.aade.gr/myDATA/invoice/v1.0"/>');

        $this->assertCount(0, $doc);
        $this->assertSame([], iterator_to_array($doc));
        $this->assertNull($doc->first());
    }

    public function test_pushing_an_array_into_an_empty_doc_merges_it(): void
    {
        $doc = new ResponseDoc();
        $doc->push('response', ['a', 'b']);

        $this->assertSame(['a', 'b'], $doc->all());
    }
}
