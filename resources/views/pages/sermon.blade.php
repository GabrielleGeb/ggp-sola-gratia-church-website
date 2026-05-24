@extends('layouts.app')

@section('content')
<div id="page-sermon" class="page">
  <div class="page-hero small-hero">
    <div class="page-hero-bg"></div>
    <h1>Khotbah</h1>
    <p>Rekam Jejak Firman Tuhan</p>
  </div>

  <div class="container page-content">

    {{-- Filter Bulan --}}
    <div style="margin-bottom: 2rem;">
      <form method="GET" action="{{ route('sermon') }}">
        <select name="bulan" onchange="this.form.submit()"
          style="padding: 0.65rem 1rem; border: 1px solid #e5e5e5; border-radius: var(--radius); font-family: 'Outfit', sans-serif; font-size: 0.875rem; background: white; color: var(--text); cursor: pointer;">
          <option value="">Semua Bulan</option>
          @foreach($bulanList as $b)
            <option value="{{ $b }}" {{ $bulan === $b ? 'selected' : '' }}>
              {{ \Carbon\Carbon::parse($b)->translatedFormat('F Y') }}
            </option>
          @endforeach
        </select>
      </form>
    </div>

    {{-- Grid Khotbah --}}
    <div class="sermon-grid">
      @forelse($videos as $item)
        @php
          $videoId = $item['snippet']['resourceId']['videoId'] ?? $item['id']['videoId'] ?? null;
          $thumb   = $item['snippet']['thumbnails']['medium']['url'] ?? '';
          $judul = html_entity_decode($item['snippet']['title']);
          $tanggal = \Carbon\Carbon::parse($item['snippet']['publishedAt'])->translatedFormat('d F Y');
        @endphp
        <a href="https://www.youtube.com/watch?v={{ $videoId }}" target="_blank" rel="noopener" class="sermon-card">
          @if($thumb)
            <img src="{{ $thumb }}" alt="{{ $judul }}" style="width:100%; border-radius:4px; margin-bottom:1rem; object-fit:cover; aspect-ratio:16/9;">
          @endif
          <div class="sermon-meta">{{ $tanggal }}</div>
          <h3>{{ $judul }}</h3>
          <div style="margin-top:1rem; font-size:0.75rem; font-weight:700; color:var(--gold); letter-spacing:0.08em; text-transform:uppercase; font-family:'Outfit',sans-serif;">
            Tonton di YouTube →
          </div>
        </a>
      @empty
        <div style="grid-column:1/-1; text-align:center; color:var(--text-light); padding:60px 0;">
          Belum ada video.
        </div>
      @endforelse
    </div>

    {{-- Pagination --}}
    @if($lastPage > 1)
      <div style="display:flex; justify-content:center; gap:0.5rem; margin-top:3rem;">
        @for($i = 1; $i <= $lastPage; $i++)
          <a href="{{ route('sermon', array_filter(['page' => $i, 'bulan' => $bulan])) }}"
            style="padding: 0.5rem 1rem; border: 1px solid {{ $i === $page ? 'var(--dark)' : '#e5e5e5' }}; border-radius: var(--radius); font-size: 0.8rem; font-family: 'Outfit', sans-serif; font-weight: 700; background: {{ $i === $page ? 'var(--dark)' : 'white' }}; color: {{ $i === $page ? 'white' : 'var(--text-light)' }};">
            {{ $i }}
          </a>
        @endfor
      </div>
    @endif

  </div>
</div>
@endsection