<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;        // ← add this line

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default admin password hash for "gbi2024"
        DB::table('settings')->insert([
            'key'   => 'admin_pass_hash',
            'value' => '2599e48ca230a2c1cd3b846f7dd9bf618a7f7d15f430efbfe8ed17d0206551e2',
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};