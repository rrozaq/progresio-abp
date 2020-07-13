<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Startup_profile extends Model
{
    protected $table = 'profile_startups';
    public $primaryKey = 'startup_id';
    protected $guarded = [];

    public function startup_profile()
    {
        return $this->hasOne(Startup_profile::class);
    }
}