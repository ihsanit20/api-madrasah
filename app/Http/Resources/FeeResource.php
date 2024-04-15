<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeeResource extends JsonResource
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
            "name"      => (string) ($this->name ?? ""),
            "period"    => (int) ($this->period ?? 0),
            "author_id" => (int) ($this->author_id ?? 0),
            "author"    => $this->whenLoaded('author'),
        ];
    }
}
