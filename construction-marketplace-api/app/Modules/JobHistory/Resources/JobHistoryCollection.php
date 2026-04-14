<?php

namespace App\Modules\JobHistory\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class JobHistoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($history) {
            return new JobHistoryResource($history);
        });
    }
}
