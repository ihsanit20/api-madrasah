<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AcademicSubjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"        => (int) ($this->id ?? 0),
            "priority"  => (int) ($this->priority ?? 0),
            "is_active" => (boolean) ($this->is_active ?? false),

            "author_id" => (int) ($this->author_id ?? 0),
            "author"    => $this->whenLoaded('author'),
            
            "academic_class_id" => (int) ($this->academic_class_id ?? 0),
            "academic_class"    => $this->whenLoaded('academic_class'),
            
            "department_class_subject_id"   => (int) ($this->department_class_subject_id ?? 0),
            "department_class_subject"      => $this->whenLoaded('department_class_subject'),
        ];
    }
}
