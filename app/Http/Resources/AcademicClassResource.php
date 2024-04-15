<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AcademicClassResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"                    => (int) ($this->id ?? 0),
            "author_id"             => (int) ($this->author_id ?? 0),
            "academic_session_id"   => (int) ($this->academic_session_id ?? 0),
            "department_class_id"   => (int) ($this->department_class_id ?? 0),
            "priority"              => (int) ($this->priority ?? 0),
            "is_active"             => (bool) ($this->is_active ?? false),
            "author"                => $this->whenLoaded('author'),
        ];
    }
}
