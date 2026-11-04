<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Exceptions;

/**
 * The request XML does not match the myDATA XSD, so it was not sent
 * (see Connection::withValidation()).
 */
class SchemaValidationException extends MyDataException
{
    /**
     * @param  list<array{field: string|null, message: string|null}>  $errors
     */
    public function __construct(private readonly string $schema, private readonly array $errors)
    {
        $lines = array_map(
            fn (array $error) => $error['field'] === null ? (string) $error['message'] : self::localName($error['field']).': '.$error['message'],
            $errors,
        );

        parent::__construct("The request does not match the myDATA schema $schema:".PHP_EOL.implode(PHP_EOL, $lines));
    }

    /**
     * The XSD the request was validated against, e.g. 'InvoicesDoc'.
     */
    public function getSchema(): string
    {
        return $this->schema;
    }

    /**
     * @return list<array{field: string|null, message: string|null}> The field, with its namespace as libxml reports it ('{http://www.aade.gr/myDATA/invoice/v1.0}aa'), and the message of each error
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    private static function localName(string $field): string
    {
        $end = strrpos($field, '}');

        return $end === false ? $field : substr($field, $end + 1);
    }
}
