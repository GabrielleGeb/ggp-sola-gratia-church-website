<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gembala', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jabatan')->nullable();
            $table->text('bio')->nullable();
            $table->longText('foto')->nullable(); // base64 image data
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gembala');
    }
};