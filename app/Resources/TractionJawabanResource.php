<?php
namespace App\Http\Resources;
use App\Http\Resources\SoalTractionResource;

use Illuminate\Http\Resources\Json\JsonResource;

class TractionJawabanResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'startup' => [
                'id'    => $this->startup->id,
                'nama'  => $this->startup->name,
                'managed_by'    => $this->startup->startup_profile->manager_name,
                'foto_profile'    => $this->startup->startup_profile->manager_profile_foto,
            ],
            'created_at'    => $this->created_at->diffForHumans()
        ];
    }
}