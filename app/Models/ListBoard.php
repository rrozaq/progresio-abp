<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListBoard extends Model
{ 
    protected $table = 'lists';
    protected $guarded = [];

    public function board()
    {
        return $this->hasOne(Board::class, 'id', 'board_id');
    }

    public function card()
    {
        return $this->hasMany(Card::class, 'list_id', 'id');
    }
}