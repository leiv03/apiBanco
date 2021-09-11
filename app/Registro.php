<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $connection='apibank';
    protected $table='usuario';
    protected $primaryKey = "id";
    public $timestamps=false;
}