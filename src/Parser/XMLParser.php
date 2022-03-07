<?php

namespace Firebed\AadeMyData\Parser;

use Error;
use InvalidArgumentException;
use SimpleXMLElement;

trait XMLParser
{
    private static function parse(SimpleXMLElement $node): mixed
    {
        $parent = self::morph($node->getName());

        self::applyClassifications($node, $parent);

        for ($node->rewind(); $node->valid(); $node->next()) {
            $current = $node->current();

            if ($current === null) {
                throw new Error("Encountered an error while parsing XML.");
            }

            $parent->put($current->getName(), $node->hasChildren() ? self::parse($current) : (string)$current);
        }

        return $parent;
    }

    private static function morph(string $name): mixed
    {
        if (!array_key_exists($name, self::$class_map)) {
            throw new InvalidArgumentException;
        }
        
        return new self::$class_map[$name];
    }

    private static function applyClassifications(SimpleXMLElement $node, mixed $parent): void
    {
        if ($node->getName() === 'expensesClassification') {
            self::parseClassification($node, $parent, 'ecls');
        } else if ($node->getName() === 'incomeClassification') {
            self::parseClassification($node, $parent, 'icls');
        }
    }

    private static function parseClassification(SimpleXMLElement $node, mixed $parent, string $namespace): void
    {
        $type = $node->xpath("$namespace:classificationType");
        $category = $node->xpath("$namespace:classificationCategory");
        $amount = $node->xpath("$namespace:amount");

        if (!empty($type)) {
            $parent->put('classificationType', (string)$type[0]);
        }

        if (!empty($category)) {
            $parent->put('classificationCategory', (string)$category[0]);
        }

        $parent->put('amount', (string)$amount[0]);
    }
}