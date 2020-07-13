<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenan extends Model
{ 
    protected $guarded = [];

    public function kategori()
    {
        return $this->hasOne(KategoriTenan::class, 'id', 'kategori_id');
    }
}