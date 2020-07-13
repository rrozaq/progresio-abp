<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AwardResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'nama'      => $this->nama,
            'tahun'     => $this->tahun,
            'gambar'    => $this->gambar,
            'incubator' => [
                'id'    => $this->incubator->id ?? null,
                'nama'  => $this->incubator->name ?? null,
            ],
            'startup' => [
                'id'    => $this->startup->id ?? null,
                'nama'  => $this->startup->name ?? null,
            ]
        ];
    }
}