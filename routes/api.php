<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::post('/login', [ApiController::class, 'login']);
Route::post('/change-password', [ApiController::class, 'changePassword']);

Route::get('/renungan', [ApiController::class, 'renunganIndex']);
Route::post('/renungan', [ApiController::class, 'renunganStore']);
Route::put('/renungan/{id}', [ApiController::class, 'renunganUpdate']);
Route::delete('/renungan/{id}', [ApiController::class, 'renunganDestroy']);

Route::get('/sermon', [ApiController::class, 'sermonIndex']);
Route::post('/sermon', [ApiController::class, 'sermonStore']);
Route::put('/sermon/{id}', [ApiController::class, 'sermonUpdate']);
Route::delete('/sermon/{id}', [ApiController::class, 'sermonDestroy']);

Route::get('/jadwal', [ApiController::class, 'jadwalIndex']);
Route::post('/jadwal', [ApiController::class, 'jadwalStore']);
Route::put('/jadwal/{id}', [ApiController::class, 'jadwalUpdate']);
Route::delete('/jadwal/{id}', [ApiController::class, 'jadwalDestroy']);

Route::get('/pengumuman', [ApiController::class, 'pengumumanIndex']);
Route::post('/pengumuman', [ApiController::class, 'pengumumanStore']);
Route::put('/pengumuman/{id}', [ApiController::class, 'pengumumanUpdate']);
Route::delete('/pengumuman/{id}', [ApiController::class, 'pengumumanDestroy']);

Route::get('/gembala', [ApiController::class, 'gembalaIndex']);
Route::post('/gembala', [ApiController::class, 'gembalaStore']);
Route::put('/gembala/{id}', [ApiController::class, 'gembalaUpdate']);
Route::delete('/gembala/{id}', [ApiController::class, 'gembalaDestroy']);

Route::get('/kontak', [ApiController::class, 'kontakIndex']);
Route::post('/kontak', [ApiController::class, 'kontakStore']);
Route::put('/kontak/{id}', [ApiController::class, 'kontakUpdate']);
Route::delete('/kontak/{id}', [ApiController::class, 'kontakDestroy']);

Route::get('/jemaat', [ApiController::class, 'jemaatIndex']);
Route::post('/jemaat', [ApiController::class, 'jemaatStore']);
Route::put('/jemaat/{id}', [ApiController::class, 'jemaatUpdate']);
Route::delete('/jemaat/{id}', [ApiController::class, 'jemaatDestroy']);


Route::get('/visi-misi', [ApiController::class, 'visiMisiShow']);
Route::post('/visi-misi', [ApiController::class, 'visiMisiUpdate']);

Route::middleware('admin.token')->group(function () {

    Route::post('/renungan', [ApiController::class, 'renunganStore']);
    Route::put('/renungan/{id}', [ApiController::class, 'renunganUpdate']);
    Route::delete('/renungan/{id}', [ApiController::class, 'renunganDestroy']);

});