<?php

namespace App\Modules\Job\Resources;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
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
            'room_type' => [
                'id' => $this->roomType->id,
                'name' => $this->roomType->name,
            ],
            'area' => $this->area,
        ];
    }
}
