<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        // FIX: hasil query disimpan ke $jadwals dulu, baru dikirim ke view
        $jadwals = Jadwal::orderByRaw("
            CASE hari
                WHEN 'Minggu' THEN 1
                WHEN 'Senin'  THEN 2
                WHEN 'Selasa' THEN 3
                WHEN 'Rabu'   THEN 4
                WHEN 'Kamis'  THEN 5
                WHEN 'Jumat'  THEN 6
                WHEN 'Sabtu'  THEN 7
            END
        ")
        ->orderBy('jam')
        ->get();

        return view('pages.jadwal', compact('jadwals'));
    }

    // Simpan jadwal baru via form web
    public function storeWeb(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'hari' => 'required|string',
            'jam'  => 'required',
        ]);

        // Hanya ambil field yang diizinkan, bukan $request->all()
        Jadwal::create($request->only(['nama', 'hari', 'jam', 'tempat', 'keterangan']));

        return redirect()->route('jadwal')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    // Update jadwal via form web (PUT)
    public function updateWeb(Request $request, int $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'hari' => 'required|string',
            'jam'  => 'required',
        ]);

        $jadwal->update($request->only(['nama', 'hari', 'jam', 'tempat', 'keterangan']));

        return redirect()->route('jadwal')->with('success', 'Jadwal berhasil diperbarui!');
    }

    // Hapus jadwal via form web (DELETE)
    public function destroyWeb(int $id)
    {
        Jadwal::findOrFail($id)->delete();

        return redirect()->route('jadwal')->with('success', 'Jadwal berhasil dihapus!');
    }

    // ─── API methods (dipanggil dari ApiController, tetap dipertahankan) ───

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'hari'      => 'required|string',
            'jam'       => 'required',
            'tempat'    => 'nullable|string',
            'keterangan'=> 'nullable|string',
        ]);

        return Jadwal::create($validated);
    }

    public function update(Request $request, int $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'hari'      => 'required|string',
            'jam'       => 'required',
            'tempat'    => 'nullable|string',
            'keterangan'=> 'nullable|string',
        ]);

        $jadwal->update($validated);

        return $jadwal;
    }

    public function destroy(int $id)
    {
        Jadwal::destroy($id);
        return response()->json(['success' => true]);
    }
}