<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IncubatorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'email'             => $this->email,
            'visible_password'  => $this->visible_password,
            'status'            => $this->status,
            'aktifasi'          => $this->aktifasi,
            'incubator_code'    => $this->incubator_code,
            'slug'              => $this->slug,
            'profile'           => [
                'lokasi' => $this->incubator_profile->lokasi,
                'phone' => $this->incubator_profile->phone,
                'website' => $this->incubator_profile->website,
                'instagram' => $this->incubator_profile->instagram,
                'facebook' => $this->incubator_profile->facebook,
                'youtube' => $this->incubator_profile->youtube,
                'since' => $this->incubator_profile->since,
                'logo' => $this->incubator_profile->logo,
                'about' => $this->incubator_profile->about,
                'manager_name' => $this->incubator_profile->manager_name,
                'manager_profile_foto' => $this->incubator_profile->manager_profile_foto,
            ]
        ];
    }
}