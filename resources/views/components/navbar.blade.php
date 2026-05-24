<nav class="navbar" id="navbar">
  <div class="nav-inner">
    <a href="{{ route('home') }}" class="logo">
      <div class="logo-cross" id="logo-cross-btn"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:1em;height:1em;"><rect x="10" y="2" width="4" height="22"/><rect x="2" y="7" width="20" height="4"/></svg></div>
      <div>
        <div class="logo-name">GGP Sola Gratia</div>
        <div class="logo-tagline">Gereja Gerakan Pentakosta</div>
      </div>
    </a>

    <button class="burger" id="burger" data-action="toggle-menu" aria-label="Buka menu" aria-expanded="false">&#9776;</button>

    <ul class="nav-links" id="navLinks">
      <li>
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
          Beranda
        </a>
      </li>

      <li class="dropdown {{ request()->routeIs('visi-misi') || request()->routeIs('gembala') ? 'active' : '' }}">
        <a href="#" class="dropdown-toggle">Tentang Kami ▾</a>
        <ul class="dropdown-menu">
          <li>
            <a href="{{ route('visi-misi') }}" class="{{ request()->routeIs('visi-misi') ? 'active' : '' }}">
              Visi &amp; Misi
            </a>
          </li>
          <li>
            <a href="{{ route('gembala') }}" class="{{ request()->routeIs('gembala') ? 'active' : '' }}">
              Gembala &amp; PIC
            </a>
          </li>
        </ul>
      </li>

      <li class="dropdown {{ request()->routeIs('renungan') || request()->routeIs('sermon') ? 'active' : '' }}">
        <a href="#" class="dropdown-toggle">Firman ▾</a>
        <ul class="dropdown-menu">
          <li>
            <a href="{{ route('renungan') }}" class="{{ request()->routeIs('renungan') ? 'active' : '' }}">
              Renungan Harian
            </a>
          </li>
          <li>
            <a href="{{ route('sermon') }}" class="{{ request()->routeIs('sermon') ? 'active' : '' }}">
              Sermon / Khotbah
            </a>
          </li>
        </ul>
      </li>

      <li class="dropdown {{ request()->routeIs('jadwal') || request()->routeIs('pengumuman') || request()->routeIs('pastoral') ? 'active' : '' }}">
        <a href="#" class="dropdown-toggle">Informasi ▾</a>
        <ul class="dropdown-menu">
          <li>
            <a href="{{ route('jadwal') }}" class="{{ request()->routeIs('jadwal') ? 'active' : '' }}">
              Jadwal Ibadah
            </a>
          </li>
          <li>
            <a href="{{ route('pengumuman') }}" class="{{ request()->routeIs('pengumuman') ? 'active' : '' }}">
              Pengumuman
            </a>
          </li>
          <li>
            <a href="{{ route('pastoral') }}" class="{{ request()->routeIs('pastoral') ? 'active' : '' }}">
              Info Pastoral
            </a>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>