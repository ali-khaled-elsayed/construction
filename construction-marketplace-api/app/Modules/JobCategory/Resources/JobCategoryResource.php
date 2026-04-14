<?php

namespace App\Modules\JobCategory\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'translations' => $this->whenLoaded('translations', function () {
                return $this->translations->map(function ($translation) {
                    return [
                        'language_code' => $translation->language_code,
                        'name' => $translation->name,
                        'description' => $translation->description,
                    ];
                });
            }),
            'attributes' => $this->whenLoaded('attributes', function () {
                return $this->attributes->map(function ($attribute) {
                    return [
                        'id' => $attribute->id,
                        'code' => $attribute->code,
                        'name' => $attribute->name,
                        'type' => $attribute->type,
                        'options' => $attribute->options,
                        'is_required' => $attribute->is_required,
                        'sort_order' => $attribute->sort_order,
                        'attribute_options' => $attribute->options()->ordered()->get()->map(function ($option) {
                            return [
                                'id' => $option->id,
                                'code' => $option->code,
                                'name' => $option->name,
                                'sort_order' => $option->sort_order,
                            ];
                        }),
                        'attribute_translations' => $this->whenLoaded('translations', function () use ($attribute) {
                            return $attribute->translations->map(function ($translation) {
                                return [
                                    'language_code' => $translation->language_code,
                                    'name' => $translation->name,
                                    'description' => $translation->description,
                                ];
                            });
                        }),
                        'attribute_values' => $attribute->values->map(function ($value) {
                            return [
                                'id' => $value->id,
                                'value' => $value->value,
                            ];
                        }),
                    ];
                });
            }),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
