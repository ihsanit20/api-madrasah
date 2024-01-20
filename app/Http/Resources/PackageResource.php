<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
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
            "author_id"     => (int) ($this->author_id ?? 0),
            "is_active"     => (bool) ($this->is_active ?? 0),
            "description"   => (string) ($this->description ?? ""),
        ];
    }
}
