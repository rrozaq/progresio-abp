<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TractionJawaban extends Model
{ 
    protected $table = 'traction_jawaban';
    public $guarded = [];

    public function tractionjawaban()
    {
        return $this->hasOne(SoalTraction::class, 'id', 'traction_soal_id');
    }

    public function startup()
    {
        return $this->hasOne(Startup::class, 'id', 'startup_id');
    }
}