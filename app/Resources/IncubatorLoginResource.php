<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IncubatorLoginResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'access_token' => $this->access_token,
            'token_type' => $this->token_type,
            'expires_in' => $this->expires_in,
            'id' => $this->incubator->id,
            'email' => $this->incubator->email,
            'expired' => $this->incubator->expired,
            'status_pembayaran' => $this->incubator->status_pembayaran,
            'aktifasi' => $this->incubator->aktifasi,
        ];
    }
}