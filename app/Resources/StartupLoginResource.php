<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StartupLoginResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'access_token' => $this->access_token,
            'token_type' => $this->token_type,
            'expires_in' => $this->expires_in,
            'id' => $this->startup->id,
            'name' => $this->startup->name,
            'email' => $this->startup->email,
            'incubator_id' => $this->startup->incubator_id,
            'profile'           => [
                'lokasi' => $this->startup->startup_profile->lokasi,
                'phone' => $this->startup->startup_profile->phone,
                'website' => $this->startup->startup_profile->website,
                'instagram' => $this->startup->startup_profile->instagram,
                'facebook' => $this->startup->startup_profile->facebook,
                'youtube' => $this->startup->startup_profile->youtube,
                'users' => $this->startup->startup_profile->users,
                'employe' => $this->startup->startup_profile->employe,
                'since' => $this->startup->startup_profile->since,
                'logo' => $this->startup->startup_profile->logo,
                'about' => $this->startup->startup_profile->about,
                'manager_name' => $this->startup->startup_profile->manager_name,
                'manager_profile_foto' => $this->startup->startup_profile->manager_profile_foto,
            ]
        ];
    }
}