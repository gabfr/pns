<?php

namespace App\Repositories;

use App\Repositories\Contracts\CityRepositoryContract;
use App\City;

class CityRepository implements CityRepositoryContract
{

    /**
     * Get all cities (filter by name or uf, if params exists)
     * @param  String|null $name
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function all(array $filters = array())
    {
        extract($filters);

        $query = City::orderBy('name');

        // Filter by name
        if( isset($name) && !empty($name) ){
            $query = $query->where('name','like',"%{$name}%");
        }

        // Filter by UF (exact)
        if( isset($uf) && !empty($uf) ){
            $query = $query->where('uf',strtoupper($uf));
        }

        return $query->get();
    }

    public function searchZipCode($zipcode)
    {
        $client = new \GuzzleHttp\Client();
        $request = new \GuzzleHttp\Psr7\Request('GET', 'http://cep.republicavirtual.com.br/web_cep.php?cep=' . $zipcode . '&formato=json');
        $responseContent = null;
        
        $promise = $client->sendAsync($request)->then(function($response) use (&$responseContent) {
            if ($response->getStatusCode() == 200) {
                $responseContent = json_decode(\GuzzleHttp\Psr7\copy_to_string($response->getBody()), true);
            }
        });

        $objectToReturn = [
            'state' => null,
            'city' => null,
            'neighborhood' => null,
            'address_type' => null,
            'address' => null 
        ];

        $promise->wait();

        if ($responseContent == null || ($responseContent != null && $responseContent["resultado"] == "0")) {
            return null;
        }

        $objectToReturn['state'] = $responseContent['uf'];
        $objectToReturn['city'] = $responseContent['cidade'];
        $objectToReturn['neighborhood'] = $responseContent['bairro'];
        $objectToReturn['address_type'] = $responseContent['tipo_logradouro'];
        $objectToReturn['address'] = $responseContent['logradouro'];

        if (is_array($responseContent) && 
            array_key_exists("uf", $responseContent) && 
            array_key_exists("cidade", $responseContent) && 
            !empty($responseContent["uf"]) && 
            !empty($responseContent["cidade"])) {
            $city = \App\City::where('uf', $responseContent["uf"])->where('name', 'LIKE', $responseContent["cidade"])->first();
            if ($city) {
                $objectToReturn["city"] = $city->toArray();
            }
        }

        return $objectToReturn;
    }
}