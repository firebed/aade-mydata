<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\Traits\HasRequestDom;
use Firebed\AadeMyData\Http\Traits\HasResponseDom;
use Firebed\AadeMyData\Models\Model;
use Firebed\AadeMyData\Xml\XMLReader;
use Firebed\AadeMyData\Xml\XMLWriter;

abstract class MyDataXmlRequest extends MyDataRequest
{
    use HasRequestDom;
    use HasResponseDom;

    /**
     * @template TDoc of Model
     *
     * @param  XMLWriter<mixed>  $writer
     * @param  XMLReader<TDoc>  $reader
     * @param  array<string, mixed>|null  $query
     * @return TDoc
     *
     * @throws MyDataException
     */
    protected function request(XMLWriter $writer, XMLReader $reader, mixed $data, ?array $query = null): Model
    {
        $requestXML = $writer->asXML($data);
        $this->requestDom = $writer->getDomDocument();

        return $this->read($this->post($query, $requestXML), $reader);
    }
}
