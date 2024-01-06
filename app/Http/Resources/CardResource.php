<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(\Illuminate\Http\Request $request): array
    {
//        ($this->last_answer) ? (new Carbon($this->last_answer))->format('d.m.Y') : '',
        return [
            'id' => $this->id,
            'flat' => $this->location->flat,
            'address' => $this->location->address ?? '',
            'name' => $this->name ?? '',
            'datecheck' => $this->device->date_check,
            'dateown' => $this->date_own,
            'comment' => $this->comment
        ];
    }
}
