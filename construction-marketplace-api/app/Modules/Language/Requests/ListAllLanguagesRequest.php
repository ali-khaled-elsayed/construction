<?php

namespace App\Modules\Language\Requests;

use App\Modules\Shared\Requests\BaseRequest;

class ListAllLanguagesRequest extends BaseRequest
{
    public function getFilters()
    {
        return [
            // 'title' => 'title',
        ];
    }

    public function constructQueryCriteria(array $queryParameters)
    {
        $filters = $this->setFilters(data_get($queryParameters, 'filters'));
        return array_merge($this->constructBaseGetQuery($queryParameters), ['filters' => $filters]);
    }
}
