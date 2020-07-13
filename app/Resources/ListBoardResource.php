<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CardResource;

class ListBoardResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'nama'              => $this->nama,
            'urut'             => $this->urut,
            'card'              => CardResource::collection($this->card),
            'board' => [
                'id'    => $this->board->id,
                'nama'  => $this->board->nama,
            ]
        ];
    }
}