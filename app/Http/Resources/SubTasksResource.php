<?php

namespace App\Http\Resources;

//use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubTasksResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => (string)$this->id,
            'type' => 'Sub Task',
            'attributes' => [
                'title' => $this->title,
                'status' => $this->status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ]
        ];
    }
}
