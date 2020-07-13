<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Traction extends Model
{ 
    public $guarded = [];

    public function soal()
    {
        return $this->hasMany(SoalTraction::class, 'judul_id');
    }

    public function jawaban()
    {
        return $this->hasMany(TractionJawaban::class, 'traction_id');
    }
}