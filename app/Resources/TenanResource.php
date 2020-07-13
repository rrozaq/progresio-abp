<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TenanResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'nama'      => $this->nama,
            'logo'      => $this->logo,
            'kategori'  => [
                'id'    => $this->kategori->id,
                'nama'    => $this->kategori->nama,
            ]
        ];
    }
}