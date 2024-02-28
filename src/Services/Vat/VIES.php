<?php

namespace Firebed\AadeMyData\Services\Vat;

use SoapClient;
use Throwable;

class VIES
{    
    private const ENDPOINT = "https://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl";

    /**
     * @throws VatException
     */
    public function handle(string $countryCode, string $vatNumber): ?VatEntity
    {
        try {
            $response = $this->request($countryCode, $vatNumber);
            
            if (!$response) {
                return null;
            }
            
            $vat = new VatEntity();
            $vat->vatNumber = $response->vatNumber;
            $vat->valid = $response->valid;
            $vat->legalName = $response->name;

            # street street_number         postcode - city
            $address = trim(beforeLast($response->address, ' - ')); // street street_number         postcode
            $street = trim(beforeLast($address, ' ')); // street street_number

            $vat->postcode = trim(afterLast($address, ' '));            
            $vat->street = trim(beforeLast($street, ' '));            
            $vat->street_number = trim(afterLast($street, ' '));
            $vat->city = trim(afterLast($response->address, ' - '));
            return $vat;
        } catch (Throwable $e) {
            throw new VatException($e->getMessage());
        }
    }

    /** @noinspection PhpUndefinedMethodInspection */
    protected function request(string $countryCode, string $vatNumber)
    {
        $client = new SoapClient(self::ENDPOINT);
        $response = $client->checkVat(compact('countryCode', 'vatNumber'));
        
        if (!$response->valid) {
            return false;
        }

        return $response;
    }
}