<?php

namespace App\Modules\Job\Resources;

use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Modules\Auth\Resources\UserResource;
use App\Modules\Job\Models\JobRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobRequestResource extends JsonResource
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
            'customer' => new UserResource($this->whenLoaded('customer')),
            'unit_type' => $this->unit_type,
            'job_type' => $this->job_type->value,
            'service_type' => $this->service_type->value,
            'description_type' => $this->description_type->value,
            // 'city' => new CityResource($this->whenLoaded('city')),
            // 'country' => new CountryResource($this->whenLoaded('country')),
            'address' => $this->address,
            // 'basic_description' => new BasicDescriptionResource($this->whenLoaded('basicDescription')),
            'basic_description' => new BasicDescriptionResource($this->basicDescription),
            //////////////////// 'rooms' => RoomResource::collection($this->rooms),
            'jobs' => JobResource::collection($this->jobs),
            // 'comments' => CommentResource::collection($this->whenLoaded('comments')),
            // 'ratings' => RatingResource::collection($this->whenLoaded('ratings')),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
