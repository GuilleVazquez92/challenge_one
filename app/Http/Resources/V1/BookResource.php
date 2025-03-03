<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'isbn' => $this->isbn,
            'author' => [
                'id' => $this->author->id,
                'name' => $this->author->name,
                'nationality' => $this->author->nationality,
            ],
            'published_date' => \Carbon\Carbon::parse($this->published_date)->format('Y-m-d'),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
