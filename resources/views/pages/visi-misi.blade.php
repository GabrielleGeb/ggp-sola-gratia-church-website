@extends('layouts.app')

@section('content')
<div id="page-visi-misi" class="page">
  <div class="page-hero small-hero">
    <div class="page-hero-bg"></div>
    <h1>Visi &amp; Misi</h1>
    <p>Fondasi pelayanan kami</p>
  </div>

  {{-- VISI --}}
  <section style="background: linear-gradient(135deg, #000000 0%, #1a0a00 50%, #2d0d0d 100%); padding: 6rem 0;">
    <div class="container">
      <span class="section-label" style="color: var(--gold);">Visi Kami</span>
      @forelse($visi as $item)
        <h2 style="color: var(--white); font-size: clamp(2rem, 4vw, 3.5rem); margin-top: 1rem; line-height: 1.1; max-width: 800px;">
          {{ $item->konten }}
        </h2>
      @empty
        <h2 style="color: var(--white);">-</h2>
      @endforelse
    </div>
  </section>

  {{-- MISI --}}
  <section style="background: var(--white); padding: 6rem 0;">
    <div class="container">
      <span class="section-label">Misi Kami</span>
      <h2 style="color: var(--dark); margin-bottom: 3rem; margin-top: 1rem;">Panggilan yang Kami Emban</h2>
      <div style="display: flex; flex-direction: column; gap: 0;">
        @forelse($misi as $index => $item)
          <div style="display: flex; gap: 2.5rem; align-items: flex-start; padding: 2.5rem 0; border-bottom: 1px solid #e5e5e5;">
            <div style="font-family: 'Montserrat', sans-serif; font-weight: 900; font-size: 3.5rem; color: var(--gold); opacity: 0.3; line-height: 1; flex-shrink: 0; width: 80px; text-align: right;">
              {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="padding-top: 0.5rem;">
              <p style="font-size: 1.25rem; color: var(--dark); font-family: 'Outfit', sans-serif; line-height: 1.8;">
                {{ $item->konten }}
              </p>
            </div>
          </div>
        @empty
          <p style="color: var(--text-light);">-</p>
        @endforelse
      </div>
    </div>
  </section>

  {{-- NILAI-NILAI --}}
  <section style="background: var(--dark); padding: 6rem 0;">
    <div class="container">
      <span class="section-label" style="color: var(--gold);">Nilai-Nilai Gereja</span>
      <h2 style="color: var(--white); margin-bottom: 3rem; margin-top: 1rem;">Yang Kami Pegang Teguh</h2>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem;">
        <div style="border: 1px solid rgba(255,255,255,0.1); border-radius: var(--radius); padding: 2rem;">
          <div style="font-size: 1.5rem; margin-bottom: 1rem; color: var(--gold);">✝</div>
          <strong style="display: block; color: var(--white); font-family: 'Montserrat', sans-serif; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.75rem;">Kasih</strong>
          <p style="font-size: 0.875rem; color: rgba(255,255,255,0.5); line-height: 1.7; font-family: 'Outfit', sans-serif;">Mengasihi Tuhan dan sesama adalah inti dari setiap pelayanan kami</p>
        </div>
        <div style="border: 1px solid rgba(255,255,255,0.1); border-radius: var(--radius); padding: 2rem;">
          <div style="font-size: 1.5rem; margin-bottom: 1rem; color: var(--gold);">✝</div>
          <strong style="display: block; color: var(--white); font-family: 'Montserrat', sans-serif; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.75rem;">Iman</strong>
          <p style="font-size: 0.875rem; color: rgba(255,255,255,0.5); line-height: 1.7; font-family: 'Outfit', sans-serif;">Percaya sepenuhnya pada janji dan kuasa Firman Allah</p>
        </div>
        <div style="border: 1px solid rgba(255,255,255,0.1); border-radius: var(--radius); padding: 2rem;">
          <div style="font-size: 1.5rem; margin-bottom: 1rem; color: var(--gold);">✝</div>
          <strong style="display: block; color: var(--white); font-family: 'Montserrat', sans-serif; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.75rem;">Penginjilan</strong>
          <p style="font-size: 0.875rem; color: rgba(255,255,255,0.5); line-height: 1.7; font-family: 'Outfit', sans-serif;">Membagikan kabar baik kepada setiap orang yang belum mengenal Kristus</p>
        </div>
        <div style="border: 1px solid rgba(255,255,255,0.1); border-radius: var(--radius); padding: 2rem;">
          <div style="font-size: 1.5rem; margin-bottom: 1rem; color: var(--gold);">✝</div>
          <strong style="display: block; color: var(--white); font-family: 'Montserrat', sans-serif; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.75rem;">Komunitas</strong>
          <p style="font-size: 0.875rem; color: rgba(255,255,255,0.5); line-height: 1.7; font-family: 'Outfit', sans-serif;">Bersama-sama bertumbuh sebagai satu tubuh Kristus yang utuh</p>
        </div>
      </div>
    </div>
  </section>

</div>
@endsection