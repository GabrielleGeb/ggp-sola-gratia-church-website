@extends('layouts.app')

@section('content')
<div id="page-jemaat" class="page">
  <div class="page-hero small-hero">
    <div class="page-hero-bg"></div>
    <h1>Data Jemaat</h1>
    <p>Direktori jemaat GBI Berkat Kasih</p>
  </div>

  {{-- GUARD: tampil jika BUKAN admin --}}
  <div id="jemaat-guard" class="container page-content">
    <div class="access-denied-box">
      <div class="ad-icon">🔒</div>
      <h2>Akses Terbatas</h2>
      <p>Halaman ini hanya dapat diakses oleh Admin gereja yang sudah login.</p>
      <p class="ad-hint">Silakan hubungi pengurus gereja jika Anda memerlukan akses.</p>
    </div>
  </div>

  {{-- CONTENT: tampil jika sudah login admin --}}
  <div id="jemaat-content" class="container page-content" style="display:none">
    <div class="jemaat-controls">
      <input type="text" id="jemaat-search" placeholder="Cari nama jemaat..." oninput="filterJemaat()">
      <select id="jemaat-filter" onchange="filterJemaat()">
        <option value="">Semua Kategori</option>
        <option value="Dewasa">Dewasa</option>
        <option value="Pemuda">Pemuda</option>
        <option value="Remaja">Remaja</option>
        <option value="Anak">Anak</option>
      </select>
    </div>
    <div class="table-wrapper">
      <table class="jemaat-table" id="jemaat-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Lengkap</th>
            <th>Kategori</th>
            <th>Tempat, Tgl Lahir</th>
            <th>No. HP</th>
            <th>Alamat</th>
            <th>Status Baptis</th>
          </tr>
        </thead>
        <tbody id="jemaat-tbody"></tbody>
      </table>
    </div>
    <p class="data-note">⚠️ Data jemaat bersifat internal. Hanya dapat diakses oleh pengurus gereja yang berwenang.</p>
  </div>
</div>
@endsection