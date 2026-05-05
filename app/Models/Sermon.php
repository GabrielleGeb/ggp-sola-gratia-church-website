<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Sermon extends Model
{
    protected $fillable = ['tanggal', 'judul', 'pembicara', 'seri', 'isi', 'yt'];
    protected $casts = ['tanggal' => 'date'];
}