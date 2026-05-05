<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
 
class Renungan extends Model
{
    protected $table = 'renungan';
    protected $fillable = ['tanggal', 'judul', 'ayat', 'isi', 'penulis'];
    protected $casts = ['tanggal' => 'date'];
}
 
