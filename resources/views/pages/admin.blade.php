@extends('layouts.app')

@section('content')
@php $adminTab = $adminTab ?? 'renungan'; @endphp
<div id="page-admin" class="page" data-admin-tab="{{ $adminTab }}">
  <div class="page-hero small-hero dark-hero">
    <div class="page-hero-bg"></div>
    <h1>Panel Admin</h1>
    <p>Kelola konten website gereja</p>
  </div>
  <div class="container page-content">

    {{-- LOGIN FORM --}}
    <div id="admin-login" class="admin-login-box">
      <div class="admin-login-logo">✝</div>
      <h2>Panel Admin</h2>
      <p class="admin-login-sub">Masuk dengan akun admin gereja Anda</p>
      <input type="text" id="admin-user" placeholder="Username" autocomplete="off">
      <input type="password" id="admin-pass" placeholder="Password" data-action="admin-login-enter">
      <div id="admin-login-error" class="login-error" style="display:none">Username atau password salah.</div>
      <button class="btn-primary" data-action="admin-login">Masuk</button>
      <p class="admin-login-back"><a href="{{ route('home') }}">← Kembali ke Beranda</a></p>
    </div>

    {{-- ADMIN PANEL --}}
    <div id="admin-panel" style="display:none">
      <div class="admin-tabs">
        <a href="{{ route('admin', ['tab' => 'renungan']) }}" class="tab-btn {{ $adminTab === 'renungan' ? 'active' : '' }}" data-action="admin-tab" data-target="renungan">
          <i data-lucide="book-open"></i> Renungan
        </a>
        <a href="{{ route('admin', ['tab' => 'jadwal']) }}" class="tab-btn {{ $adminTab === 'jadwal-admin' ? 'active' : '' }}" data-action="admin-tab" data-target="jadwal-admin">
          <i data-lucide="clock"></i> Jadwal
        </a>
        <a href="{{ route('admin', ['tab' => 'pengumuman']) }}" class="tab-btn {{ $adminTab === 'pengumuman-admin' ? 'active' : '' }}" data-action="admin-tab" data-target="pengumuman-admin">
          <i data-lucide="megaphone"></i> Pengumuman
        </a>
        <a href="{{ route('admin', ['tab' => 'gembala']) }}" class="tab-btn {{ $adminTab === 'gembala' ? 'active' : '' }}" data-action="admin-tab" data-target="gembala">
          <i data-lucide="user"></i> Gembala
        </a>
        <a href="{{ route('admin', ['tab' => 'kontak']) }}" class="tab-btn {{ $adminTab === 'kontak-admin' ? 'active' : '' }}" data-action="admin-tab" data-target="kontak-admin">
          <i data-lucide="phone"></i> Kontak
        </a>
        <a href="{{ route('admin', ['tab' => 'jemaat']) }}" class="tab-btn {{ $adminTab === 'jemaat-admin' ? 'active' : '' }}" data-action="admin-tab" data-target="jemaat-admin">
          <i data-lucide="users"></i> Jemaat
        </a>
        <a href="{{ route('admin', ['tab' => 'visi']) }}" class="tab-btn {{ $adminTab === 'visi-admin' ? 'active' : '' }}" data-action="admin-tab" data-target="visi-admin">
          <i data-lucide="target"></i> Visi Misi
        </a>
        <a href="{{ route('admin', ['tab' => 'setelan']) }}" class="tab-btn {{ $adminTab === 'setelan' ? 'active' : '' }}" data-action="admin-tab" data-target="setelan">
          <i data-lucide="settings"></i> Setelan
        </a>
        <button class="tab-btn logout" data-action="admin-logout">
          <i data-lucide="log-out"></i> Keluar
        </button>
      </div>

      {{-- TAB: RENUNGAN --}}
      <div id="admin-renungan" class="admin-tab-content {{ $adminTab === 'renungan' ? 'active' : '' }}">
        <h3>Tambah Renungan Harian</h3>
        <div class="form-group"><label>Tanggal</label><input type="date" id="r-tanggal"></div>
        <div class="form-group"><label>Judul</label><input type="text" id="r-judul" placeholder="Judul renungan..."></div>
        <div class="form-group"><label>Ayat Kunci</label><input type="text" id="r-ayat" placeholder="mis: Yohanes 3:16"></div>
        <div class="form-group"><label>Isi Renungan</label><textarea id="r-isi" rows="8" placeholder="Tulis isi renungan..."></textarea></div>
        <div class="form-group"><label>Penulis</label><input type="text" id="r-penulis" placeholder="Nama penulis..."></div>
        <button class="btn-primary" data-action="add-renungan">Simpan Renungan</button>
        <h3 style="margin-top:2rem">Daftar Renungan</h3>
        <div id="admin-renungan-list" class="admin-list"></div>
      </div>

      {{-- TAB: JADWAL --}}
      <div id="admin-jadwal-admin" class="admin-tab-content">
        <h3>Tambah Jadwal Ibadah</h3>
        <div class="form-row">
          <div class="form-group"><label>Nama Ibadah</label><input type="text" id="j2-nama" placeholder="mis: Ibadah Raya Pagi I"></div>
          <div class="form-group"><label>Hari</label>
            <select id="j2-hari">
              <option>Minggu</option><option>Senin</option><option>Selasa</option>
              <option>Rabu</option><option>Kamis</option><option>Jumat</option><option>Sabtu</option>
            </select>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group"><label>Jam</label><input type="time" id="j2-jam"></div>
          <div class="form-group"><label>Tempat / Ruangan</label><input type="text" id="j2-tempat" placeholder="mis: Gedung Utama"></div>
        </div>
        <div class="form-group"><label>Keterangan (opsional)</label><textarea id="j2-keterangan" rows="2" placeholder="Keterangan tambahan..."></textarea></div>
        <button class="btn-primary" data-action="add-jadwal">Simpan Jadwal</button>
        <h3 style="margin-top:2rem">Daftar Jadwal Ibadah</h3>
        <div id="admin-jadwal-list" class="admin-list"></div>
      </div>

      {{-- TAB: PENGUMUMAN --}}
      <div id="admin-pengumuman-admin" class="admin-tab-content">
        <h3>Tambah Pengumuman</h3>
        <div class="form-row">
          <div class="form-group"><label>Tanggal</label><input type="date" id="p2-tanggal"></div>
          <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:4px">
            <label style="display:flex;align-items:center;gap:0.5rem;text-transform:none;letter-spacing:0;font-size:0.875rem;cursor:pointer">
              <input type="checkbox" id="p2-penting"> Tandai sebagai Penting
            </label>
          </div>
        </div>
        <div class="form-group"><label>Judul Pengumuman</label><input type="text" id="p2-judul" placeholder="Judul pengumuman..."></div>
        <div class="form-group"><label>Isi Pengumuman</label><textarea id="p2-isi" rows="7" placeholder="Tulis isi pengumuman..."></textarea></div>
        <button class="btn-primary" data-action="add-pengumuman">Simpan Pengumuman</button>
        <h3 style="margin-top:2rem">Daftar Pengumuman</h3>
        <div id="admin-pengumuman-list" class="admin-list"></div>
      </div>

      {{-- TAB: GEMBALA --}}
      <div id="admin-gembala" class="admin-tab-content">
        <h3>Tambah Gembala &amp; PIC</h3>
        <div class="form-group"><label>Nama</label><input type="text" id="g-nama" placeholder="Nama hamba Tuhan..."></div>
        <div class="form-group"><label>Jabatan</label><input type="text" id="g-jabatan" placeholder="mis: Gembala Sidang, PIC Pemuda..."></div>
        <div class="form-group"><label>Biografi Singkat</label><textarea id="g-bio" rows="4" placeholder="Biografi singkat..."></textarea></div>
        <div class="form-group">
          <label>Foto (Upload dari Komputer)</label>
          <div class="photo-upload-area" id="g-upload-area" data-action="trigger-upload" data-target="g-foto-file">
            <div class="upload-placeholder" id="g-upload-placeholder">
              <i data-lucide="camera" class="upload-icon"></i>
              <span>Klik untuk pilih foto</span>
              <span class="upload-hint">JPG, PNG maks. 2MB</span>
            </div>
            <img id="g-foto-preview" class="upload-preview" style="display:none" alt="Preview">
          </div>
          <input type="file" id="g-foto-file" accept="image/*" style="display:none" data-action="handle-upload" data-prefix="g">
          <button type="button" class="clear-photo-btn" id="g-clear-foto" style="display:none" data-action="clear-foto" data-target="g">Hapus Foto</button>
          <input type="hidden" id="g-foto-data">
        </div>
        <button class="btn-primary" data-action="add-gembala">Simpan</button>
        <h3 style="margin-top:2rem">Daftar Gembala &amp; PIC</h3>
        <div id="admin-gembala-list" class="admin-list"></div>
      </div>

      {{-- TAB: KONTAK --}}
      <div id="admin-kontak-admin" class="admin-tab-content">
        <h3>Tambah Kontak Pastoral</h3>
        <div class="form-row">
          <div class="form-group"><label>Nama</label><input type="text" id="k-nama" placeholder="Nama..."></div>
          <div class="form-group"><label>Jabatan</label><input type="text" id="k-jabatan" placeholder="mis: Gembala Sidang"></div>
        </div>
        <div class="form-group">
          <label>No. WhatsApp (format internasional)</label>
          <input type="tel" id="k-hp" placeholder="mis: 6281234567890">
          <small style="color:var(--text-light);font-size:0.75rem;margin-top:0.25rem;display:block">Tanpa tanda + di depan</small>
        </div>
        <button class="btn-primary" data-action="add-kontak">Simpan Kontak</button>
        <h3 style="margin-top:2rem">Daftar Kontak Pastoral</h3>
        <div id="admin-kontak-list" class="admin-list"></div>
      </div>

      {{-- TAB: JEMAAT --}}
      <div id="admin-jemaat-admin" class="admin-tab-content">
        <div id="birthday-banner" style="display:none" class="birthday-banner"></div>
        <h3>Tambah Data Jemaat</h3>
        <div class="form-row">
          <div class="form-group"><label>Nama Lengkap</label><input type="text" id="j-nama" placeholder="Nama..."></div>
          <div class="form-group"><label>Kategori</label>
            <select id="j-kategori">
              <option>Dewasa</option><option>Pemuda</option><option>Remaja</option><option>Anak</option>
            </select>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group"><label>No. HP / WhatsApp</label><input type="text" id="j-hp" placeholder="0812-..."></div>
          <div class="form-group"><label>Tanggal Lahir</label><input type="date" id="j-ttl"></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label>Tempat Lahir</label><input type="text" id="j-kota" placeholder="mis: Surabaya"></div>
          <div class="form-group"><label>Status Baptis</label>
            <select id="j-baptis"><option>Sudah Baptis</option><option>Belum Baptis</option></select>
          </div>
        </div>
        <div class="form-group"><label>Alamat</label><input type="text" id="j-alamat" placeholder="Alamat lengkap..."></div>
        <button class="btn-primary" data-action="add-jemaat">Simpan</button>
        <h3 style="margin-top:2rem">Total Jemaat: <span id="total-jemaat">0</span></h3>
        <button class="btn-export" data-action="export-csv" style="margin-bottom:1rem">Ekspor CSV</button>
        <div id="admin-jemaat-list" class="admin-list"></div>
      </div>

      {{-- TAB: VISI MISI --}}
      <div id="admin-visi-admin" class="admin-tab-content">
        <h3>Visi &amp; Misi Gereja</h3>

        @if(session('success'))
          <div class="alert-success">{{ session('success') }}</div>
        @endif

        {{-- Form Tambah --}}
        <form method="POST" action="{{ route('visi-misi.store') }}" style="margin-bottom:2rem">
          @csrf
          <div class="form-group">
            <label>Tipe</label>
            <select name="tipe" required>
              <option value="visi">Visi</option>
              <option value="misi">Misi</option>
            </select>
          </div>
          <div class="form-group">
            <label>Konten</label>
            <textarea name="konten" rows="3" required placeholder="Tulis visi atau poin misi..."></textarea>
          </div>
          <button type="submit" class="btn-primary">+ Tambah</button>
        </form>

        {{-- Daftar Visi --}}
        <h4>Visi</h4>
        @php $visiList = \App\Models\VisiMisi::where('tipe','visi')->orderBy('urutan')->get(); @endphp
        @forelse($visiList as $item)
          <div class="admin-list-item">
            <span>{{ $item->konten }}</span>
            <div style="display:flex;gap:0.5rem">
              <form method="POST" action="{{ route('visi-misi.update', $item->id) }}">
                @csrf @method('PUT')
                <input type="hidden" name="konten" value="{{ $item->konten }}">
                {{-- tombol edit bisa dikembangkan dengan modal --}}
              </form>
              <form method="POST" action="{{ route('visi-misi.destroy', $item->id) }}" onsubmit="return confirm('Hapus item ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger">Hapus</button>
              </form>
            </div>
          </div>
        @empty
          <p>Belum ada data visi.</p>
        @endforelse

        {{-- Daftar Misi --}}
        <h4 style="margin-top:1.5rem">Misi</h4>
        @php $misiList = \App\Models\VisiMisi::where('tipe','misi')->orderBy('urutan')->get(); @endphp
        @forelse($misiList as $item)
          <div class="admin-list-item">
            <span>{{ $item->konten }}</span>
            <form method="POST" action="{{ route('visi-misi.destroy', $item->id) }}" onsubmit="return confirm('Hapus item ini?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-danger">Hapus</button>
            </form>
          </div>
        @empty
          <p>Belum ada data misi.</p>
        @endforelse
      </div>

      {{-- TAB: SETELAN --}}
      <div id="admin-setelan" class="admin-tab-content">
        <h3>Ganti Password Admin</h3>
        <p style="color:var(--text-light);font-size:0.875rem;margin-bottom:1.5rem;font-family:'Outfit',sans-serif">Gunakan fitur ini untuk mengubah password masuk panel admin.</p>
        <div class="form-group"><label>Password Lama</label><input type="password" id="ap-old" placeholder="Password saat ini"></div>
        <div class="form-group"><label>Password Baru</label><input type="password" id="ap-new" placeholder="Minimal 6 karakter"></div>
        <div class="form-group"><label>Konfirmasi Password Baru</label><input type="password" id="ap-confirm" placeholder="Ulangi password baru"></div>
        <button class="btn-primary" data-action="change-password">Ubah Password</button>
      </div>

    </div>{{-- end admin-panel --}}
  </div>
</div>
@endsection