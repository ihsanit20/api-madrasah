<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"            => (int) ($this->id ?? 0),
            "name"          => (string) ($this->name ?? ""),
            "author_id"     => (int) ($this->id ?? 0),
            "is_active"     => (bool) ($this->is_active ?? 0),
            "description"   => $this->when($this->isResource(), (string) ($this->description ?? "")),
        ];
    }

    /**
     * Check if the resource is a single resource.
     *
     * @return bool
     */
    private function isResource(): bool
    {
        return $this->resource instanceof self;
    }
}
