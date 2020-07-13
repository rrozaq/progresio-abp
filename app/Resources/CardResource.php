<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'title'             => $this->title,
            'description'       => $this->description,
            'berkas'            => $this->berkas,
            'urut'              => $this->urut,
            'craeted_at'        => $this->created_at,
        ];
    }
}