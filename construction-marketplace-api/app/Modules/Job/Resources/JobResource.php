<?php

namespace App\Modules\Job\Resources;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
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
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ],
            'room' => new RoomResource($this->room),
            'fee_amount' => $this->fee_amount,
            'size' => $this->size->value,
            'description' => $this->description,
            'urgency' => $this->urgency->value,
            'status' => $this->status->value,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
