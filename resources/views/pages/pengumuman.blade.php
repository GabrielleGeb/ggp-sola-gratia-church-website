@extends('layouts.app')

@section('content')
<div id="page-pengumuman" class="page">
  <div class="page-hero small-hero">
    <div class="page-hero-bg"></div>
    <h1>Pengumuman</h1>
    <p>Informasi dan kegiatan gereja</p>
  </div>
  <div class="container page-content">
    <div id="pengumuman-list" class="pengumuman-grid"></div>
  </div>

  {{-- DETAIL PANEL --}}
  <div id="pengumuman-detail" class="detail-panel" style="display:none">
    <div class="container">
      <button class="back-btn" onclick="closeDetail('pengumuman')">← Kembali</button>
      <article class="article-full" id="pengumuman-detail-content"></article>
    </div>
  </div>
</div>
@endsection