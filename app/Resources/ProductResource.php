<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'deskripsi'      => $this->deskripsi,
            'nama'      => $this->nama,
            'gambar'      => $this->gambar,
        ];
    }
}