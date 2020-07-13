<?php
namespace App\Http\Resources;
use App\Http\Resources\SoalTractionResource;
use Illuminate\Http\Resources\Json\JsonResource;
use DB;

class TractionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'judul'  => $this->judul,
            'description'  => $this->description,
            'pertanyaan' => SoalTractionResource::collection($this->soal),
            'jumlah_jawaban'    => $this->jawaban->count(),
            'created_at'    => $this->created_at->diffForHumans()
        ];
    }
}