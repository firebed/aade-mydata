<?php

namespace Firebed\AadeMyData\Parser;

use Error;
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

            // If the current node has children then iterate through them recursively,
            // otherwise put the tag's name with its value to the corresponding object
            // of class Type as a property.
            $parent->put($current->getName(), $node->hasChildren() ? self::parse($current) : (string)$current);
        }

        return $parent;
    }

    private static function morph(string $name): mixed
    {
        $type = self::$class_map[$name];
        return new $type;
    }

    private static function applyClassifications(SimpleXMLElement $node, object $parent): void
    {
        if ($node->getName() === 'expensesClassification') {
            self::parseClassification($node, $parent, 'ecls');
        } else if ($node->getName() === 'incomeClassification') {
            self::parseClassification($node, $parent, 'icls');
        }
    }

    private static function parseClassification(SimpleXMLElement $node, object $parent, string $namespace): void
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