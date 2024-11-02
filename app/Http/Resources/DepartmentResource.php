<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"            => (int) ($this->id ?? 0),
            "name"          => (string) ($this->name ?? ""),
            "is_active"     => (bool) ($this->is_active ?? 0),
            "description"   => (string) ($this->description ?? ""),
            "author"        => [
                "id"   => (int) ($this->user->id ?? 0),
                "name" => (string) ($this->user->name ?? ""),
            ],
        ];
    }
}
