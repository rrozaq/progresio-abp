<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FounderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'nama'      => $this->nama,
            'posisi'    => $this->posisi,
            'gambar'    => $this->gambar,
        ];
    }
}