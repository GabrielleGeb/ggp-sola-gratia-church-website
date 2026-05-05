<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class KontakPastoral extends Model
{
    protected $fillable = ['nama', 'jabatan', 'hp'];
    protected $table = 'kontak_pastoral';
}