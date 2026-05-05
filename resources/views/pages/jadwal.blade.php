@extends('layouts.app')

@section('content')
<div class="container" style="padding-top: 50px; padding-bottom: 60px;">

    {{-- NOTIFIKASI SUKSES --}}
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 24px;">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- NOTIFIKASI ERROR VALIDASI --}}
    @if($errors->any())
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 24px;">
            <strong>Mohon periksa kembali:</strong>
            <ul style="margin: 8px 0 0 16px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ===== FORM TAMBAH JADWAL ===== --}}
    <div style="background: #ffffff; padding: 28px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); margin-bottom: 40px;">
        <h2 style="margin-top: 0; margin-bottom: 20px;">➕ Tambah Jadwal Ibadah</h2>

        <form action="{{ route('jadwal.storeWeb') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">

                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:500;">Nama Ibadah <span style="color:red">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                        style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box;">
                </div>

                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:500;">Hari <span style="color:red">*</span></label>
                    <select name="hari" required style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box;">
                        @foreach(['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari)
                            <option value="{{ $hari }}" {{ old('hari') == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:500;">Jam <span style="color:red">*</span></label>
                    {{-- type="time" jauh lebih user-friendly daripada type="text" --}}
                    <input type="time" name="jam" value="{{ old('jam') }}" required
                        style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box;">
                </div>

                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:500;">Tempat</label>
                    <input type="text" name="tempat" value="{{ old('tempat') }}"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box;">
                </div>

                <div style="grid-column: span 2;">
                    <label style="display:block; margin-bottom:6px; font-weight:500;">Keterangan</label>
                    <textarea name="keterangan" rows="3"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; resize: vertical;">{{ old('keterangan') }}</textarea>
                </div>

            </div>

            <button type="submit"
                style="margin-top: 20px; background: #007bff; color: white; border: none; padding: 12px 28px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 15px;">
                Simpan Jadwal
            </button>
        </form>
    </div>

    {{-- ===== DAFTAR JADWAL ===== --}}
    <h2 style="margin-bottom: 16px;">📅 Jadwal Ibadah</h2>

    @forelse($jadwals as $item)
        <div style="border-left: 5px solid #007bff; background: #f9f9f9; padding: 18px 20px; margin-bottom: 14px; border-radius: 0 10px 10px 0; display: flex; justify-content: space-between; align-items: flex-start;">

            {{-- Info jadwal --}}
            <div>
                <h3 style="margin: 0 0 6px;">{{ $item->nama }}</h3>
                <p style="margin: 0 0 4px;"><strong>{{ $item->hari }}</strong> · {{ \Carbon\Carbon::createFromFormat('H:i', $item->jam)->format('H:i') }} WIB</p>
                @if($item->tempat)
                    <p style="margin: 0 0 4px; color: #555;">📍 {{ $item->tempat }}</p>
                @endif
                @if($item->keterangan)
                    <p style="margin: 0; color: #777; font-size: 0.9em;">{{ $item->keterangan }}</p>
                @endif
            </div>

            {{-- Tombol aksi --}}
            <div style="display: flex; gap: 8px; flex-shrink: 0; margin-left: 16px;">

                {{-- Tombol Edit --}}
                <button onclick="bukaModalEdit({{ $item->id }}, {!! json_encode($item->nama ?? '') !!}, {!! json_encode($item->hari ?? '') !!}, {!! json_encode($item->jam ?? '') !!}, {!! json_encode($item->tempat ?? '') !!}, {!! json_encode($item->keterangan ?? '') !!})"
                    style="background: #ffc107; color: #333; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 500;">
                    ✏️ Edit
                </button>

                {{-- Tombol Hapus --}}
                <form action="{{ route('jadwal.destroyWeb', $item->id) }}" method="POST"
                    onsubmit="return confirm({!! json_encode('Yakin mau hapus jadwal ' . ($item->nama ?? '') . '?') !!})">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 500;">
                        🗑️ Hapus
                    </button>
                </form>

            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 40px; color: #888; background: #f9f9f9; border-radius: 10px;">
            Belum ada jadwal. Tambahkan jadwal pertama di atas!
        </div>
    @endforelse

</div>

{{-- ===== MODAL EDIT ===== --}}
<div id="modal-edit" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:32px; border-radius:12px; width:100%; max-width:520px; margin:20px; box-shadow:0 8px 32px rgba(0,0,0,0.2);">
        <h3 style="margin-top:0; margin-bottom:20px;">✏️ Edit Jadwal</h3>

        <form id="form-edit" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">

                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:500;">Nama Ibadah <span style="color:red">*</span></label>
                    <input type="text" name="nama" id="edit-nama" required
                        style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:6px; box-sizing:border-box;">
                </div>

                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:500;">Hari <span style="color:red">*</span></label>
                    <select name="hari" id="edit-hari" required
                        style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:6px; box-sizing:border-box;">
                        @foreach(['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari)
                            <option value="{{ $hari }}">{{ $hari }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:500;">Jam <span style="color:red">*</span></label>
                    <input type="time" name="jam" id="edit-jam" required
                        style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:6px; box-sizing:border-box;">
                </div>

                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:500;">Tempat</label>
                    <input type="text" name="tempat" id="edit-tempat"
                        style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:6px; box-sizing:border-box;">
                </div>

                <div style="grid-column: span 2;">
                    <label style="display:block; margin-bottom:6px; font-weight:500;">Keterangan</label>
                    <textarea name="keterangan" id="edit-keterangan" rows="3"
                        style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:6px; box-sizing:border-box; resize:vertical;"></textarea>
                </div>
            </div>

            <div style="display:flex; gap:10px; margin-top:20px;">
                <button type="submit"
                    style="background:#007bff; color:white; border:none; padding:11px 24px; border-radius:6px; cursor:pointer; font-weight:600;">
                    Simpan Perubahan
                </button>
                <button type="button" onclick="tutupModal()"
                    style="background:#6c757d; color:white; border:none; padding:11px 24px; border-radius:6px; cursor:pointer;">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function bukaModalEdit(id, nama, hari, jam, tempat, keterangan) {
    // Set action form ke route update
    document.getElementById('form-edit').action = '/jadwal/' + id;

    // Isi field dengan data yang ada
    document.getElementById('edit-nama').value      = nama;
    document.getElementById('edit-hari').value      = hari;
    document.getElementById('edit-jam').value       = jam;
    document.getElementById('edit-tempat').value    = tempat;
    document.getElementById('edit-keterangan').value = keterangan;

    // Tampilkan modal
    document.getElementById('modal-edit').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function tutupModal() {
    document.getElementById('modal-edit').style.display = 'none';
    document.body.style.overflow = '';
}

// Klik di luar modal = tutup
document.getElementById('modal-edit').addEventListener('click', function(e) {
    if (e.target === this) tutupModal();
});
</script>
@endsection