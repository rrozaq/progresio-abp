<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{ 
    protected $guarded = [];

    public function incubator()
    {
        return $this->hasOne(Incubator::class, 'id', 'incubator_id');
    }
}