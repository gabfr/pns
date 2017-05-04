<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Api\ApiBaseController;
use App\Repositories\Contracts\CityRepositoryContract;
use App\Http\Requests\Cities\ZipcodeRequest;

class CitiesController extends ApiBaseController
{

    protected $cityRepo;

    public function __construct(
        CityRepositoryContract $cityRepo
    )
    {
        $this->cityRepo = $cityRepo;
    }

    /**
     * Get all cities (filter by name or uf, if params exists)
     * @param  Request $request
     * @return Dingo\Api\Http\Response
     */
    public function all(Request $request)
    {

        $cities = $this->cityRepo->all(
            $request->only('uf','name')
        );

        return $this->response->collection(
            $cities, $this->getBasicTransformer()
        );
    }

    public function zipcode(ZipcodeRequest $request)
    {
        // URL: http://cep.republicavirtual.com.br/web_cep.php?cep=91010000&formato=json
        $zipcode = $request->get('zipcode', null);
        
        if (!is_numeric($zipcode)) {
            return $this->response->errorNotFound();
        }

        $searchResult = $this->cityRepo->searchZipCode($zipcode);

        if ($searchResult == null) {
            return $this->response->errorNotFound();
        }

        return $this->response->array($searchResult, $this->getBasicTransformer());
    }
}
