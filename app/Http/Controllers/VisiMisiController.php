<?php

namespace App\Http\Controllers;

use App\Models\VisiMisi;
use Illuminate\Http\Request;

class VisiMisiController extends Controller
{
    public function index()
    {
        $visi = VisiMisi::where('tipe', 'visi')->orderBy('urutan')->get();
        $misi = VisiMisi::where('tipe', 'misi')->orderBy('urutan')->get();
        return view('pages.visi-misi', compact('visi', 'misi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe'   => 'required|in:visi,misi',
            'konten' => 'required|string',
        ]);

        $urutan = VisiMisi::where('tipe', $request->tipe)->max('urutan') + 1;

        VisiMisi::create([
            'tipe'   => $request->tipe,
            'konten' => $request->konten,
            'urutan' => $urutan,
        ]);

        return redirect()->route('admin', ['tab' => 'visi'])->with('success', 'Item berhasil ditambahkan!');
    }

    public function update(Request $request, int $id)
    {
        $item = VisiMisi::findOrFail($id);

        $request->validate([
            'konten' => 'required|string',
        ]);

        $item->update(['konten' => $request->konten]);

        return redirect()->route('admin', ['tab' => 'visi'])->with('success', 'Item berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        VisiMisi::findOrFail($id)->delete();
        return redirect()->route('admin', ['tab' => 'visi'])->with('success', 'Item berhasil dihapus!');
    }
}