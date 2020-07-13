<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incubator_profile extends Model
{
    protected $table = 'profile_incubators';
    public $primaryKey = 'incubator_id';
    protected $guarded = [];
}