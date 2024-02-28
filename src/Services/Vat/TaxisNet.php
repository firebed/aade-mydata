<?php

namespace Firebed\AadeMyData\Services\Vat;

use SoapClient;
use SoapFault;
use SoapHeader;
use stdClass;
use Throwable;

class TaxisNet
{
    private const WSDL = 'https://www1.gsis.gr/wsaade/RgWsPublic2/RgWsPublic2?WSDL';
    private const XSD  = 'https://www1.gsis.gr/wsaade/RgWsPublic2/RgWsPublic2?xsd=1';

    private string $username;
    private string $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @throws VatException
     */
    public function handle(string $vatToSearch, string $vatCalledBy = null): ?VatEntity
    {
        if (blank($vatToSearch)) {
            throw new VatException("Please provide a VAT number");
        }

        try {
            $response = $this->request($vatToSearch, $vatCalledBy);

            $error = $response->error_rec;
            if (filled($error->error_code)) {
                if ($error->error_code === "RG_WS_PUBLIC_WRONG_AFM") {
                    return null;
                }

                throw new VatException(trim($error->error_descr));
            }

            return $this->parseResponse($response);
        } catch (Throwable $e) {
            throw new VatException($e->getMessage());
        }
    }

    /**
     * @throws VatException|SoapFault
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function request(string $vatToSearch, string $vatCalledBy = null)
    {
        $headers = $this->prepareHeaders($this->username, $this->password);

        $client = new SoapClient(self::WSDL, ['soap_version' => SOAP_1_2]);
        $client->__setSoapHeaders($headers);

        $response = $client->rgWsPublic2AfmMethod([
            'INPUT_REC' => [
                'afm_called_by'  => $vatCalledBy,
                'afm_called_for' => $vatToSearch
            ]
        ]);
        
        if (!isset($response->result->rg_ws_public2_result_rtType)) {
            $this->invalidResponse();
        }

        return $response->result->rg_ws_public2_result_rtType;
    }

    /**
     * @throws VatException
     */
    protected function invalidResponse()
    {
        throw new VatException("Invalid response from TaxisNet");
    }

    protected function prepareHeaders(string $username, string $password): SoapHeader
    {
        $header = new stdClass();
        $header->UsernameToken = new stdClass();
        $header->UsernameToken->Username = $username;
        $header->UsernameToken->Password = $password;

        return new SoapHeader(self::XSD, 'Security', $header);
    }

    protected function parseResponse(object $data): VatEntity
    {
        $rec = $data->basic_rec;

        $vat = new VatEntity();
        $vat->vatNumber = trim($rec->afm);
        $vat->tax_authority_id = trim($rec->doy);
        $vat->tax_authority_name = trim($rec->doy_descr);
        $vat->flag_description = trim($rec->i_ni_flag_descr);
        $vat->valid = trim($rec->deactivation_flag) === "1";
        $vat->validity_description = trim($rec->deactivation_flag_descr);
        $vat->firm_flag_description = trim($rec->firm_flag_descr);
        $vat->legalName = preg_replace('!\s+!', ' ', trim($rec->onomasia));
        $vat->commerce_title = trim($rec->commer_title);
        $vat->legal_status_description = trim($rec->legal_status_descr);
        $vat->street = trim($rec->postal_address);
        $vat->street_number = trim($rec->postal_address_no);
        $vat->postcode = trim($rec->postal_zip_code);
        $vat->city = trim($rec->postal_area_description);
        $vat->registration_date = trim($rec->regist_date);
        $vat->stop_date = trim($rec->stop_date);
        $vat->normal_vat = trim($rec->normal_vat_system_flag) === 'Y';

        $firms = wrapArray($data->firm_act_tab->item);

        foreach ($firms as $firm) {
            $vat->firms[] = [
                'code'             => trim($firm->firm_act_code),
                'description'      => trim($firm->firm_act_descr),
                'kind'             => trim($firm->firm_act_kind),
                'kind_description' => trim($firm->firm_act_kind_descr),
            ];
        }

        return $vat;
    }
}