<?php

namespace App\Http\Resources;

use App\Models\DeviceType;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(\Illuminate\Http\Request $request): array
    {
        if( $this->dataFrom ) {
            $dataFrom = floatval($this->dataFrom->parameters->first()?->pivot?->value);
            $data = round(floatval($this->parameters->first()?->pivot?->value) - $dataFrom,2);
        } else {
            $data = $this->parameters->first()?->pivot?->value;
        }
        return [
            'number' => $this->number,
            'flat' => $this->location->flat,
            'address' => $this->location->address ?? '',
            'counter' => $this->counter,
            'name' => $this->client?->name,
            'personal_number' => $this->personal_number,
            'type' => $this->deviceType->name,
            'code' => $this->code,
            'last_answer' => ($this->last_answer) ? (new Carbon($this->last_answer))->format('d.m.Y') : '',
            'data' => $data
        ];
    }
}
