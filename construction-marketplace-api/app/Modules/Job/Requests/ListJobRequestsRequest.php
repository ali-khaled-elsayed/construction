<?php

namespace App\Modules\Job\Requests;

use App\Modules\Shared\Requests\BaseGetRequestValidator;

class ListJobRequestsRequest extends BaseGetRequestValidator
{
    public function rules(): array
    {
        $rules = [
            // 'title' => 'nullable|string',
        ];
        return array_merge(parent::rules(), $rules);
    }
}
