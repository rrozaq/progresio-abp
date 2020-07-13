<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{ 
    protected $guarded = [];

    public function startup()
    {
        return $this->hasOne(Startup::class, 'id', 'startup_id');
    }
}