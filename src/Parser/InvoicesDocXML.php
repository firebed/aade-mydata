<?php

namespace Firebed\AadeMyData\Parser;

use Firebed\AadeMyData\Models\ExpensesClassificationType;
use Firebed\AadeMyData\Models\IncomeClassificationType;
use Firebed\AadeMyData\Models\InvoicesDoc;
use Firebed\AadeMyData\Models\Type;
use SimpleXMLElement;

class InvoicesDocXML
{
    public function asXML(InvoicesDoc $invoicesDoc): string
    {
        $xmlns = 'xmlns="http://www.aade.gr/myDATA/invoice/v1.0"';
        $xsi = 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"';
        $schemaLocation = 'xsi:schemaLocation="http://www.aade.gr/myDATA/invoice/v1.0/InvoicesDoc-v0.6.xsd"';
        $icls = 'xmlns:icls="https://www.aade.gr/myDATA/incomeClassificaton/v1.0"';
        $ecls = 'xmlns:ecls="https://www.aade.gr/myDATA/expensesClassificaton/v1.0"';

        $xml = new SimpleXMLElement("<InvoicesDoc $xmlns $xsi $schemaLocation $icls $ecls/>");

        $this->toXML($xml, $invoicesDoc);

        return $xml->asXML();
    }

    /** @noinspection NullPointerExceptionInspection */
    private function toXML(SimpleXMLElement $parent, Type $type): void
    {
        foreach ($type->properties() as $key => $value) {
            if ($value === null) {
                continue;
            }

            if ($value instanceof Type) {
                $child = $parent->addChild($key);
                $this->toXML($child, $value);
                continue;
            }

            if (is_array($value)) {
                foreach ($value as $v) {
                    $child = $parent->addChild($key);
                    if ($v instanceof Type) {
                        $this->toXML($child, $v);
                    } else {
                        $child->addChild($key, $v);
                    }
                }
                continue;
            }

            if ($type instanceof IncomeClassificationType) {
                $parent->addChild("icls:$key", $value, "https://www.aade.gr/myDATA/incomeClassificaton/v1.0");
            } elseif ($type instanceof ExpensesClassificationType) {
                $parent->addChild("ecls:$key", $value, "https://www.aade.gr/myDATA/expensesClassificaton/v1.0");
            } else {
                $parent->addChild($key, is_bool($value) ? $this->getValue($value) : $value);
            }
        }
    }

    private function getValue($value): string
    {
        return $value ? 'true' : 'false';
    }
}