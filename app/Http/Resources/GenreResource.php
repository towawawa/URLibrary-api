<?php

namespace App\Http\Resources;

use App\Models\UrlLibrary;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GenreResource extends JsonResource
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
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'updatedAt' => optional($this->updated_at)->format('Y-m-d H:i:s'),
            'hashTags' => UrlLibraryResource::collection($this->whenLoaded('urlLibraries')),
        ];
    }
}
