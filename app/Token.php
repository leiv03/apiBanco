<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $connection='apibank';
    protected $table='token';
    protected $primaryKey = "idUsuario";
    public $timestamps=false;
}