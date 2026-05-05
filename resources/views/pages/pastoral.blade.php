@extends('layouts.app')

@section('content')
<div id="page-pastoral" class="page">
  <div class="page-hero small-hero">
    <div class="page-hero-bg"></div>
    <h1>Informasi Pastoral</h1>
    <p>Layanan gereja untuk jemaat</p>
  </div>

  {{-- LAYANAN SECTION --}}
  <div class="container page-content">
    <span class="section-label">Layanan Gereja</span>
    <h2 style="margin-bottom:2.5rem;color:var(--dark)">Kami Ada untuk Melayani Anda</h2>
    <div class="pastoral-grid">

      <div class="pastoral-card">
        <div class="p-icon"><i data-lucide="droplets"></i></div>
        <h3>Baptisan Air</h3>
        <p>Baptisan air dilaksanakan setiap semester. Pendaftaran dibuka 1 bulan sebelumnya melalui sekretariat gereja.</p>
        <ul>
          <li>Usia minimal: 13 tahun</li>
          <li>Wajib mengikuti kelas persiapan baptisan (4 pertemuan)</li>
          <li>Kontak: <strong>Ibu Maria — 0812-0001-0001</strong></li>
        </ul>
      </div>

      <div class="pastoral-card">
        <div class="p-icon"><i data-lucide="heart"></i></div>
        <h3>Pernikahan Kudus</h3>
        <p>Pelayanan pernikahan Kristen dilayani oleh gembala gereja. Pendaftaran minimal 3 bulan sebelum hari H.</p>
        <ul>
          <li>Konseling pra-nikah wajib (minimum 3 sesi)</li>
          <li>Kedua mempelai adalah jemaat terdaftar</li>
          <li>Kontak: <strong>Pnt. Budi — 0812-0002-0002</strong></li>
        </ul>
      </div>

      <div class="pastoral-card">
        <div class="p-icon"><i data-lucide="baby"></i></div>
        <h3>Penyerahan Anak</h3>
        <p>Penyerahan anak kepada Tuhan dilaksanakan dalam ibadah raya pada hari Minggu.</p>
        <ul>
          <li>Anak berusia 0–2 tahun</li>
          <li>Orang tua adalah jemaat terdaftar</li>
          <li>Kontak: <strong>Sdri. Rut — 0812-0003-0003</strong></li>
        </ul>
      </div>

    </div>
  </div>

  {{-- KONTAK SECTION — full width, dark background --}}
  <div class="kontak-section">
    <div class="container">
      <div class="kontak-section-header">
        <div>
          <span class="section-label" style="color:var(--gold)">Hubungi Kami</span>
          <h2 style="color:var(--white)">Kontak Pastoral</h2>
          <p style="color:rgba(255,255,255,0.5);font-size:0.9rem;margin-top:0.75rem;font-family:'Outfit',sans-serif">
            Tim pastoral kami siap membantu Anda. Hubungi langsung melalui WhatsApp.
          </p>
        </div>
      </div>
      <div id="kontak-wa-list" class="kontak-grid"></div>
    </div>
  </div>
</div>
@endsection