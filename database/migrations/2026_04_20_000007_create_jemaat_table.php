<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jemaat', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kategori')->default('Dewasa'); // Dewasa, Pemuda, Remaja, Anak
            $table->string('hp')->nullable();
            $table->date('ttl')->nullable();
            $table->string('kota')->nullable();
            $table->string('baptis')->default('Belum Baptis');
            $table->string('alamat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jemaat');
    }
};