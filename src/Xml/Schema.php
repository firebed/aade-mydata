<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Xml;

use DOMDocument;
use Firebed\AadeMyData\Models\Invoice;
use LibXMLError;

/**
 * Validates XML against the myDATA XSD files shipped in the xsd folder.
 */
final class Schema
{
    /**
     * The path of a myDATA XSD by its name without the version, e.g. 'InvoicesDoc'.
     */
    public static function path(string $name): string
    {
        return dirname(__DIR__, 2).'/xsd/'.$name.'-'.Invoice::VERSION.'.xsd';
    }

    /**
     * Validates a document against a myDATA XSD, given by name without the version (e.g. 'InvoicesDoc').
     *
     * @return list<array{field: string|null, message: string|null}> The validation errors, empty when the document is valid
     */
    public static function validate(DOMDocument|string $xml, string $schema): array
    {
        $previous = libxml_use_internal_errors(true);
        libxml_clear_errors();

        try {
            if (is_string($xml)) {
                $document = new DOMDocument;
                $document->loadXML($xml);
                $xml = $document;
            }

            $xml->schemaValidate(self::path($schema));

            return array_map(self::error(...), libxml_get_errors());
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }
    }

    /**
     * @return array{field: string|null, message: string|null}
     */
    private static function error(LibXMLError $error): array
    {
        preg_match("/Element '(.+?)':( \[.*?])? (.+)/", $error->message, $matches);

        return [
            'field' => $matches[1] ?? null,
            'message' => isset($matches[3]) ? trim($matches[3]) : trim($error->message),
        ];
    }
}
