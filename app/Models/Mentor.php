<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{ 
    public $guarded = [];

    public function incubator()
    {
        return $this->hasOne(Incubator::class, 'id', 'incubator_id');
    }
}