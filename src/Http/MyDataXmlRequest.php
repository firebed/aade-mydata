<?php

namespace Firebed\AadeMyData\Http;

use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\Traits\HasRequestDom;
use Firebed\AadeMyData\Http\Traits\HasResponseDom;
use Firebed\AadeMyData\Models\ResponseDoc;
use Firebed\AadeMyData\Xml\XMLReader;
use Firebed\AadeMyData\Xml\XMLWriter;

abstract class MyDataXmlRequest extends MyDataRequest
{
    use HasRequestDom;
    use HasResponseDom;

    /**
     * @throws MyDataException
     */
    protected function request(XMLWriter $writer, XMLReader $reader, mixed $data): ResponseDoc
    {
        // Create the request XML
        $requestXML = $writer->asXML($data);

        // Get the response XML
        $response = $this->post(body: $requestXML);
        $responseXML = $response->getBody()->getContents();

        // Parse the response XML
        $responseDoc = $reader->parseXML($responseXML);

        $this->responseDom = $reader->getDomDocument();
        $this->requestDom = $writer->getDomDocument();

        return $responseDoc;
    }
}