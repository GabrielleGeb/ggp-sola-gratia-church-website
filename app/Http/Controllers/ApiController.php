<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Renungan;
use App\Models\Sermon;
use App\Models\Jadwal;
use App\Models\Pengumuman;
use App\Models\Gembala;
use App\Models\KontakPastoral;
use App\Models\Jemaat;
use App\Models\VisiMisi;

class ApiController extends Controller
{
    // ============================================================
    // MIDDLEWARE: Simple admin token check
    // All POST/PUT/DELETE routes require the header:
    //   X-Admin-Token: <token stored in DB or .env>
    // ============================================================

    private function checkAdmin(Request $request): bool
    {
        $token = $request->header('X-Admin-Token');

        $storedToken = \Illuminate\Support\Facades\DB::table('settings')
            ->where('key', 'admin_token')->value('value');

        $expiresAt = \Illuminate\Support\Facades\DB::table('settings')
            ->where('key', 'admin_token_expires')->value('value');

        // Token tidak ada
        if (!$token || !$storedToken) {
            return false;
        }

        // Token tidak cocok
        if (!hash_equals($storedToken, $token)) {
            return false;
        }

        // Expired
        if (!$expiresAt || now()->greaterThan($expiresAt)) {
            return false;
        }

        return true;
    }

    private function unauthorized(): JsonResponse
    {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // ============================================================
    // AUTH: Login & Token Management
    // ============================================================

    public function login(Request $request): JsonResponse
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $storedHash = \Illuminate\Support\Facades\DB::table('settings')
            ->where('key', 'admin_pass_hash')->value('value')
            ?? config('app.default_admin_hash');

        if ($username !== 'admin' || hash('sha256', $password) !== $storedHash) {
            return response()->json(['error' => 'Username atau password salah.'], 401);
        }

        // Generate a session token valid for 8 hours
        $token = bin2hex(random_bytes(32));
        $expires = now()->addHours(8)->toDateTimeString();

        \Illuminate\Support\Facades\DB::table('settings')
            ->updateOrInsert(['key' => 'admin_token'], ['value' => $token]);
        \Illuminate\Support\Facades\DB::table('settings')
            ->updateOrInsert(['key' => 'admin_token_expires'], ['value' => $expires]);

        return response()->json(['token' => $token, 'expires' => $expires]);
    }

    public function changePassword(Request $request): JsonResponse
    {
        if (!$this->checkAdmin($request)) return $this->unauthorized();

        $newHash = $request->input('new_hash'); // sha256 hash from frontend
        if (!$newHash || strlen($newHash) !== 64) {
            return response()->json(['error' => 'Hash tidak valid'], 422);
        }

        \Illuminate\Support\Facades\DB::table('settings')
            ->updateOrInsert(['key' => 'admin_pass_hash'], ['value' => $newHash]);

        return response()->json(['success' => true]);
    }

    // ============================================================
    // RENUNGAN
    // ============================================================

    public function renunganIndex(): JsonResponse
    {
        return response()->json(Renungan::orderBy('tanggal', 'desc')->get());
    }

    public function renunganStore(Request $request): JsonResponse
    {
        if (!$this->checkAdmin($request)) return $this->unauthorized();
        $data = $request->validate([
            'tanggal' => 'required|date',
            'judul'   => 'required|string|max:255',
            'ayat'    => 'nullable|string|max:255',
            'isi'     => 'required|string',
            'penulis' => 'nullable|string|max:255',
        ]);
        return response()->json(Renungan::create($data), 201);
    }

    public function renunganUpdate(Request $request, int $id): JsonResponse
    {
        if (!$this->checkAdmin($request)) return $this->unauthorized();
        $item = Renungan::findOrFail($id);
        $data = $request->validate([
            'tanggal' => 'required|date',
            'judul'   => 'required|string|max:255',
            'ayat'    => 'nullable|string|max:255',
            'isi'     => 'required|string',
            'penulis' => 'nullable|string|max:255',
        ]);
        $item->update($data);
        return response()->json($item);
    }

    public function renunganDestroy(Request $request, int $id): JsonResponse
    {
        if (!$this->checkAdmin($request)) return $this->unauthorized();
        Renungan::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    // ============================================================
    // SERMON
    // ============================================================

    public function sermonIndex(): JsonResponse
    {
        return response()->json(Sermon::orderBy('tanggal', 'desc')->get());
    }

    public function sermonStore(Request $request): JsonResponse
    {
        if (!$this->checkAdmin($request)) return $this->unauthorized();
        $data = $request->validate([
            'tanggal'   => 'required|date',
            'judul'     => 'required|string|max:255',
            'pembicara' => 'nullable|string|max:255',
            'seri'      => 'nullable|string|max:255',
            'isi'       => 'required|string',
            'yt'        => 'nullable|string|max:500',
        ]);
        return response()->json(Sermon::create($data), 201);
    }

    public function sermonUpdate(Request $request, int $id): JsonResponse
    {
        if (!$this->checkAdmin($request)) return $this->unauthorized();
        $item = Sermon::findOrFail($id);
        $data = $request->validate([
            'tanggal'   => 'required|date',
            'judul'     => 'required|string|max:255',
            'pembicara' => 'nullable|string|max:255',
            'seri'      => 'nullable|string|max:255',
            'isi'       => 'required|string',
            'yt'        => 'nullable|string|max:500',
        ]);
        $item->update($data);
        return response()->json($item);
    }

    public function sermonDestroy(Request $request, int $id): JsonResponse
    {
        if (!$this->checkAdmin($request)) return $this->unauthorized();
        Sermon::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    // ============================================================
    // JADWAL
    // ============================================================

    public function jadwalIndex(): JsonResponse
{
    return response()->json(
        Jadwal::orderByRaw("
            CASE hari
                WHEN 'Minggu' THEN 1
                WHEN 'Senin' THEN 2
                WHEN 'Selasa' THEN 3
                WHEN 'Rabu' THEN 4
                WHEN 'Kamis' THEN 5
                WHEN 'Jumat' THEN 6
                WHEN 'Sabtu' THEN 7
            END
        ")
        ->orderBy('jam')
        ->get()
    );
}

    public function jadwalStore(Request $request): JsonResponse
    {
        if (!$this->checkAdmin($request)) return $this->unauthorized();
        $data = $request->validate([
            'nama'       => 'required|string|max:255',
            'hari'       => 'required|string|max:50',
            'jam'        => 'required|string|max:10',
            'tempat'     => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);
        return response()->json(Jadwal::create($data), 201);
    }

    public function jadwalUpdate(Request $request, int $id): JsonResponse
    {
        if (!$this->checkAdmin($request)) return $this->unauthorized();
        $item = Jadwal::findOrFail($id);
        $data = $request->validate([
            'nama'       => 'required|string|max:255',
            'hari'       => 'required|string|max:50',
            'jam'        => 'required|string|max:10',
            'tempat'     => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);
        $item->update($data);
        return response()->json($item);
    }

    public function jadwalDestroy(Request $request, int $id): JsonResponse
    {
        if (!$this->checkAdmin($request)) return $this->unauthorized();
        Jadwal::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    // ============================================================
    // PENGUMUMAN
    // ============================================================

    public function pengumumanIndex(): JsonResponse
    {
        return response()->json(
            Pengumuman::orderByDesc('penting')->orderByDesc('tanggal')->get()
        );
    }

    public function pengumumanStore(Request $request): JsonResponse
    {
        if (!$this->checkAdmin($request)) return $this->unauthorized();
        $data = $request->validate([
            'tanggal' => 'required|date',
            'judul'   => 'required|string|max:255',
            'isi'     => 'required|string',
            'penting' => 'boolean',
        ]);
        return response()->json(Pengumuman::create($data), 201);
    }

    public function pengumumanUpdate(Request $request, int $id): JsonResponse
    {
        if (!$this->checkAdmin($request)) return $this->unauthorized();
        $item = Pengumuman::findOrFail($id);
        $data = $request->validate([
            'tanggal' => 'required|date',
            'judul'   => 'required|string|max:255',
            'isi'     => 'required|string',
            'penting' => 'boolean',
        ]);
        $item->update($data);
        return response()->json($item);
    }

    public function pengumumanDestroy(Request $request, int $id): JsonResponse
    {
        if (!$this->checkAdmin($request)) return $this->unauthorized();
        Pengumuman::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    // ============================================================
    // GEMBALA
    // ============================================================

    public function gembalaIndex(): JsonResponse
    {
        return response()->json(Gembala::all());
    }

    public function gembalaStore(Request $request): JsonResponse
    {
        if (!$this->checkAdmin($request)) return $this->unauthorized();
        $data = $request->validate([
            'nama'    => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'bio'     => 'nullable|string',
            'foto'    => 'nullable|string', // base64
        ]);
        return response()->json(Gembala::create($data), 201);
    }

    public function gembalaUpdate(Request $request, int $id): JsonResponse
    {
        if (!$this->checkAdmin($request)) return $this->unauthorized();
        $item = Gembala::findOrFail($id);
        $data = $request->validate([
            'nama'    => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'bio'     => 'nullable|string',
            'foto'    => 'nullable|string',
        ]);
        $item->update($data);
        return response()->json($item);
    }

    public function gembalaDestroy(Request $request, int $id): JsonResponse
    {
        if (!$this->checkAdmin($request)) return $this->unauthorized();
        Gembala::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    // ============================================================
    // KONTAK PASTORAL
    // ============================================================

    public function kontakIndex(): JsonResponse
    {
        return response()->json(KontakPastoral::all());
    }

    public function kontakStore(Request $request): JsonResponse
    {
        if (!$this->checkAdmin($request)) return $this->unauthorized();
        $data = $request->validate([
            'nama'    => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'hp'      => 'required|string|max:30',
        ]);
        return response()->json(KontakPastoral::create($data), 201);
    }

    public function kontakUpdate(Request $request, int $id): JsonResponse
    {
        if (!$this->checkAdmin($request)) return $this->unauthorized();
        $item = KontakPastoral::findOrFail($id);
        $data = $request->validate([
            'nama'    => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'hp'      => 'required|string|max:30',
        ]);
        $item->update($data);
        return response()->json($item);
    }

    public function kontakDestroy(Request $request, int $id): JsonResponse
    {
        if (!$this->checkAdmin($request)) return $this->unauthorized();
        KontakPastoral::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    // ============================================================
    // JEMAAT
    // ============================================================

    public function jemaatIndex(): JsonResponse
    {
        return response()->json(Jemaat::orderBy('nama')->get());
    }

    public function jemaatStore(Request $request): JsonResponse
    {
        if (!$this->checkAdmin($request)) return $this->unauthorized();
        $data = $request->validate([
            'nama'     => 'required|string|max:255',
            'kategori' => 'required|in:Dewasa,Pemuda,Remaja,Anak',
            'hp'       => 'nullable|string|max:30',
            'ttl'      => 'nullable|date',
            'kota'     => 'nullable|string|max:100',
            'baptis'   => 'required|in:Sudah Baptis,Belum Baptis',
            'alamat'   => 'nullable|string|max:500',
        ]);
        return response()->json(Jemaat::create($data), 201);
    }

    public function jemaatUpdate(Request $request, int $id): JsonResponse
    {
        if (!$this->checkAdmin($request)) return $this->unauthorized();
        $item = Jemaat::findOrFail($id);
        $data = $request->validate([
            'nama'     => 'required|string|max:255',
            'kategori' => 'required|in:Dewasa,Pemuda,Remaja,Anak',
            'hp'       => 'nullable|string|max:30',
            'ttl'      => 'nullable|date',
            'kota'     => 'nullable|string|max:100',
            'baptis'   => 'required|in:Sudah Baptis,Belum Baptis',
            'alamat'   => 'nullable|string|max:500',
        ]);
        $item->update($data);
        return response()->json($item);
    }

    public function jemaatDestroy(Request $request, int $id): JsonResponse
    {
        if (!$this->checkAdmin($request)) return $this->unauthorized();
        Jemaat::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    // ============================================================
    // VISI MISI
    // ============================================================

    public function visiMisiShow(): JsonResponse
    {
        $vm = VisiMisi::first();
        return response()->json($vm ?? ['visi' => '', 'misi' => '']);
    }

    public function visiMisiUpdate(Request $request): JsonResponse
    {
        if (!$this->checkAdmin($request)) return $this->unauthorized();
        $data = $request->validate([
            'visi' => 'required|string',
            'misi' => 'required|string',
        ]);
        $vm = VisiMisi::firstOrNew([]);
        $vm->fill($data)->save();
        return response()->json($vm);
    }
}