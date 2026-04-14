<?php

namespace App\Modules\JobHistory\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobHistoryResource extends JsonResource
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
            'job_id' => $this->job_id,
            'user_id' => $this->user_id,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ];
            }),
            'action' => $this->action,
            'action_label' => $this->getActionLabel(),
            'old_status' => $this->old_status,
            'new_status' => $this->new_status,
            'description' => $this->description,
            'formatted_description' => $this->getFormattedDescription(),
            'changes' => $this->changes,
            'is_status_change' => $this->isStatusChange(),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
