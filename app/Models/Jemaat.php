<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Jemaat extends Model
{
    protected $table = 'jemaat';
    protected $fillable = ['nama', 'kategori', 'hp', 'ttl', 'kota', 'baptis', 'alamat'];
    protected $casts = ['ttl' => 'date'];
}
