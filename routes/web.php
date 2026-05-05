<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JadwalController;

Route::get('/', fn() => view('pages.home'))->name('home');
Route::redirect('/visi-misi', '/admin/visi')->name('visi-misi');
Route::redirect('/gembala', '/admin/gembala')->name('gembala');
Route::redirect('/renungan', '/admin/renungan')->name('renungan');
Route::redirect('/sermon', '/admin/sermon')->name('sermon');
Route::redirect('/pengumuman', '/admin/pengumuman')->name('pengumuman');
Route::get('/pastoral', fn() => view('pages.pastoral'))->name('pastoral');
Route::redirect('/jemaat', '/admin/jemaat')->name('jemaat');
Route::get('/admin/{tab?}', function (?string $tab = null) {
    $map = [
        'renungan' => 'renungan',
        'sermon' => 'sermon',
        'jadwal' => 'jadwal-admin',
        'pengumuman' => 'pengumuman-admin',
        'gembala' => 'gembala',
        'kontak' => 'kontak-admin',
        'jemaat' => 'jemaat-admin',
        'visi' => 'visi-admin',
        'setelan' => 'setelan',
    ];
    return view('pages.admin', ['adminTab' => $map[$tab] ?? 'renungan']);
})->name('admin');

// ─── Jadwal (web) ───────────────────────────────────────────────────────────
Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal');
Route::post('/jadwal/simpan', [JadwalController::class, 'storeWeb'])->name('jadwal.storeWeb');
Route::put('/jadwal/{id}', [JadwalController::class, 'updateWeb'])->name('jadwal.updateWeb');
Route::delete('/jadwal/{id}', [JadwalController::class, 'destroyWeb'])->name('jadwal.destroyWeb');