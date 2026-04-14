<?php

namespace App\Modules\Language\Requests;

use App\Modules\Shared\Requests\BaseGetRequestValidator;

class ListLanguagesRequest extends BaseGetRequestValidator
{
    public function rules(): array
    {
        $rules = [
            // 'title' => 'nullable|string',
        ];
        return array_merge(parent::rules(), $rules);
    }
}
