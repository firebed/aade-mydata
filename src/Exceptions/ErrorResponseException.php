<?php

namespace Firebed\AadeMyData\Exceptions;

use DOMDocument;
use Firebed\AadeMyData\Models\Error;
use Firebed\AadeMyData\Models\Errors;
use Firebed\AadeMyData\Xml\ResponseDocReader;
use Throwable;

/**
 * myDATA answered with an error document (ResponseDoc with errors) instead of
 * the response the request expects, either with HTTP 200 or with an HTTP error.
 */
class ErrorResponseException extends TransmissionFailedException
{
    /**
     * @param int|null $code The HTTP status of an HTTP error; otherwise the code of the first error is used
     */
    public function __construct(private readonly Errors $errors, ?int $code = null, ?Throwable $previous = null)
    {
        $all = $errors->all();
        $message = implode(PHP_EOL, array_map(fn (Error $error) => $error->getCode() ? "{$error->getCode()}: {$error->getMessage()}" : $error->getMessage(), $all));

        parent::__construct($message ?: 'myDATA returned an error response.', $code ?? (int) ($all[0] ?? null)?->getCode(), $previous);
    }

    /**
     * The errors of every response in a ResponseDoc, or null when the XML is something else.
     */
    public static function fromXml(string $xml, ?int $code = null, ?Throwable $previous = null): ?static
    {
        $document = new DOMDocument();
        $document->preserveWhiteSpace = false;

        return str_contains($xml, '<ResponseDoc') && @$document->loadXML($xml)
            ? static::fromDocument($document, $code, $previous)
            : null;
    }

    /**
     * The errors of every response in a ResponseDoc, or null when the document is something else.
     *
     * @param DOMDocument $document Loaded without whitespace nodes (preserveWhiteSpace = false)
     */
    public static function fromDocument(DOMDocument $document, ?int $code = null, ?Throwable $previous = null): ?static
    {
        if ($document->documentElement?->localName !== 'ResponseDoc') {
            return null;
        }

        $errors = new Errors();
        foreach ((new ResponseDocReader())->parseDocument($document)->all() as $response) {
            $errors->push('error', $response->getErrors()?->all() ?? []);
        }

        return new static($errors, $code, $previous);
    }

    public function getErrors(): Errors
    {
        return $this->errors;
    }
}
