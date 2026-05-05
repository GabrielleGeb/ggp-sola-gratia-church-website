<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GGP Sola Gratia — Gereja Gerakan Pentakosta</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

@include('components.navbar')

@yield('content')

<!-- EDIT MODAL -->
<div id="edit-modal" class="modal-overlay" onclick="handleModalOverlayClick(event)">
  <div class="modal-box">
    <div class="modal-header">
      <h3 id="edit-modal-title">Edit Data</h3>
      <button class="modal-close" onclick="closeEditModal()">
        <i data-lucide="x"></i>
      </button>
    </div>
    <div class="modal-body" id="edit-modal-body"></div>
    <div class="modal-footer">
      <button class="btn-outline-dark" onclick="closeEditModal()">Batal</button>
      <button class="btn-primary" onclick="saveEdit()">Simpan Perubahan</button>
    </div>
  </div>
</div>

<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<script src="{{ asset('js/script.js') }}"></script>

</body>
</html>