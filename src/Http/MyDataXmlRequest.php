<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Exceptions\SchemaValidationException;
use Firebed\AadeMyData\Http\Traits\HasRequestDom;
use Firebed\AadeMyData\Http\Traits\HasResponseDom;
use Firebed\AadeMyData\Models\Model;
use Firebed\AadeMyData\Xml\Schema;
use Firebed\AadeMyData\Xml\XMLReader;
use Firebed\AadeMyData\Xml\XMLWriter;

abstract class MyDataXmlRequest extends MyDataRequest
{
    use HasRequestDom;
    use HasResponseDom;

    /**
     * The myDATA XSD the request body follows, by name without the version (e.g. 'InvoicesDoc'),
     * checked before sending when the connection validates requests (Connection::withValidation()).
     */
    protected const ?string SCHEMA = null;

    /**
     * @template TDoc of Model
     *
     * @param  XMLWriter<mixed>  $writer
     * @param  XMLReader<TDoc>  $reader
     * @param  array<string, mixed>|null  $query
     * @return TDoc
     *
     * @throws SchemaValidationException|MyDataException
     */
    protected function request(XMLWriter $writer, XMLReader $reader, mixed $data, ?array $query = null): Model
    {
        $requestXML = $writer->asXML($data);
        $this->requestDom = $writer->getDomDocument();

        if (static::SCHEMA !== null && $this->connection()->validatesRequests() && ($errors = Schema::validate($this->requestDom, static::SCHEMA)) !== []) {
            throw new SchemaValidationException(static::SCHEMA, $errors);
        }

        return $this->read($this->post($query, $requestXML), $reader);
    }
}
