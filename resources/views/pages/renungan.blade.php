@extends('layouts.app')

@section('content')
<div id="page-renungan" class="page">
  <div class="page-hero small-hero">
    <div class="page-hero-bg"></div>
    <h1>Renungan Harian</h1>
    <p>Firman Tuhan setiap hari</p>
  </div>
  <div class="container page-content">
    <div id="renungan-list" class="renungan-grid"></div>
  </div>

  <div id="renungan-detail" class="detail-panel" style="display:none">
    <div class="container">
      <button class="back-btn" onclick="closeDetail('renungan')">← Kembali</button>
      <article class="article-full" id="renungan-detail-content"></article>
    </div>
  </div>
</div>
@endsection