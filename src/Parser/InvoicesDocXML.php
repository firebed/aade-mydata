<?php

namespace Firebed\AadeMyData\Parser;

use Firebed\AadeMyData\Models\ExpensesClassification;
use Firebed\AadeMyData\Models\IncomeClassification;
use Firebed\AadeMyData\Models\InvoiceExpensesClassification;
use Firebed\AadeMyData\Models\InvoiceIncomeClassification;
use Firebed\AadeMyData\Models\InvoicesDoc;
use Firebed\AadeMyData\Models\Type;
use SimpleXMLElement;

class InvoicesDocXML
{
    private const ICLS = 'https://www.aade.gr/myDATA/incomeClassificaton/v1.0';
    private const ECLS = 'https://www.aade.gr/myDATA/expensesClassificaton/v1.0';

    private array $class_map;

    public function __construct()
    {
        $this->class_map = require __DIR__ . '/../../config/class_map.php';
    }

    public function asXML(InvoicesDoc $invoicesDoc): string
    {
        $attributes = [
            'xmlns'              => "http://www.aade.gr/myDATA/invoice/v1.0",
            'xmlns:xsi'          => "http://www.w3.org/2001/XMLSchema-instance",
            'xsi:schemaLocation' => "http://www.aade.gr/myDATA/invoice/v1.0/InvoicesDoc-v0.6.xsd",
            'xmlns:icls'         => self::ICLS,
            'xmlns:ecls'         => self::ECLS
        ];

        $args = "";
        array_walk($attributes, static function ($v, $k) use (&$args) {
            $args .= "$k=\"$v\" ";
        });

        $xml = new SimpleXMLElement("<?xml version='1.0' encoding='UTF-8'?><InvoicesDoc $args/>");

        $this->toXML($xml, $invoicesDoc);

        return $xml->asXML();
    }

    private function toXML(?SimpleXMLElement $xml, Type $type): void
    {
        if ($xml === null) {
            return;
        }

        foreach ($type->attributes() as $key => $property) {
            if ($property instanceof Type) {
                $child = $this->addNode($xml, $this->getTypeName($property));
                $this->toXML($child, $property);
                continue;
            }

            if (!is_array($property)) {
                $this->addNode($xml, $key, $property, $type);
                continue;
            }

            foreach ($property as $value) {
                $child = $this->addNode($xml, $this->getTypeName($value));
                $this->toXML($child, $value);
            }
        }
    }

    private function addNode(?SimpleXMLElement $xml, string $key, mixed $value = null, Type $type = null): ?SimpleXMLElement
    {
        if ($xml === null) {
            return null;
        }

        return $xml->addChild(
            $this->getQualifiedName($key, $type),
            $this->getValue($value),
            $this->getNamespace($type)
        );
    }

    private function getTypeName(Type $type): string
    {
        return array_search(get_class($type), $this->class_map, true);
    }

    private function getQualifiedName($qualifiedName, $type = null): string
    {
        return match (true) {
            $this->isIncomeClassification($type)   => "icls:$qualifiedName",
            $this->isExpensesClassification($type) => "ecls:$qualifiedName",
            default                                => $qualifiedName
        };
    }

    private function getValue($value): string|null
    {
        return match (true) {
            is_bool($value) => $value ? 'true' : 'false',
            default         => $value
        };
    }

    private function getNamespace($type = null): string|null
    {
        return match (true) {
            $this->isIncomeClassification($type)   => self::ICLS,
            $this->isExpensesClassification($type) => self::ECLS,
            default                                => null
        };
    }

    private function isIncomeClassification($type): bool
    {
        return $type instanceof IncomeClassification || $type instanceof InvoiceIncomeClassification;
    }

    private function isExpensesClassification($type): bool
    {
        return $type instanceof ExpensesClassification || $type instanceof InvoiceExpensesClassification;
    }
}