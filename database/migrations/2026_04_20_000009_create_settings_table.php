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
            'value' => 'f05bd8112c70151704c00302fc800b5b935f84b82fe787570f9fb9cdf01535d3',
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};