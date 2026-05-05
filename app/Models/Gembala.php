<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Gembala extends Model
{
    protected $table = 'gembala';
    protected $fillable = ['nama', 'jabatan', 'bio', 'foto'];
}