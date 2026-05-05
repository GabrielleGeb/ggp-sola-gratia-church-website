<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jadwal')->insert([
            [
                'nama' => 'Ibadah Raya Pagi I',
                'hari' => 'Minggu',
                'jam' => '08:00',
                'tempat' => 'Gereja Utama',
                'keterangan' => 'Ibadah utama yang diikuti oleh seluruh jemaat.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ibadah Raya Sore I',
                'hari' => 'Minggu',
                'jam' => '18:00',
                'tempat' => 'Gereja Utama',
                'keterangan' => 'Ibadah utama sore hari.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Doa Malam',
                'hari' => 'Selasa',
                'jam' => '19:00',
                'tempat' => 'Gereja Utama',
                'keterangan' => 'Ibadah doa dan perenungan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kamis Ceria',
                'hari' => 'Kamis',
                'jam' => '16:30',
                'tempat' => 'Gereja Utama',
                'keterangan' => 'Kegiatan Sekolah Minggu anak.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}