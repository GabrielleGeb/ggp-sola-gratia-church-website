const URUTAN_HARI = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
const API = "/api";

// ============================================================
// SECURITY
// ============================================================

async function sha256(str) {
  const buffer = await crypto.subtle.digest("SHA-256", new TextEncoder().encode(str));
  return Array.from(new Uint8Array(buffer)).map((b) => b.toString(16).padStart(2, "0")).join("");
}

function escHtml(str) {
  return String(str || "")
    .replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;").replace(/'/g, "&#39;");
}

// SESSION — token dari API disimpan di localStorage (persist across page reloads)
function getToken() { return localStorage.getItem("adminToken") || null; }
function setToken(token) { token ? localStorage.setItem("adminToken", token) : localStorage.removeItem("adminToken"); }
function isAdminLoggedIn() { return !!getToken(); }


// API HELPER
async function apiFetch(method, path, data = null) {
  const opts = { method, headers: { "Content-Type": "application/json", "Accept": "application/json" } };
  const token = getToken();
  if (token) opts.headers["X-Admin-Token"] = token;
  if (data) opts.body = JSON.stringify(data);
  const res = await fetch(API + path, opts);
  if (!res.ok) { const err = await res.json().catch(() => ({})); throw new Error(err.error || `Error ${res.status}`); }
  return res.json();
}

const api = {
  get:    (path)       => apiFetch("GET",    path),
  post:   (path, data) => apiFetch("POST",   path, data),
  put:    (path, data) => apiFetch("PUT",    path, data),
  delete: (path)       => apiFetch("DELETE", path),
};

// NAVIGATION
function toggleMenu() {
  const nav = document.getElementById("navLinks");
  const burger = document.getElementById("burger");
  const isOpen = nav.classList.toggle("open");
  if (burger) burger.setAttribute("aria-expanded", String(isOpen));
}

function closeMenu() {
  const nav = document.getElementById("navLinks");
  const burger = document.getElementById("burger");
  if (nav) nav.classList.remove("open");
  if (burger) burger.setAttribute("aria-expanded", "false");
}

// ============================================================
// RENDER — HOME
// ============================================================

async function renderHomeRenungan() {
  const list = document.getElementById("home-renungan-list");
  if (!list) return;
  try {
    const items = await api.get("/renungan");
    list.innerHTML = items.slice(0, 3).map((r) => `
      <div class="content-card" data-action="open-renungan" data-id="${r.id}">
        <div class="card-date">${formatDate(r.tanggal)}</div>
        <h3>${escHtml(r.judul)}</h3>
        <p>${escHtml(r.isi.substring(0, 120))}...</p>
        <span class="card-tag">${escHtml(r.ayat)}</span>
      </div>
    `).join("") || '<p style="color:var(--text-light)">Belum ada renungan.</p>';
  } catch { list.innerHTML = '<p style="color:var(--text-light)">Gagal memuat renungan.</p>'; }
}

async function renderHomeJadwal() {
  const list = document.getElementById("home-jadwal-list");
  if (!list) return;
  try {
    const items = await api.get("/jadwal");
    const sorted = items.sort((a, b) => URUTAN_HARI.indexOf(a.hari) - URUTAN_HARI.indexOf(b.hari)).slice(0, 4);
    list.innerHTML = sorted.map((j) => `
      <div class="jadwal-preview-card">
        <div class="jadwal-preview-hari">${escHtml(j.hari)}</div>
        <div class="jadwal-preview-nama">${escHtml(j.nama)}</div>
        <div class="jadwal-preview-jam">${escHtml(j.jam)} WIB</div>
        <div class="jadwal-preview-tempat">${escHtml(j.tempat)}</div>
      </div>
    `).join("") || '<p style="color:var(--text-light)">Belum ada jadwal.</p>';
  } catch { list.innerHTML = '<p style="color:var(--text-light)">Gagal memuat jadwal.</p>'; }
}

async function renderHomePengumuman() {
  const list = document.getElementById("home-pengumuman-list");
  if (!list) return;
  try {
    const items = await api.get("/pengumuman");
    list.innerHTML = items.slice(0, 3).map((p) => `
      <div class="pengumuman-preview-item ${p.penting ? "penting" : ""}" data-action="goto-pengumuman">
        ${p.penting ? '<span class="badge-penting">Penting</span>' : ""}
        <div class="card-date">${formatDate(p.tanggal)}</div>
        <strong>${escHtml(p.judul)}</strong>
        <p>${escHtml(p.isi.substring(0, 100))}...</p>
      </div>
    `).join("") || '<p style="color:var(--text-light)">Belum ada pengumuman.</p>';
  } catch { list.innerHTML = '<p style="color:var(--text-light)">Gagal memuat pengumuman.</p>'; }
}

// ============================================================
// RENDER — RENUNGAN
// ============================================================

async function renderRenungan() {
  const list = document.getElementById("renungan-list");
  if (!list) return;
  list.style.display = "";
  try {
    const items = await api.get("/renungan");
    list.innerHTML = items.length ? items.map((r) => `
      <div class="content-card" data-action="open-renungan" data-id="${r.id}">
        <div class="card-date">${formatDate(r.tanggal)}</div>
        <h3>${escHtml(r.judul)}</h3>
        <p>${escHtml(r.isi.substring(0, 140))}...</p>
        <span class="card-tag">${escHtml(r.ayat)}</span>
      </div>
    `).join("") : '<p style="color:var(--text-light)">Belum ada renungan.</p>';
  } catch { list.innerHTML = '<p style="color:var(--text-light)">Gagal memuat data.</p>'; }
}

async function openRenungan(id) {
  const list = document.getElementById("renungan-list");
  const detail = document.getElementById("renungan-detail");
  if (!detail) return;
  if (list) list.style.display = "none";
  detail.style.display = "block";
  document.getElementById("renungan-detail-content").innerHTML = `<p>Memuat...</p>`;
  try {
    const items = await api.get("/renungan");
    const item = items.find((r) => r.id == id);
    if (!item) return;
    document.getElementById("renungan-detail-content").innerHTML = `
      <h2>${escHtml(item.judul)}</h2>
      <div class="article-meta">${formatDate(item.tanggal)} &nbsp;|&nbsp; ${escHtml(item.penulis)}</div>
      <div class="article-ayat">Ayat Kunci: ${escHtml(item.ayat)}</div>
      <div class="article-isi">${escHtml(item.isi)}</div>
    `;
    detail.scrollIntoView({ behavior: "smooth" });
  } catch {}
}

function closeDetail(type) {
  const detail = document.getElementById(type + "-detail");
  const list = document.getElementById(type + "-list");
  if (detail) detail.style.display = "none";
  if (list) list.style.display = "";
  const rerenders = { renungan: renderRenungan, sermon: renderSermon, pengumuman: renderPengumuman };
  if (rerenders[type]) rerenders[type]();
}

// ============================================================
// RENDER — SERMON
// ============================================================

async function renderSermon() {
  const list = document.getElementById("sermon-list");
  if (!list) return;
  list.style.display = "";
  try {
    const items = await api.get("/sermon");
    list.innerHTML = items.length ? items.map((s) => `
      <div class="sermon-card" data-action="open-sermon" data-id="${s.id}">
        ${s.seri ? `<span class="sermon-series">${escHtml(s.seri)}</span>` : ""}
        <div class="sermon-meta">${formatDate(s.tanggal)} &nbsp;|&nbsp; ${escHtml(s.pembicara)}</div>
        <h3>${escHtml(s.judul)}</h3>
        <p style="font-size:0.875rem;color:var(--text-light);margin-top:0.5rem">${escHtml(s.isi.substring(0, 120))}...</p>
      </div>
    `).join("") : '<p style="color:var(--text-light)">Belum ada sermon.</p>';
  } catch { list.innerHTML = '<p style="color:var(--text-light)">Gagal memuat data.</p>'; }
}

async function openSermon(id) {
  const list = document.getElementById("sermon-list");
  const detail = document.getElementById("sermon-detail");
  if (!detail) return;
  if (list) list.style.display = "none";
  detail.style.display = "block";
  document.getElementById("sermon-detail-content").innerHTML = `<p>Memuat...</p>`;
  try {
    const items = await api.get("/sermon");
    const item = items.find((s) => s.id == id);
    if (!item) return;
    document.getElementById("sermon-detail-content").innerHTML = `
      <h2>${escHtml(item.judul)}</h2>
      <div class="article-meta">${formatDate(item.tanggal)} &nbsp;|&nbsp; ${escHtml(item.pembicara)}${item.seri ? " &nbsp;|&nbsp; " + escHtml(item.seri) : ""}</div>
      ${item.yt ? `<div style="margin-bottom:1.5rem"><a href="${escHtml(item.yt)}" target="_blank" rel="noopener" class="btn-primary">Tonton di YouTube</a></div>` : ""}
      <div class="article-isi">${escHtml(item.isi)}</div>
    `;
    detail.scrollIntoView({ behavior: "smooth" });
  } catch {}
}

// ============================================================
// RENDER — JADWAL (UPDATED: tombol edit/hapus kalau admin login)
// ============================================================

async function renderJadwal() {
  const list = document.getElementById("jadwal-list");
  if (!list) return;
  try {
    const items = await api.get("/jadwal");
    if (!items.length) { list.innerHTML = '<p style="color:var(--text-light)">Belum ada jadwal ibadah.</p>'; return; }
    const grouped = {};
    URUTAN_HARI.forEach((h) => { grouped[h] = []; });
    items.forEach((j) => { if (grouped[j.hari]) grouped[j.hari].push(j); });
    const adminLoggedIn = isAdminLoggedIn();
    list.innerHTML = URUTAN_HARI.filter((h) => grouped[h].length > 0).map((hari) => `
      <div class="jadwal-day-group">
        <div class="jadwal-day-title">${hari}</div>
        ${grouped[hari].sort((a, b) => a.jam.localeCompare(b.jam)).map((j) => `
          <div class="jadwal-card">
            <div class="jadwal-hari">${escHtml(j.jam)}</div>
            <div class="jadwal-info">
              <h3>${escHtml(j.nama)}</h3>
              <div class="jadwal-meta"><span>${escHtml(j.tempat)}</span></div>
              ${j.keterangan ? `<p class="jadwal-ket">${escHtml(j.keterangan)}</p>` : ""}
            </div>
            ${adminLoggedIn ? `
            <div style="display:flex;gap:8px;margin-left:auto;flex-shrink:0;align-items:center;">
              <button class="edit-btn" data-action="open-edit" data-type="jadwal" data-id="${j.id}">Edit</button>
              <button class="delete-btn" data-action="delete-item" data-endpoint="/jadwal/${j.id}" data-listtype="jadwal-public">Hapus</button>
            </div>` : ""}
          </div>
        `).join("")}
      </div>
    `).join("");
  } catch { list.innerHTML = '<p style="color:var(--text-light)">Gagal memuat jadwal.</p>'; }
}

// ============================================================
// RENDER — PENGUMUMAN
// ============================================================

async function renderPengumuman() {
  const list = document.getElementById("pengumuman-list");
  if (!list) return;
  list.style.display = "";
  try {
    const items = await api.get("/pengumuman");
    list.innerHTML = items.length ? items.map((p) => `
      <div class="content-card ${p.penting ? "card-penting" : ""}" data-action="open-pengumuman" data-id="${p.id}">
        ${p.penting ? '<span class="badge-penting">Penting</span>' : ""}
        <div class="card-date">${formatDate(p.tanggal)}</div>
        <h3>${escHtml(p.judul)}</h3>
        <p>${escHtml(p.isi.substring(0, 130))}...</p>
      </div>
    `).join("") : '<p style="color:var(--text-light)">Belum ada pengumuman.</p>';
  } catch { list.innerHTML = '<p style="color:var(--text-light)">Gagal memuat data.</p>'; }
}

async function openPengumuman(id) {
  const list = document.getElementById("pengumuman-list");
  const detail = document.getElementById("pengumuman-detail");
  if (!detail) return;
  if (list) list.style.display = "none";
  detail.style.display = "block";
  document.getElementById("pengumuman-detail-content").innerHTML = `<p>Memuat...</p>`;
  try {
    const items = await api.get("/pengumuman");
    const item = items.find((p) => p.id == id);
    if (!item) return;
    document.getElementById("pengumuman-detail-content").innerHTML = `
      <h2>${escHtml(item.judul)}</h2>
      <div class="article-meta">${formatDate(item.tanggal)}${item.penting ? ' &nbsp;|&nbsp; <span class="badge-penting">Penting</span>' : ""}</div>
      <div class="article-isi">${escHtml(item.isi)}</div>
    `;
    detail.scrollIntoView({ behavior: "smooth" });
  } catch {}
}

// ============================================================
// RENDER — GEMBALA
// ============================================================

async function renderGembala() {
  const list = document.getElementById("gembala-list");
  if (!list) return;
  try {
    const items = await api.get("/gembala");
    list.innerHTML = items.map((g) => `
      <div class="pastor-card">
        <div class="pastor-photo">
          ${g.foto ? `<img src="${g.foto}" alt="${escHtml(g.nama)}" style="width:100%;height:100%;object-fit:cover">` : `<i data-lucide="user-circle" style="width:64px;height:64px;color:rgba(201,168,76,0.4)"></i>`}
        </div>
        <div class="pastor-info">
          <div class="pastor-jabatan">${escHtml(g.jabatan)}</div>
          <h3>${escHtml(g.nama)}</h3>
          <p class="pastor-bio">${escHtml(g.bio)}</p>
        </div>
      </div>
    `).join("") || "<p>Belum ada data.</p>";
    if (typeof lucide !== "undefined") lucide.createIcons();
  } catch { list.innerHTML = '<p>Gagal memuat data.</p>'; }
}

// ============================================================
// RENDER — KONTAK PASTORAL
// ============================================================

async function renderKontakWA() {
  const el = document.getElementById("kontak-wa-list");
  if (!el) return;
  try {
    const items = await api.get("/kontak");
    el.innerHTML = items.map((k) => `
      <div class="kontak-wa-item">
        <div class="kontak-wa-info">
          <span>${escHtml(k.jabatan)}</span>
          <strong>${escHtml(k.nama)}</strong>
          <span class="kontak-number">+${escHtml(k.hp)}</span>
        </div>
        <div class="kontak-wa-btns">
          <a class="wa-btn" href="https://wa.me/${encodeURIComponent(k.hp)}" target="_blank" rel="noopener">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            WhatsApp
          </a>
          <button class="copy-btn" data-action="copy-nomor" data-hp="${escHtml(k.hp)}">Salin</button>
        </div>
      </div>
    `).join("") || '<p style="color:rgba(255,255,255,0.4)">Belum ada kontak.</p>';
  } catch { el.innerHTML = '<p style="color:rgba(255,255,255,0.4)">Gagal memuat kontak.</p>'; }
}

// ============================================================
// RENDER — JEMAAT
// ============================================================

async function renderJemaat(filter = "", kategori = "") {
  const tbody = document.getElementById("jemaat-tbody");
  if (!tbody) return;
  try {
    let items = await api.get("/jemaat");
    if (filter) items = items.filter((j) => j.nama.toLowerCase().includes(filter.toLowerCase()));
    if (kategori) items = items.filter((j) => j.kategori === kategori);
    tbody.innerHTML = items.map((j, i) => {
      const ttlStr = j.kota || j.ttl ? `${escHtml(j.kota || "—")}, ${j.ttl ? formatDate(j.ttl) : "—"}` : "—";
      const isBday = isBirthdayToday(j.ttl);
      return `<tr${isBday ? ' class="bday-row"' : ""}>
        <td>${i + 1}</td>
        <td><strong>${escHtml(j.nama)}</strong>${isBday ? ' <span class="bday-indicator">HUT</span>' : ""}</td>
        <td><span class="badge badge-${escHtml(j.kategori.toLowerCase())}">${escHtml(j.kategori)}</span></td>
        <td>${ttlStr}</td>
        <td>${escHtml(j.hp)}</td>
        <td>${escHtml(j.alamat)}</td>
        <td><span class="badge ${j.baptis === "Sudah Baptis" ? "badge-baptis" : "badge-belum"}">${escHtml(j.baptis)}</span></td>
      </tr>`;
    }).join("") || '<tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--text-light)">Tidak ada data.</td></tr>';
  } catch { tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:2rem">Gagal memuat data.</td></tr>'; }
}

function filterJemaat() {
  const q = document.getElementById("jemaat-search")?.value || "";
  const k = document.getElementById("jemaat-filter")?.value || "";
  renderJemaat(q, k);
}

async function renderJemaatStats() {
  const statsBar = document.getElementById("jemaat-stats");
  if (!statsBar) return;
  try {
    const items = await api.get("/jemaat");
    const total = items.length;
    const perKat = { Dewasa: 0, Pemuda: 0, Remaja: 0, Anak: 0 };
    let sudahBaptis = 0;
    items.forEach((j) => { if (perKat[j.kategori] !== undefined) perKat[j.kategori]++; if (j.baptis === "Sudah Baptis") sudahBaptis++; });
    statsBar.innerHTML = `
      <div class="stat-chip"><strong>${total}</strong>Total</div>
      <div class="stat-chip"><strong>${perKat.Dewasa}</strong>Dewasa</div>
      <div class="stat-chip"><strong>${perKat.Pemuda}</strong>Pemuda</div>
      <div class="stat-chip"><strong>${perKat.Remaja}</strong>Remaja</div>
      <div class="stat-chip"><strong>${perKat.Anak}</strong>Anak</div>
      <div class="stat-chip"><strong>${sudahBaptis}</strong>Sudah Baptis</div>
      <div class="stat-chip"><strong>${total - sudahBaptis}</strong>Belum Baptis</div>
    `;
  } catch {}
}

function updateJemaatGuard() {
  const guard = document.getElementById("jemaat-guard");
  const content = document.getElementById("jemaat-content");
  if (!guard || !content) return;
  if (isAdminLoggedIn()) {
    guard.style.display = "none";
    content.style.display = "block";
    renderJemaatStats();
    renderJemaat();
  } else {
    guard.style.display = "block";
    content.style.display = "none";
  }
}

async function exportJemaatCSV() {
  try {
    const items = await api.get("/jemaat");
    const headers = ["No", "Nama Lengkap", "Kategori", "Tempat Lahir", "Tanggal Lahir", "No. HP", "Alamat", "Status Baptis"];
    const rows = items.map((j, i) => [i + 1, j.nama, j.kategori, j.kota || "", j.ttl || "", j.hp, j.alamat, j.baptis]);
    const csv = [headers, ...rows].map((row) => row.map((cell) => `"${String(cell).replace(/"/g, '""')}"`).join(",")).join("\r\n");
    const blob = new Blob(["\uFEFF" + csv], { type: "text/csv;charset=utf-8;" });
    const url = URL.createObjectURL(blob);
    const a = Object.assign(document.createElement("a"), { href: url, download: `data-jemaat-${new Date().toISOString().slice(0, 10)}.csv` });
    document.body.appendChild(a); a.click(); document.body.removeChild(a);
    URL.revokeObjectURL(url);
    showToast("Data jemaat berhasil diekspor!");
  } catch { showToast("Gagal mengekspor data."); }
}

// ============================================================
// ADMIN AUTH
// ============================================================

async function adminLogin() {
  const username = document.getElementById("admin-user")?.value.trim();
  const password = document.getElementById("admin-pass")?.value;
  const errBox = document.getElementById("admin-login-error");
  if (!username || !password) { errBox.style.display = "block"; errBox.textContent = "Harap isi username dan password."; return; }
  try {
    console.log('Attempting login with:', username);
    const res = await fetch(API + "/login", { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ username, password }) });
    console.log('Login response status:', res.status);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const data = await res.json();
    console.log('Login response data:', data);
    setToken(data.token);
    console.log('Token set, current token:', getToken());
    errBox.style.display = "none";
    document.getElementById("admin-login").style.display = "none";
    document.getElementById("admin-panel").style.display = "block";
    renderAdminLists();
    loadVisiInput();
    checkBirthdays();
    setActiveAdminTab();
    if (typeof lucide !== "undefined") lucide.createIcons();
  } catch (error) {
    console.error('Login error:', error);
    errBox.style.display = "block";
    errBox.textContent = "Username atau password salah.";
    document.getElementById("admin-pass").value = "";
    document.getElementById("admin-pass").focus();
  }
}

function adminLogout() { setToken(null); window.location.href = "/"; }

let _logoClicks = 0, _logoTimer = null;
function handleLogoClick(e) {
  e.preventDefault(); _logoClicks++;
  clearTimeout(_logoTimer);
  if (_logoClicks >= 5) { _logoClicks = 0; showToast("Membuka Panel Admin..."); setTimeout(() => { window.location.href = "/admin"; }, 400); return; }
  if (_logoClicks >= 2) showToast(`${5 - _logoClicks}x lagi...`, 1000);
  _logoTimer = setTimeout(() => { _logoClicks = 0; }, 3000);
}

async function changePassword() {
  const oldPass = document.getElementById("ap-old")?.value;
  const newPass = document.getElementById("ap-new")?.value;
  const confirm = document.getElementById("ap-confirm")?.value;
  if (!oldPass || !newPass || !confirm) return showToast("Harap isi semua field!");
  if (newPass !== confirm) return showToast("Password baru tidak cocok!");
  if (newPass.length < 6) return showToast("Password minimal 6 karakter!");
  try {
    const newHash = await sha256(newPass);
    await api.post("/change-password", { new_hash: newHash });
    ["ap-old", "ap-new", "ap-confirm"].forEach((id) => { document.getElementById(id).value = ""; });
    showToast("Password berhasil diubah!");
  } catch { showToast("Gagal mengubah password."); }
}

function adminTab(btn) {
  const target = btn.dataset.target;
  document.querySelectorAll(".admin-tab-content").forEach((t) => t.classList.remove("active"));
  document.querySelectorAll(".tab-btn:not(.logout)").forEach((b) => b.classList.remove("active"));
  const content = document.getElementById("admin-" + target);
  if (content) content.classList.add("active");
  btn.classList.add("active");

  if (btn.tagName.toLowerCase() === "a" && btn.href) {
    const url = new URL(btn.href, window.location.origin);
    window.history.pushState(null, "", url.pathname + url.search + url.hash);
    const page = document.getElementById("page-admin");
    if (page) page.dataset.adminTab = target;
  }
}

function getAdminTabFromPath() {
  const segments = window.location.pathname.split('/').filter(Boolean);
  const tab = segments[1] || 'renungan';
  return {
    renungan: 'renungan',
    sermon: 'sermon',
    jadwal: 'jadwal-admin',
    pengumuman: 'pengumuman-admin',
    gembala: 'gembala',
    kontak: 'kontak-admin',
    jemaat: 'jemaat-admin',
    visi: 'visi-admin',
    setelan: 'setelan',
  }[tab] || 'renungan';
}

function setActiveAdminTab() {
  const page = document.getElementById("page-admin");
  const activeTab = page?.dataset.adminTab || getAdminTabFromPath();
  const button = document.querySelector(`.tab-btn[data-target="${activeTab}"]`);
  if (button) adminTab(button);
}

window.addEventListener('popstate', () => {
  const page = document.getElementById("page-admin");
  if (page) {
    page.dataset.adminTab = getAdminTabFromPath();
    setActiveAdminTab();
  }
});

// ============================================================
// MODAL EDIT
// ============================================================

let _editId = null, _editType = null;

async function openEditModal(type, id) {
  _editId = id; _editType = type;
  const modal = document.getElementById("edit-modal");
  const body = document.getElementById("edit-modal-body");
  const title = document.getElementById("edit-modal-title");
  const endpointMap = { renungan: "/renungan", sermon: "/sermon", jadwal: "/jadwal", pengumuman: "/pengumuman", gembala: "/gembala", kontak: "/kontak", jemaat: "/jemaat" };
  try {
    const items = await api.get(endpointMap[type]);
    const item = items.find((i) => i.id == id);
    if (!item) return;
    if (type === "renungan") {
      title.textContent = "Edit Renungan";
      body.innerHTML = `
        <div class="form-row">
          <div class="form-group"><label>Tanggal</label><input type="date" id="e-tanggal" value="${escHtml(item.tanggal)}"></div>
          <div class="form-group"><label>Ayat Kunci</label><input type="text" id="e-ayat" value="${escHtml(item.ayat)}"></div>
        </div>
        <div class="form-group"><label>Judul</label><input type="text" id="e-judul" value="${escHtml(item.judul)}"></div>
        <div class="form-group"><label>Isi Renungan</label><textarea id="e-isi" rows="8">${escHtml(item.isi)}</textarea></div>
        <div class="form-group"><label>Penulis</label><input type="text" id="e-penulis" value="${escHtml(item.penulis)}"></div>`;
    } else if (type === "sermon") {
      title.textContent = "Edit Sermon";
      body.innerHTML = `
        <div class="form-row">
          <div class="form-group"><label>Tanggal</label><input type="date" id="e-tanggal" value="${escHtml(item.tanggal)}"></div>
          <div class="form-group"><label>Seri</label><input type="text" id="e-seri" value="${escHtml(item.seri || "")}"></div>
        </div>
        <div class="form-group"><label>Judul</label><input type="text" id="e-judul" value="${escHtml(item.judul)}"></div>
        <div class="form-group"><label>Pembicara</label><input type="text" id="e-pembicara" value="${escHtml(item.pembicara)}"></div>
        <div class="form-group"><label>Isi / Ringkasan</label><textarea id="e-isi" rows="6">${escHtml(item.isi)}</textarea></div>
        <div class="form-group"><label>Link YouTube</label><input type="url" id="e-yt" value="${escHtml(item.yt || "")}"></div>`;
    } else if (type === "jadwal") {
      title.textContent = "Edit Jadwal Ibadah";
      body.innerHTML = `
        <div class="form-row">
          <div class="form-group"><label>Nama Ibadah</label><input type="text" id="e-nama" value="${escHtml(item.nama)}"></div>
          <div class="form-group"><label>Hari</label><select id="e-hari">${URUTAN_HARI.map((h) => `<option ${item.hari===h?"selected":""}>${h}</option>`).join("")}</select></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label>Jam</label><input type="time" id="e-jam" value="${escHtml(item.jam)}"></div>
          <div class="form-group"><label>Tempat</label><input type="text" id="e-tempat" value="${escHtml(item.tempat)}"></div>
        </div>
        <div class="form-group"><label>Keterangan</label><textarea id="e-keterangan" rows="2">${escHtml(item.keterangan || "")}</textarea></div>`;
    } else if (type === "pengumuman") {
      title.textContent = "Edit Pengumuman";
      body.innerHTML = `
        <div class="form-row">
          <div class="form-group"><label>Tanggal</label><input type="date" id="e-tanggal" value="${escHtml(item.tanggal)}"></div>
          <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:4px">
            <label style="display:flex;align-items:center;gap:0.5rem;text-transform:none;letter-spacing:0;font-size:0.875rem;cursor:pointer"><input type="checkbox" id="e-penting" ${item.penting?"checked":""}> Tandai Penting</label>
          </div>
        </div>
        <div class="form-group"><label>Judul</label><input type="text" id="e-judul" value="${escHtml(item.judul)}"></div>
        <div class="form-group"><label>Isi</label><textarea id="e-isi" rows="7">${escHtml(item.isi)}</textarea></div>`;
    } else if (type === "gembala") {
      title.textContent = "Edit Gembala / PIC";
      body.innerHTML = `
        <div class="form-row">
          <div class="form-group"><label>Nama</label><input type="text" id="e-nama" value="${escHtml(item.nama)}"></div>
          <div class="form-group"><label>Jabatan</label><input type="text" id="e-jabatan" value="${escHtml(item.jabatan)}"></div>
        </div>
        <div class="form-group"><label>Biografi</label><textarea id="e-bio" rows="4">${escHtml(item.bio)}</textarea></div>
        <div class="form-group">
          <label>Foto</label>
          <div class="photo-upload-area" id="e-upload-area" data-action="trigger-upload" data-target="e-foto-file">
            <div class="upload-placeholder" id="e-upload-placeholder" ${item.foto?'style="display:none"':""}>
              <i data-lucide="camera" class="upload-icon"></i><span>Klik untuk pilih foto</span><span class="upload-hint">JPG, PNG maks. 2MB</span>
            </div>
            <img id="e-foto-preview" class="upload-preview" src="${item.foto||""}" alt="Preview" style="${item.foto?"":"display:none"}">
          </div>
          <input type="file" id="e-foto-file" accept="image/jpeg,image/png,image/webp" style="display:none" data-action="handle-upload" data-prefix="e">
          <button type="button" class="clear-photo-btn" id="e-clear-foto" style="${item.foto?"":"display:none"}" data-action="clear-foto" data-target="e">Hapus Foto</button>
          <input type="hidden" id="e-foto-data" value="${item.foto||""}">
        </div>`;
    } else if (type === "kontak") {
      title.textContent = "Edit Kontak Pastoral";
      body.innerHTML = `
        <div class="form-row">
          <div class="form-group"><label>Nama</label><input type="text" id="e-nama" value="${escHtml(item.nama)}"></div>
          <div class="form-group"><label>Jabatan</label><input type="text" id="e-jabatan" value="${escHtml(item.jabatan)}"></div>
        </div>
        <div class="form-group"><label>No. WhatsApp</label><input type="tel" id="e-hp" value="${escHtml(item.hp)}">
          <small style="color:var(--text-light);font-size:0.75rem;display:block;margin-top:0.25rem">Format internasional tanpa +, mis: 628123456789</small>
        </div>`;
    } else if (type === "jemaat") {
      title.textContent = "Edit Data Jemaat";
      body.innerHTML = `
        <div class="form-row">
          <div class="form-group"><label>Nama Lengkap</label><input type="text" id="e-nama" value="${escHtml(item.nama)}"></div>
          <div class="form-group"><label>Kategori</label><select id="e-kategori">${["Dewasa","Pemuda","Remaja","Anak"].map((k)=>`<option ${item.kategori===k?"selected":""}>${k}</option>`).join("")}</select></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label>No. HP</label><input type="tel" id="e-hp" value="${escHtml(item.hp)}"></div>
          <div class="form-group"><label>Status Baptis</label><select id="e-baptis"><option ${item.baptis==="Sudah Baptis"?"selected":""}>Sudah Baptis</option><option ${item.baptis==="Belum Baptis"?"selected":""}>Belum Baptis</option></select></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label>Tempat Lahir</label><input type="text" id="e-kota" value="${escHtml(item.kota||"")}"></div>
          <div class="form-group"><label>Tanggal Lahir</label><input type="date" id="e-ttl" value="${escHtml(item.ttl||"")}"></div>
        </div>
        <div class="form-group"><label>Alamat</label><input type="text" id="e-alamat" value="${escHtml(item.alamat)}"></div>`;
    }
    modal.classList.add("open");
    if (typeof lucide !== "undefined") lucide.createIcons();
  } catch { showToast("Gagal memuat data."); }
}

function closeEditModal() { document.getElementById("edit-modal")?.classList.remove("open"); _editId = null; _editType = null; }

async function saveEdit() {
  const type = _editType, id = _editId;
  let data = {}, endpoint = "";
  try {
    if (type === "renungan") {
      const tanggal=document.getElementById("e-tanggal").value, judul=document.getElementById("e-judul").value.trim(), ayat=document.getElementById("e-ayat").value.trim(), isi=document.getElementById("e-isi").value.trim(), penulis=document.getElementById("e-penulis").value.trim();
      if (!tanggal||!judul||!isi) return showToast("Tanggal, judul & isi wajib diisi!");
      data={tanggal,judul,ayat,isi,penulis}; endpoint=`/renungan/${id}`;
    } else if (type === "sermon") {
      const tanggal=document.getElementById("e-tanggal").value, judul=document.getElementById("e-judul").value.trim(), pembicara=document.getElementById("e-pembicara").value.trim(), seri=document.getElementById("e-seri").value.trim(), isi=document.getElementById("e-isi").value.trim(), yt=document.getElementById("e-yt").value.trim();
      if (!tanggal||!judul||!isi) return showToast("Tanggal, judul & isi wajib diisi!");
      data={tanggal,judul,pembicara,seri,isi,yt}; endpoint=`/sermon/${id}`;
    } else if (type === "jadwal") {
      const nama=document.getElementById("e-nama").value.trim(), hari=document.getElementById("e-hari").value, jam=document.getElementById("e-jam").value, tempat=document.getElementById("e-tempat").value.trim(), keterangan=document.getElementById("e-keterangan").value.trim();
      if (!nama||!jam) return showToast("Nama & jam wajib diisi!");
      data={nama,hari,jam,tempat,keterangan}; endpoint=`/jadwal/${id}`;
    } else if (type === "pengumuman") {
      const tanggal=document.getElementById("e-tanggal").value, judul=document.getElementById("e-judul").value.trim(), isi=document.getElementById("e-isi").value.trim(), penting=document.getElementById("e-penting").checked;
      if (!tanggal||!judul||!isi) return showToast("Tanggal, judul & isi wajib diisi!");
      data={tanggal,judul,isi,penting}; endpoint=`/pengumuman/${id}`;
    } else if (type === "gembala") {
      const nama=document.getElementById("e-nama").value.trim(), jabatan=document.getElementById("e-jabatan").value.trim(), bio=document.getElementById("e-bio").value.trim(), foto=document.getElementById("e-foto-data").value;
      if (!nama||!jabatan) return showToast("Nama & jabatan wajib diisi!");
      data={nama,jabatan,bio,foto}; endpoint=`/gembala/${id}`;
    } else if (type === "kontak") {
      const nama=document.getElementById("e-nama").value.trim(), jabatan=document.getElementById("e-jabatan").value.trim(), hp=document.getElementById("e-hp").value.trim();
      if (!nama||!hp) return showToast("Nama & no. HP wajib diisi!");
      data={nama,jabatan,hp}; endpoint=`/kontak/${id}`;
    } else if (type === "jemaat") {
      const nama=document.getElementById("e-nama").value.trim(), kategori=document.getElementById("e-kategori").value, hp=document.getElementById("e-hp").value.trim(), baptis=document.getElementById("e-baptis").value, alamat=document.getElementById("e-alamat").value.trim(), ttl=document.getElementById("e-ttl").value, kota=document.getElementById("e-kota").value.trim();
      if (!nama) return showToast("Nama wajib diisi!");
      data={nama,kategori,hp,baptis,alamat,ttl,kota}; endpoint=`/jemaat/${id}`;
    }
    await api.put(endpoint, data);
    closeEditModal();
    showToast("Data berhasil diperbarui!");
    const listTypeMap = { renungan:"renungan", sermon:"sermon", jadwal:"jadwal-admin", pengumuman:"pengumuman-admin", gembala:"gembala", kontak:"kontak-admin", jemaat:"jemaat-admin" };
    renderAdminList(listTypeMap[type]);
    // Kalau edit jadwal, refresh tampilan publik juga
    if (type === "jadwal") renderJadwal();
    if (type === "kontak") renderKontakWA();
  } catch (err) { showToast("Gagal menyimpan: " + err.message); }
}

function handleFotoUpload(prefix) {
  const file = document.getElementById(prefix+"-foto-file")?.files[0];
  if (!file) return;
  if (!["image/jpeg","image/png","image/webp"].includes(file.type)) { showToast("Hanya JPG, PNG, atau WEBP!"); return; }
  if (file.size > 2*1024*1024) { showToast("Ukuran foto maksimal 2MB!"); return; }
  const reader = new FileReader();
  reader.onload = (e) => {
    const data = e.target.result;
    document.getElementById(prefix+"-foto-data").value = data;
    document.getElementById(prefix+"-foto-preview").src = data;
    document.getElementById(prefix+"-foto-preview").style.display = "block";
    document.getElementById(prefix+"-upload-placeholder").style.display = "none";
    document.getElementById(prefix+"-clear-foto").style.display = "inline-block";
  };
  reader.readAsDataURL(file);
}

function clearFoto(prefix) {
  document.getElementById(prefix+"-foto-data").value = "";
  document.getElementById(prefix+"-foto-file").value = "";
  document.getElementById(prefix+"-foto-preview").src = "";
  document.getElementById(prefix+"-foto-preview").style.display = "none";
  document.getElementById(prefix+"-upload-placeholder").style.display = "flex";
  document.getElementById(prefix+"-clear-foto").style.display = "none";
}

// ============================================================
// ADD DATA
// ============================================================

async function addRenungan() {
  const tanggal=document.getElementById("r-tanggal").value, judul=document.getElementById("r-judul").value.trim(), ayat=document.getElementById("r-ayat").value.trim(), isi=document.getElementById("r-isi").value.trim(), penulis=document.getElementById("r-penulis").value.trim();
  if (!tanggal||!judul||!isi) return showToast("Harap isi tanggal, judul, dan isi!");
  try { await api.post("/renungan",{tanggal,judul,ayat,isi,penulis}); ["r-tanggal","r-judul","r-ayat","r-isi","r-penulis"].forEach((id)=>{document.getElementById(id).value="";}); renderAdminList("renungan"); showToast("Renungan berhasil disimpan!"); } catch(err){showToast("Gagal: "+err.message);}
}

async function addSermon() {
  const tanggal=document.getElementById("s-tanggal").value, judul=document.getElementById("s-judul").value.trim(), pembicara=document.getElementById("s-pembicara").value.trim(), seri=document.getElementById("s-seri").value.trim(), isi=document.getElementById("s-isi").value.trim(), yt=document.getElementById("s-yt").value.trim();
  if (!tanggal||!judul||!isi) return showToast("Harap isi tanggal, judul, dan isi!");
  try { await api.post("/sermon",{tanggal,judul,pembicara,seri,isi,yt}); ["s-tanggal","s-judul","s-pembicara","s-seri","s-isi","s-yt"].forEach((id)=>{document.getElementById(id).value="";}); renderAdminList("sermon"); showToast("Sermon berhasil disimpan!"); } catch(err){showToast("Gagal: "+err.message);}
}

async function addJadwal() {
  const nama=document.getElementById("j2-nama").value.trim(), hari=document.getElementById("j2-hari").value, jam=document.getElementById("j2-jam").value, tempat=document.getElementById("j2-tempat").value.trim(), keterangan=document.getElementById("j2-keterangan").value.trim();
  if (!nama||!jam) return showToast("Nama ibadah dan jam wajib diisi!");
  try { await api.post("/jadwal",{nama,hari,jam,tempat,keterangan}); ["j2-nama","j2-jam","j2-tempat","j2-keterangan"].forEach((id)=>{document.getElementById(id).value="";}); renderAdminList("jadwal-admin"); renderJadwal(); showToast("Jadwal berhasil disimpan!"); } catch(err){showToast("Gagal: "+err.message);}
}

async function addPengumuman() {
  const tanggal=document.getElementById("p2-tanggal").value, judul=document.getElementById("p2-judul").value.trim(), isi=document.getElementById("p2-isi").value.trim(), penting=document.getElementById("p2-penting").checked;
  if (!tanggal||!judul||!isi) return showToast("Tanggal, judul & isi wajib diisi!");
  try { await api.post("/pengumuman",{tanggal,judul,isi,penting}); ["p2-tanggal","p2-judul","p2-isi"].forEach((id)=>{document.getElementById(id).value="";}); document.getElementById("p2-penting").checked=false; renderAdminList("pengumuman-admin"); showToast("Pengumuman berhasil disimpan!"); } catch(err){showToast("Gagal: "+err.message);}
}

async function addGembala() {
  const nama=document.getElementById("g-nama").value.trim(), jabatan=document.getElementById("g-jabatan").value.trim(), bio=document.getElementById("g-bio").value.trim(), foto=document.getElementById("g-foto-data").value;
  if (!nama||!jabatan) return showToast("Harap isi nama dan jabatan!");
  try { await api.post("/gembala",{nama,jabatan,bio,foto}); ["g-nama","g-jabatan","g-bio"].forEach((id)=>{document.getElementById(id).value="";}); clearFoto("g"); renderAdminList("gembala"); showToast("Data gembala berhasil disimpan!"); } catch(err){showToast("Gagal: "+err.message);}
}

async function addKontak() {
  const nama=document.getElementById("k-nama").value.trim(), jabatan=document.getElementById("k-jabatan").value.trim(), hp=document.getElementById("k-hp").value.trim();
  if (!nama||!hp) return showToast("Harap isi nama dan nomor WhatsApp!");
  try { await api.post("/kontak",{nama,jabatan,hp}); ["k-nama","k-jabatan","k-hp"].forEach((id)=>{document.getElementById(id).value="";}); renderAdminList("kontak-admin"); showToast("Kontak berhasil disimpan!"); } catch(err){showToast("Gagal: "+err.message);}
}

async function addJemaat() {
  const nama=document.getElementById("j-nama").value.trim(), kategori=document.getElementById("j-kategori").value, hp=document.getElementById("j-hp").value.trim(), baptis=document.getElementById("j-baptis").value, alamat=document.getElementById("j-alamat").value.trim(), ttl=document.getElementById("j-ttl").value, kota=document.getElementById("j-kota").value.trim();
  if (!nama) return showToast("Harap isi nama jemaat!");
  try { await api.post("/jemaat",{nama,kategori,hp,alamat,baptis,ttl,kota}); ["j-nama","j-hp","j-alamat","j-ttl","j-kota"].forEach((id)=>{document.getElementById(id).value="";}); renderAdminList("jemaat-admin"); updateTotalJemaat(); checkBirthdays(); showToast("Data jemaat berhasil disimpan!"); } catch(err){showToast("Gagal: "+err.message);}
}

// ============================================================
// VISI MISI
// ============================================================

async function loadVisiInput() {
  try {
    const vm = await api.get("/visi-misi");
    const visiEl=document.getElementById("vm-visi-input"), misiEl=document.getElementById("vm-misi-input");
    if (visiEl) visiEl.value = vm.visi || "";
    if (misiEl) misiEl.value = vm.misi || "";
    const visiDisplay=document.getElementById("vm-visi"), misiDisplay=document.getElementById("vm-misi");
    if (visiDisplay && vm.visi) visiDisplay.innerText = vm.visi;
    if (misiDisplay && vm.misi) misiDisplay.innerText = vm.misi;
  } catch {}
}

async function saveVisiMisi() {
  const visi=document.getElementById("vm-visi-input").value.trim(), misi=document.getElementById("vm-misi-input").value.trim();
  if (!visi||!misi) return showToast("Visi dan misi tidak boleh kosong!");
  try { await api.post("/visi-misi",{visi,misi}); showToast("Visi & Misi berhasil diperbarui!"); } catch(err){showToast("Gagal: "+err.message);}
}

// ============================================================
// ADMIN LIST RENDERS
// ============================================================

function renderAdminLists() {
  ["renungan","sermon","jadwal-admin","pengumuman-admin","gembala","kontak-admin","jemaat-admin"].forEach(renderAdminList);
  updateTotalJemaat();
}

async function updateTotalJemaat() {
  const el = document.getElementById("total-jemaat");
  if (!el) return;
  try { const items = await api.get("/jemaat"); el.textContent = items.length; } catch {}
}

async function renderAdminList(type) {
  const endpointMap = { renungan:"/renungan", sermon:"/sermon", "jadwal-admin":"/jadwal", "pengumuman-admin":"/pengumuman", gembala:"/gembala", "kontak-admin":"/kontak", "jemaat-admin":"/jemaat" };
  const elMap = { renungan:"admin-renungan-list", sermon:"admin-sermon-list", "jadwal-admin":"admin-jadwal-list", "pengumuman-admin":"admin-pengumuman-list", gembala:"admin-gembala-list", "kontak-admin":"admin-kontak-list", "jemaat-admin":"admin-jemaat-list" };
  const el = document.getElementById(elMap[type]);
  if (!el) return;
  try {
    const items = await api.get(endpointMap[type]);
    let html = "";
    if (type === "renungan") {
      html = items.map((r)=>`<div class="admin-item"><div class="admin-item-info"><strong>${escHtml(r.judul)}</strong><span>${formatDate(r.tanggal)} · ${escHtml(r.penulis)} · ${escHtml(r.ayat)}</span></div><div class="admin-item-actions"><button class="edit-btn" data-action="open-edit" data-type="renungan" data-id="${r.id}">Edit</button><button class="delete-btn" data-action="delete-item" data-endpoint="/renungan/${r.id}" data-listtype="renungan">Hapus</button></div></div>`).join("") || noData();
    } else if (type === "sermon") {
      html = items.map((s)=>`<div class="admin-item"><div class="admin-item-info"><strong>${escHtml(s.judul)}</strong><span>${formatDate(s.tanggal)} · ${escHtml(s.pembicara)}${s.seri?" · "+escHtml(s.seri):""}</span></div><div class="admin-item-actions"><button class="edit-btn" data-action="open-edit" data-type="sermon" data-id="${s.id}">Edit</button><button class="delete-btn" data-action="delete-item" data-endpoint="/sermon/${s.id}" data-listtype="sermon">Hapus</button></div></div>`).join("") || noData();
    } else if (type === "jadwal-admin") {
      const sorted = items.sort((a,b)=>URUTAN_HARI.indexOf(a.hari)-URUTAN_HARI.indexOf(b.hari)||a.jam.localeCompare(b.jam));
      html = sorted.map((j)=>`<div class="admin-item"><div class="admin-item-info"><strong>${escHtml(j.nama)}</strong><span>${escHtml(j.hari)} · ${escHtml(j.jam)} WIB · ${escHtml(j.tempat)}</span></div><div class="admin-item-actions"><button class="edit-btn" data-action="open-edit" data-type="jadwal" data-id="${j.id}">Edit</button><button class="delete-btn" data-action="delete-item" data-endpoint="/jadwal/${j.id}" data-listtype="jadwal-admin">Hapus</button></div></div>`).join("") || noData();
    } else if (type === "pengumuman-admin") {
      html = items.map((p)=>`<div class="admin-item"><div class="admin-item-info"><strong>${p.penting?"📌 ":""}${escHtml(p.judul)}</strong><span>${formatDate(p.tanggal)}${p.penting?" · Penting":""}</span></div><div class="admin-item-actions"><button class="edit-btn" data-action="open-edit" data-type="pengumuman" data-id="${p.id}">Edit</button><button class="delete-btn" data-action="delete-item" data-endpoint="/pengumuman/${p.id}" data-listtype="pengumuman-admin">Hapus</button></div></div>`).join("") || noData();
    } else if (type === "gembala") {
      html = items.map((g)=>`<div class="admin-item"><div class="admin-item-info"><strong>${escHtml(g.nama)}</strong><span>${escHtml(g.jabatan)}</span></div><div class="admin-item-actions"><button class="edit-btn" data-action="open-edit" data-type="gembala" data-id="${g.id}">Edit</button><button class="delete-btn" data-action="delete-item" data-endpoint="/gembala/${g.id}" data-listtype="gembala">Hapus</button></div></div>`).join("") || noData();
    } else if (type === "kontak-admin") {
      html = items.map((k)=>`<div class="admin-item"><div class="admin-item-info"><strong>${escHtml(k.nama)}</strong><span>${escHtml(k.jabatan)} · +${escHtml(k.hp)}</span></div><div class="admin-item-actions"><button class="edit-btn" data-action="open-edit" data-type="kontak" data-id="${k.id}">Edit</button><button class="delete-btn" data-action="delete-item" data-endpoint="/kontak/${k.id}" data-listtype="kontak-admin">Hapus</button></div></div>`).join("") || noData();
    } else if (type === "jemaat-admin") {
      const sorted = items.sort((a,b)=>a.nama.localeCompare(b.nama));
      html = sorted.map((j)=>`<div class="admin-item"><div class="admin-item-info"><strong>${escHtml(j.nama)}${isBirthdayToday(j.ttl)?' <span class="bday-indicator">HUT</span>':""}</strong><span>${escHtml(j.kategori)} · ${escHtml(j.hp)} · ${escHtml(j.baptis)}</span></div><div class="admin-item-actions"><button class="edit-btn" data-action="open-edit" data-type="jemaat" data-id="${j.id}">Edit</button><button class="delete-btn" data-action="delete-item" data-endpoint="/jemaat/${j.id}" data-listtype="jemaat-admin">Hapus</button></div></div>`).join("") || noData();
    }
    el.innerHTML = html;
  } catch { el.innerHTML = noData(); }
}

function noData() { return '<p style="color:var(--text-light);font-size:.875rem">Belum ada data.</p>'; }

async function deleteItem(endpoint, listType) {
  if (!confirm("Hapus data ini? Tindakan tidak dapat dibatalkan.")) return;
  try {
    await api.delete(endpoint);
    // Refresh list admin kalau listType ada di admin
    if (listType !== "jadwal-public") renderAdminList(listType);
    if (listType === "jemaat-admin") updateTotalJemaat();
    // Selalu refresh tampilan publik jadwal setelah hapus
    if (listType === "jadwal-admin" || listType === "jadwal-public") renderJadwal();
    showToast("Data berhasil dihapus.");
  } catch(err) { showToast("Gagal menghapus: " + err.message); }
}

// ============================================================
// BIRTHDAY
// ============================================================

function isBirthdayToday(ttl) {
  if (!ttl) return false;
  const today=new Date(), bday=new Date(ttl+"T00:00:00");
  return bday.getDate()===today.getDate()&&bday.getMonth()===today.getMonth();
}

async function checkBirthdays() {
  const banner=document.getElementById("birthday-banner");
  if (!banner) return;
  try {
    const items=await api.get("/jemaat");
    const todayBdays=items.filter((j)=>isBirthdayToday(j.ttl));
    if (!todayBdays.length){banner.style.display="none";return;}
    banner.style.display="block";
    banner.innerHTML=`<strong>Ulang Tahun Hari Ini!</strong> Jangan lupa doakan dan ucapkan selamat kepada:<div class="bday-names">${todayBdays.map((j)=>`<span class="bday-tag">${escHtml(j.nama)}</span>`).join("")}</div>`;
  } catch {}
}

// ============================================================
// UTILITIES
// ============================================================

function formatDate(dateStr) {
  if (!dateStr) return "";
  return new Date(dateStr+"T00:00:00").toLocaleDateString("id-ID",{day:"numeric",month:"long",year:"numeric"});
}

let _toastTimer=null;
function showToast(msg,duration=2500){
  const t=document.getElementById("shortcut-toast");
  if (!t) return;
  t.textContent=msg; t.classList.add("show");
  clearTimeout(_toastTimer);
  _toastTimer=setTimeout(()=>t.classList.remove("show"),duration);
}

// ============================================================
// EVENT DELEGATION
// ============================================================

document.addEventListener("click",(e)=>{
  const navbar=document.getElementById("navbar");
  if (navbar&&!navbar.contains(e.target)) closeMenu();

  const dropdownToggle=e.target.closest(".dropdown-toggle");
  if (dropdownToggle&&window.innerWidth<=768){e.preventDefault();dropdownToggle.closest(".dropdown").classList.toggle("open");return;}

  const modal=document.getElementById("edit-modal");
  if (e.target===modal){closeEditModal();return;}

  const el=e.target.closest("[data-action]");
  if (!el) return;
  e.preventDefault();

  switch(el.dataset.action){
    case "toggle-menu":      toggleMenu(); break;
    case "admin-login":      adminLogin(); break;
    case "admin-logout":     adminLogout(); break;
    case "admin-tab":        adminTab(el); break;
    case "add-renungan":     addRenungan(); break;
    case "add-sermon":       addSermon(); break;
    case "add-jadwal":       addJadwal(); break;
    case "add-pengumuman":   addPengumuman(); break;
    case "add-gembala":      addGembala(); break;
    case "add-kontak":       addKontak(); break;
    case "add-jemaat":       addJemaat(); break;
    case "save-visi-misi":   saveVisiMisi(); break;
    case "save-edit":        saveEdit(); break;
    case "export-csv":       exportJemaatCSV(); break;
    case "change-password":  changePassword(); break;
    case "close-edit-modal": closeEditModal(); break;
    case "open-edit":        openEditModal(el.dataset.type,Number(el.dataset.id)); break;
    case "delete-item":      deleteItem(el.dataset.endpoint,el.dataset.listtype); break;
    case "open-renungan":    openRenungan(el.dataset.id); break;
    case "open-sermon":      openSermon(el.dataset.id); break;
    case "open-pengumuman":  openPengumuman(el.dataset.id); break;
    case "goto-pengumuman":  window.location.href="/pengumuman"; break;
    case "close-detail":     closeDetail(el.dataset.target); break;
    case "trigger-upload":   document.getElementById(el.dataset.target)?.click(); break;
    case "clear-foto":       clearFoto(el.dataset.target); break;
    case "copy-nomor":{
      const btn=el;
      navigator.clipboard.writeText("+"+el.dataset.hp).then(()=>{const orig=btn.textContent;btn.textContent="Tersalin!";setTimeout(()=>{btn.textContent=orig;},2000);});
      break;
    }
  }
});

document.addEventListener("change",(e)=>{
  const el=e.target.closest("[data-action='handle-upload']");
  if (el) handleFotoUpload(el.dataset.prefix);
  if (e.target.id==="jemaat-filter") filterJemaat();
});

document.addEventListener("input",(e)=>{if(e.target.id==="jemaat-search")filterJemaat();});
document.addEventListener("keydown",(e)=>{if(e.key==="Enter"&&e.target.id==="admin-pass")adminLogin();});
window.addEventListener("scroll",()=>{const nb=document.getElementById("navbar");if(nb)nb.style.boxShadow=window.scrollY>30?"0 4px 24px rgba(0,0,0,0.2)":"none";});

// ============================================================
// INIT
// ============================================================

document.addEventListener("DOMContentLoaded",()=>{
  const logoCross=document.getElementById("logo-cross-btn");
  if (logoCross) logoCross.addEventListener("click",handleLogoClick);

  if (isAdminLoggedIn()){
    const loginEl=document.getElementById("admin-login"), panelEl=document.getElementById("admin-panel");
    if (loginEl) loginEl.style.display="none";
    if (panelEl){panelEl.style.display="block";renderAdminLists();loadVisiInput();checkBirthdays();setActiveAdminTab();}
  }

  renderHomeRenungan();
  renderHomeJadwal();
  renderHomePengumuman();
  renderRenungan();
  renderSermon();
  renderJadwal();
  renderPengumuman();
  renderGembala();
  renderKontakWA();
  updateJemaatGuard();
  loadVisiInput();

  if (typeof lucide!=="undefined") lucide.createIcons();
});