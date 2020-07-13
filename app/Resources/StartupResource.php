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
                'lokasi' => $this->startup_profile->lokasi,
                'phone' => $this->startup_profile->phone,
                'website' => $this->startup_profile->website,
                'instagram' => $this->startup_profile->instagram,
                'facebook' => $this->startup_profile->facebook,
                'youtube' => $this->startup_profile->youtube,
                'users' => $this->startup_profile->users,
                'employe' => $this->startup_profile->employe,
                'since' => $this->startup_profile->since,
                'logo' => $this->startup_profile->logo,
                'about' => $this->startup_profile->about,
                'manager_name' => $this->startup_profile->manager_name,
                'manager_profile_foto' => $this->startup_profile->manager_profile_foto,
            ]
        ];
    }
}