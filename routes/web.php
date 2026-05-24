<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\VisiMisiController;
use App\Http\Controllers\SermonController;


Route::get('/', function () {
    $visi = \App\Models\VisiMisi::where('tipe', 'visi')->orderBy('urutan')->get();
    $misi = \App\Models\VisiMisi::where('tipe', 'misi')->orderBy('urutan')->get();
    return view('pages.home', compact('visi', 'misi'));
})->name('home');

Route::get('/gembala', fn() => view('pages.gembala'))->name('gembala');

Route::get('/renungan', fn() => view('pages.renungan'))->name('renungan');
Route::get('/sermon', [SermonController::class, 'index'])->name('sermon');
Route::get('/pengumuman', fn() => view('pages.pengumuman'))->name('pengumuman');

Route::get('/pastoral', fn() => view('pages.pastoral'))->name('pastoral');

Route::redirect('/jemaat', '/admin/jemaat')->name('jemaat');
Route::get('/admin/{tab?}', function (?string $tab = null) {
    $map = [
        'renungan' => 'renungan',
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

Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal');
Route::post('/jadwal/simpan', [JadwalController::class, 'storeWeb'])->name('jadwal.storeWeb');
Route::put('/jadwal/{id}', [JadwalController::class, 'updateWeb'])->name('jadwal.updateWeb');
Route::delete('/jadwal/{id}', [JadwalController::class, 'destroyWeb'])->name('jadwal.destroyWeb');

Route::get('/visi-misi', [VisiMisiController::class, 'index'])->name('visi-misi');
Route::post('/visi-misi/simpan', [VisiMisiController::class, 'store'])->name('visi-misi.store');
Route::put('/visi-misi/{id}', [VisiMisiController::class, 'update'])->name('visi-misi.update');
Route::delete('/visi-misi/{id}', [VisiMisiController::class, 'destroy'])->name('visi-misi.destroy');