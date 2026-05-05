@extends('layouts.app')

@section('content')
<div id="page-home" class="page active">

  {{-- HERO --}}
  <section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-content">
      <span class="hero-kecil">Selamat Datang di</span>
      <h1 class="hero-title">
        Gereja Gerakan Pentakosta
        <span class="hero-sub">Sola Gratia</span>
      </h1>
      <p class="hero-verse">"Karena begitu besar kasih Allah akan dunia ini, sehingga Ia telah mengaruniakan Anak-Nya yang tunggal" — Yohanes 3:16</p>
      <div class="hero-buttons">
        <a href="{{ route('renungan') }}" class="btn-primary">Baca Renungan</a>
        <a href="{{ route('jadwal') }}" class="btn-outline">Jadwal Ibadah</a>
      </div>
    </div>
    <div class="hero-scroll">scroll ↓</div>
  </section>

  {{-- HIGHLIGHT CARDS --}}
  <section class="home-section highlight-section">
    <div class="container">
      <div class="highlight-grid">
        <a href="{{ route('renungan') }}" class="highlight-card">
          <div class="h-icon"><i data-lucide="book-open"></i></div>
          <h3>Renungan Harian</h3>
          <p>Firman Tuhan setiap hari untuk menguatkan iman Anda</p>
          <span class="h-link">Baca →</span>
        </a>
        <a href="{{ route('jadwal') }}" class="highlight-card">
          <div class="h-icon"><i data-lucide="clock"></i></div>
          <h3>Jadwal Ibadah</h3>
          <p>Waktu dan tempat pelaksanaan ibadah mingguan dan tengah minggu</p>
          <span class="h-link">Lihat →</span>
        </a>
        <a href="{{ route('pengumuman') }}" class="highlight-card">
          <div class="h-icon"><i data-lucide="megaphone"></i></div>
          <h3>Pengumuman</h3>
          <p>Informasi terkini tentang kegiatan dan program gereja</p>
          <span class="h-link">Info →</span>
        </a>
        <a href="{{ route('pastoral') }}" class="highlight-card">
          <div class="h-icon"><i data-lucide="mail"></i></div>
          <h3>Info Pastoral</h3>
          <p>Baptisan, pernikahan, penyerahan anak & kontak gembala</p>
          <span class="h-link">Info →</span>
        </a>
      </div>
    </div>
  </section>

  {{-- JADWAL PREVIEW --}}
  <section class="home-section jadwal-preview-section">
    <div class="container">
      <span class="section-label">Jadwal Ibadah</span>
      <h2>Bergabunglah Bersama Kami</h2>
      <div id="home-jadwal-list" class="jadwal-preview-grid"></div>
      <div style="text-align:center;margin-top:2.5rem">
        <a href="{{ route('jadwal') }}" class="btn-primary">Lihat Semua Jadwal</a>
      </div>
    </div>
  </section>

  {{-- VISI PREVIEW --}}
  <section class="home-section visi-preview">
    <div class="container two-col">
      <div class="col-text">
        <span class="section-label">Visi Kami</span>
        <h2>Menjadi Gereja yang Mengasihi Tuhan &amp; Sesama</h2>
        <p>GGP Sola Gratia hadir untuk menjadi rumah rohani bagi setiap jiwa, tempat di mana kasih Tuhan dialami, iman bertumbuh, dan setiap orang diperlengkapi untuk menjadi terang di dunia.</p>
        <a href="{{ route('visi-misi') }}" class="btn-primary">Selengkapnya</a>
      </div>
      <div class="col-deco">
        <div class="deco-cross">✝</div>
        <div class="deco-verse">"Kasihilah Tuhan, Allahmu, dengan segenap hatimu"<br><em>— Matius 22:37</em></div>
      </div>
    </div>
  </section>

  {{-- RENUNGAN PREVIEW --}}
  <section class="home-section renungan-preview">
    <div class="container">
      <span class="section-label">Renungan Terbaru</span>
      <h2>Firman Hari Ini</h2>
      <div id="home-renungan-list" class="card-grid"></div>
      <div style="text-align:center;margin-top:2rem">
        <a href="{{ route('renungan') }}" class="btn-outline">Lihat Semua Renungan</a>
      </div>
    </div>
  </section>

  {{-- PENGUMUMAN PREVIEW --}}
  <section class="home-section pengumuman-preview-section">
    <div class="container">
      <span class="section-label">Pengumuman</span>
      <h2>Informasi Terkini</h2>
      <div id="home-pengumuman-list" class="pengumuman-preview-list"></div>
      <div style="text-align:center;margin-top:2rem">
        <a href="{{ route('pengumuman') }}" class="btn-primary">Semua Pengumuman</a>
      </div>
    </div>
  </section>

  @include('components.footer')

</div>
@endsection