<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MentorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'nama'      => $this->nama,
            'posisi'     => $this->posisi,
            'gambar'     => $this->gambar,
            'incubator' => [
                'id'    => $this->incubator->id,
                'name'  => $this->incubator->name,
            ]
        ];
    }
}