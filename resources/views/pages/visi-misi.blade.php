@extends('layouts.app')

@section('content')
<div id="page-visi-misi" class="page">
  <div class="page-hero small-hero">
    <div class="page-hero-bg"></div>
    <h1>Visi &amp; Misi</h1>
    <p>Fondasi pelayanan kami</p>
  </div>
  <div class="container page-content">

    <div class="vm-block">
      <div class="vm-icon"><i data-lucide="target"></i></div>
      <h2>Visi</h2>
      <div id="vm-visi" class="vm-text">
        Menjadi Gereja yang Bertumbuh dalam Kasih, Iman, dan Pelayanan — menggenapi Amanat Agung Tuhan Yesus Kristus untuk menjangkau, mendidik, dan mengutus setiap jemaat menjadi murid yang berbuah.
      </div>
    </div>

    <div class="vm-block">
      <div class="vm-icon"><i data-lucide="list-checks"></i></div>
      <h2>Misi</h2>
      <div id="vm-misi" class="vm-text">
        <ol>
          <li>Memuliakan Tuhan melalui ibadah yang hidup dan penuh Roh Kudus</li>
          <li>Membangun jemaat yang kuat dalam Firman dan doa</li>
          <li>Membina persekutuan yang hangat dan saling mendukung</li>
          <li>Melayani masyarakat sekitar dengan kasih Kristus</li>
          <li>Mengutus hamba-hamba Tuhan untuk pekerjaan misi</li>
        </ol>
      </div>
    </div>

    <div class="vm-block">
      <div class="vm-icon"><i data-lucide="scroll-text"></i></div>
      <h2>Nilai-Nilai Gereja</h2>
      <div class="nilai-grid">
        <div class="nilai-card"><strong>Kasih</strong><p>Mengasihi Tuhan dan sesama adalah inti dari setiap pelayanan kami</p></div>
        <div class="nilai-card"><strong>Iman</strong><p>Percaya sepenuhnya pada janji dan kuasa Firman Allah</p></div>
        <div class="nilai-card"><strong>Penginjilan</strong><p>Membagikan kabar baik kepada setiap orang yang belum mengenal Kristus</p></div>
        <div class="nilai-card"><strong>Komunitas</strong><p>Bersama-sama bertumbuh sebagai satu tubuh Kristus yang utuh</p></div>
      </div>
    </div>

  </div>
</div>
@endsection