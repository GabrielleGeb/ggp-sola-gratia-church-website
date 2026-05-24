<footer class="footer">
  <div class="container footer-inner">
    <div class="footer-brand">
      <div class="logo-cross big">&#10013;</div>
      <div class="logo-name">GGP Sola Gratia</div>
      <p>Jl. Kertanegara No.26, Dusun Tratap, Sawotratap, Kec. Gedangan,<br>Kab. Sidoarjo, Jawa Timur, Indonesia</p>
    </div>
    <div class="footer-links">
      <h4>Navigasi</h4>
      <ul>
        <li><a href="{{ route('visi-misi') }}">Visi &amp; Misi</a></li>
        <li><a href="{{ route('gembala') }}">Gembala &amp; PIC</a></li>
        <li><a href="{{ route('renungan') }}">Renungan Harian</a></li>
        <li><a href="{{ route('sermon') }}">Sermon</a></li>
        <li><a href="{{ route('jadwal') }}">Jadwal Ibadah</a></li>
        <li><a href="{{ route('pengumuman') }}">Pengumuman</a></li>
        <li><a href="{{ route('pastoral') }}">Info Pastoral</a></li>
      </ul>
    </div>
    <div class="footer-contact">
      <h4>Kontak</h4>
      <p><i data-lucide="phone" class="footer-icon"></i> +62 812-3456-7890</p>
      <p><i data-lucide="mail" class="footer-icon"></i> info@ggpsolagratia.id</p>
      <!-- <p><i data-lucide="clock" class="footer-icon"></i> Ibadah: Minggu 08.00 | 10.30 | 17.00</p> -->
    </div>
  </div>
  <div class="footer-bottom">
    © 2026 GGP Sola Gratia. Dikelola oleh Tim IT Gereja.
    <a href="{{ route('admin') }}" class="footer-admin-btn">
      <i data-lucide="settings"></i>
    </a>
  </div>
</footer>