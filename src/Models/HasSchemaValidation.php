<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

use DOMDocument;

trait HasSchemaValidation
{
    /**
     * @return list<array{field: string|null, message: string|null}> The validation errors, empty when the XML is valid
     */
    protected function validateSchema(string $xml, string $xsdFile): array
    {
        libxml_use_internal_errors(true);
        libxml_clear_errors();

        $dom = new DOMDocument;
        $dom->loadXML($xml);
        $dom->schemaValidate(__DIR__."/../../xsd/$xsdFile");

        return array_map(function ($error) {
            preg_match("/Element '(.+?)':( \[.*?])? (.+)/", $error->message, $matches);

            return [
                'field' => $matches[1] ?? null,
                'message' => $matches[3] ?? null,
            ];
        }, libxml_get_errors());
    }
}
