<?php

namespace App\Modules\Shared\Requests;

class BaseRequest
{
    public $baseGetParameters = ['limit', 'offset', 'sort', 'sortBy'];

    public function constructBaseGetQuery($queryParameters)
    {
        $criteria = [];
        foreach ($this->baseGetParameters as $parameter) {
            if (array_key_exists($parameter, $queryParameters)) {
                $criteria[$parameter] = data_get($queryParameters, $parameter);
            }
        }
        return $criteria;
    }

    public function getFilters()
    {
        return [];
    }

    public function setFilters($requestFilters)
    {
        $finalFilters = [];
        foreach ($requestFilters as $key => $value) {
            if (array_key_exists($key, $this->getFilters())) {
                $finalFilters[$this->getFilters()[$key]] = $value;
            }
        }
        return $finalFilters;
    }
}
