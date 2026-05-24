@extends('layouts.app')

@section('content')
<div id="page-jadwal" class="page">
  <div class="page-hero small-hero">
    <div class="page-hero-bg"></div>
    <h1>Jadwal Ibadah</h1>
    <p>Bergabunglah bersama kami dalam ibadah</p>
  </div>

  <div class="container page-content">
    @php
      $urutan = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
      $grouped = $jadwals->groupBy('hari')->sortBy(fn($v, $k) => array_search($k, $urutan));
    @endphp

    @forelse($grouped as $hari => $items)
      <div class="jadwal-day-group">
        <div class="jadwal-day-title">{{ $hari }}</div>
        <div class="jadwal-full-grid-2">
          @foreach($items->sortBy('jam') as $item)
            <div class="jadwal-card">
              <div>
                <div class="jadwal-hari">{{ \Carbon\Carbon::createFromFormat('H:i', $item->jam)->format('H:i') }}</div>
              </div>
              <div class="jadwal-info">
                <h3>{{ $item->nama }}</h3>
                <div class="jadwal-meta">
                  @if($item->tempat)
                    <span>
                      <i data-lucide="map-pin" style="width:13px;height:13px;margin-right:4px;"></i>
                      {{ $item->tempat }}
                    </span>
                  @endif
                </div>
                @if($item->keterangan)
                  <p class="jadwal-ket">{{ $item->keterangan }}</p>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @empty
      <div style="text-align:center; color:var(--text-light); padding:60px 0;">
        Belum ada jadwal ibadah.
      </div>
    @endforelse
  </div>
</div>
@endsection