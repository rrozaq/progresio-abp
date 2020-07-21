<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StartupResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'email'             => $this->email,
            'visible_password'  => $this->visible_password,
            'incubator_by'      => $this->incubator->name,
            'slug'              => $this->slug,
            'accept'            => $this->accept,
            'profile'           => [
                'lokasi' => $this->startup_profile->lokasi ?? null,
                'phone' => $this->startup_profile->phone ?? null,
                'website' => $this->startup_profile->website ?? null,
                'instagram' => $this->startup_profile->instagram ?? null,
                'facebook' => $this->startup_profile->facebook ?? null,
                'youtube' => $this->startup_profile->youtube ?? null,
                'users' => $this->startup_profile->users ?? null,
                'employe' => $this->startup_profile->employe ?? null,
                'since' => $this->startup_profile->since ?? null,
                'logo' => $this->startup_profile->logo ?? null,
                'about' => $this->startup_profile->about ?? null,
                'manager_name' => $this->startup_profile->manager_name ?? null,
                'manager_profile_foto' => $this->startup_profile->manager_profile_foto ?? null,
            ]
        ];
    }
}