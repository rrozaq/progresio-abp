<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BoardResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'nama'              => $this->nama,
            'deadline'        => $this->deadline,
            'startup' => [
                'id'    => $this->startup->id,
                'nama'  => $this->startup->name,
            ]
        ];
    }
}