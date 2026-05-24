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
      <!-- <p class="hero-verse">"Karena begitu besar kasih Allah akan dunia ini, sehingga Ia telah mengaruniakan Anak-Nya yang tunggal" — Yohanes 3:16</p> -->
      <!-- <div class="hero-buttons">
        <a href="{{ route('renungan') }}" class="btn-primary">Baca Renungan</a>
        <a href="{{ route('jadwal') }}" class="btn-outline">Jadwal Ibadah</a>
      </div> -->
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
        <!-- <a href="{{ route('jadwal') }}" class="highlight-card">
          <div class="h-icon"><i data-lucide="clock"></i></div>
          <h3>Jadwal Ibadah</h3>
          <p>Waktu dan tempat pelaksanaan ibadah mingguan dan tengah minggu</p>
          <span class="h-link">Lihat →</span>
        </a> -->
        <a href="{{ route('sermon') }}" class="highlight-card">
          <div class="h-icon"><i data-lucide="mic"></i></div>
          <h3>Khotbah</h3>
          <p>Kumpulan khotbah dan pesan firman Tuhan dari para hamba-Nya</p>
          <span class="h-link">Dengar →</span>
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
  <div class="container">
    <div class="visi-split">
      <div class="visi-split-col">
        <span class="visi-split-label">Visi</span>
        @foreach($visi as $v)
          <h2 class="visi-split-text">{{ $v->konten }}</h2>
        @endforeach
      </div>
      <div class="visi-split-divider"></div>
      <div class="visi-split-col">
        <span class="visi-split-label">Misi</span>
        @foreach($misi as $m)
          <h3 class="visi-split-text-sm">{{ $m->konten }}</h3>
        @endforeach
      </div>
    </div>
    <div style="text-align:center; margin-top: 3rem;">
      <a href="{{ route('visi-misi') }}" class="btn-outline">Selengkapnya</a>
    </div>
  </div>
</section>

  {{-- RENUNGAN PREVIEW --}}
  <section class="home-section renungan-preview">
    <div class="container">
      <span class="section-label">Renungan Terbaru</span>
      <h2>Firman Hari Ini</h2>
      <div id="home-renungan-list" class="card-grid"></div>
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