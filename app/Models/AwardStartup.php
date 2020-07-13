<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AwardStartup extends Model
{ 
    protected $table = 'startup_awards';
    protected $guarded = [];

    public function startup()
    {
        return $this->hasOne(Startup::class, 'id', 'startup_id');
    }
}