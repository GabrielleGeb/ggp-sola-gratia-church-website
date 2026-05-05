@extends('layouts.app')

@section('content')
<div id="page-sermon" class="page">
  <div class="page-hero small-hero">
    <div class="page-hero-bg"></div>
    <h1>Sermon &amp; Khotbah</h1>
    <p>Rekam Jejak Firman Tuhan</p>
  </div>
  <div class="container page-content">
    <div id="sermon-list" class="sermon-grid"></div>
  </div>

  <div id="sermon-detail" class="detail-panel" style="display:none">
    <div class="container">
      <button class="back-btn" onclick="closeDetail('sermon')">← Kembali</button>
      <article class="article-full" id="sermon-detail-content"></article>
    </div>
  </div>
</div>
@endsection