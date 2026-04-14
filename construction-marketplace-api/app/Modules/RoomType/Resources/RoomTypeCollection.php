<?php

namespace App\Modules\RoomType\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RoomTypeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($roomType) {
            return new RoomTypeResource($roomType);
        });
    }
}
