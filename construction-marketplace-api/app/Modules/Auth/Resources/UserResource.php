<?php

namespace App\Modules\Auth\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role->value,
            'email_verified_at' => $this->email_verified_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),

            // Load related data based on role
            'service_provider_profile' => when($this->role->value === 'service_provider', function () {
                return new ServiceProviderProfileResource($this->whenLoaded('serviceProviderProfile'));
            }),

            // Statistics
            'average_rating' => $this->average_rating,
            'total_ratings' => $this->total_ratings,
        ];
    }
}
