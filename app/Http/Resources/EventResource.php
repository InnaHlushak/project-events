<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'deadline' => $this->deadline->format('d-m-Y H:i'),
            'venue' => $this->venue,
            'description' => $this->description,
            'image' => asset("storage/$this->image"),
            'category_id' => $this->category_id,
            'category' => $this->category->name,

            'costs' => $this->costs->map(fn($cost) => [
                'name' => $cost->name,
                'price' => $cost->price,
                'full_cost' => $cost->fullCost,
            ]),
        ];
    }
}
