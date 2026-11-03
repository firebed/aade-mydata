<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Xml;

use DOMDocument;
use DOMNode;
use RuntimeException;

final class Dom
{
    /**
     * The XML of a document, or of one of its nodes, as a string. DOMDocument::saveXML()
     * returns false when libxml cannot serialize the tree; that is an error, not an empty document.
     */
    public static function save(DOMDocument $document, ?DOMNode $node = null): string
    {
        $xml = $document->saveXML($node);

        if ($xml === false) {
            throw new RuntimeException('The XML document could not be serialized.');
        }

        return $xml;
    }
}
