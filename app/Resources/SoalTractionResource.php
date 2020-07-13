<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SoalTractionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'pertanyaan'  => $this->pertanyaan,
        ];
    }
}