<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageFeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"                => (int) ($this->id ?? 0),
            "academic_class_id" => (int) ($this->academic_class_id ?? 0),
            "package_id"        => (int) ($this->package_id ?? 0),
            "fee_id"            => (int) ($this->fee_id ?? 0),
            "amount"            => (int) ($this->amount ?? 0),
            "fee"               => $this->whenLoaded('fee'),
        ];
    }
}
