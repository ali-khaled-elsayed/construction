<?php

namespace App\Modules\Job\Resources;

use App\Models\BasicDescription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BasicDescriptionResource extends JsonResource
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
            'rooms_count' => $this->rooms_count,
            'wet_rooms_count' => $this->wet_rooms_count,
            'external_rooms_count' => $this->external_rooms_count,
            'has_garden' => $this->has_garden,
            'has_roof' => $this->has_roof,
            'area' => $this->area,
            'description' => $this->description,
        ];
    }
}
