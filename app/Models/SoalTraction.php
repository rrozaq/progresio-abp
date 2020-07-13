<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoalTraction extends Model
{ 
    protected $table = 'soal_tractions';
    public $guarded = [];

    public function traction()
    {
        return $this->hasOne(Traction::class, 'id', 'judul_id');
    }
}