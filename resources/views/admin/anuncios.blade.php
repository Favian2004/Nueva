<!DOCTYPE html>
<html lang="es" class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin · Empleabilidad Zacapoaxtla</title>

  <link rel="stylesheet" href="{{ asset('assets/admin/css/main.min.css') }}">

  <link rel="dns-prefetch" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
  <style>
    .aside-tools-label b { font-weight: 700; }
    .is-image-cell .image img { object-fit: cover; }
    .anuncio-thumb { width: 160px; height: 90px; object-fit: cover; border-radius: 6px; }
    .badge-dot { display:inline-block; width:8px; height:8px; border-radius:50%; margin-right:6px; }
  </style>

  <link rel="stylesheet" href="{{ asset('assets/admin/css/theme-conectaya-admin.css') }}">
</head>
<body>

<div id="app">
  <nav id="navbar-main" class="navbar is-fixed-top">
    <div class="navbar-brand">
      <a class="navbar-item is-hidden-desktop jb-aside-mobile-toggle">
        <span class="icon"><i class="mdi mdi-forwardburger mdi-24px"></i></span>
      </a>
    </div>
    <div class="navbar-brand is-right">
      <a class="navbar-item is-hidden-desktop jb-navbar-menu-toggle" data-target="navbar-menu">
        <span class="icon"><i class="mdi mdi-dots-vertical"></i></span>
      </a>
    </div>
    <div class="navbar-menu fadeIn animated faster" id="navbar-menu">
      <div class="navbar-end">
        <div class="navbar-item has-dropdown has-dropdown-with-icons has-divider has-user-avatar is-hoverable">
          <a class="navbar-link is-arrowless">
            <div class="is-user-avatar">
              <img src="https://avatars.dicebear.com/v2/initials/admin.svg" alt="Admin">
            </div>
            <div class="is-user-name"><span>Admin</span></div>
            <span class="icon"><i class="mdi mdi-chevron-down"></i></span>
          </a>
          <div class="navbar-dropdown">
            <a href="/admin/profile" class="navbar-item">
              <span class="icon"><i class="mdi mdi-account"></i></span>
              <span>Mi perfil</span>
            </a>
            <hr class="navbar-divider">
            <a href="#" class="navbar-item js-logout">
              <span class="icon"><i class="mdi mdi-logout"></i></span>
              <span>Cerrar sesión</span>
            </a>
          </div>
        </div>
        <a title="Cerrar sesión" href="#" class="navbar-item is-desktop-icon-only js-logout">
          <span class="icon"><i class="mdi mdi-logout"></i></span>
          <span>Cerrar sesión</span>
        </a>
      </div>
    </div>
  </nav>
  <aside class="aside is-placed-left is-expanded">
    <div class="aside-tools">
      <div class="aside-tools-label">
        <span><b>Empleabilidad</b> Zacapoaxtla</span>
      </div>
    </div>
    <div class="menu is-menu-main">
      <p class="menu-label">Panel</p>
      <ul class="menu-list">
        <li>
          <a href="/admin" class="has-icon">
            <span class="icon"><i class="mdi mdi-desktop-mac"></i></span>
            <span class="menu-item-label">Dashboard</span>
          </a>
        </li>
        <li>
          <a href="/admin/usuarios" class="has-icon">
            <span class="icon"><i class="mdi mdi-account-multiple"></i></span>
            <span class="menu-item-label">Usuarios</span>
          </a>
        </li>
        <li>
          <a href="/admin/vacantes" class="has-icon">
            <span class="icon"><i class="mdi mdi-briefcase-search"></i></span>
            <span class="menu-item-label">Vacantes</span>
          </a>
        </li>
        <li>
          <a href="/admin/postulaciones" class="has-icon">
            <span class="icon"><i class="mdi mdi-account-arrow-right"></i></span>
            <span class="menu-item-label">Postulaciones</span>
          </a>
        </li>
        <li>
          <a href="/admin/servicios" class="has-icon">
            <span class="icon"><i class="mdi mdi-hammer-wrench"></i></span>
            <span class="menu-item-label">Servicios</span>
          </a>
        </li>
        <li>
          <a href="/admin/contrataciones" class="has-icon">
            <span class="icon"><i class="mdi mdi-handshake"></i></span>
            <span class="menu-item-label">Contrataciones</span>
          </a>
        </li>
        <li>
          <a href="/admin/reportes" class="has-icon">
            <span class="icon"><i class="mdi mdi-alert-octagon"></i></span>
            <span class="menu-item-label">Reportes</span>
          </a>
        </li>
        <li>
          <a href="/admin/categorias" class="has-icon">
            <span class="icon"><i class="mdi mdi-shape"></i></span>
            <span class="menu-item-label">Categorías</span>
          </a>
        </li>
        <li>
          <a href="/admin/municipios" class="has-icon">
            <span class="icon"><i class="mdi mdi-map-marker"></i></span>
            <span class="menu-item-label">Municipios</span>
          </a>
        </li>
        <li>
          <a href="/admin/anuncios" class="is-active router-link-active has-icon">
            <span class="icon"><i class="mdi mdi-bullhorn"></i></span>
            <span class="menu-item-label">Anuncios</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>
  <section class="section is-title-bar">
    <div class="level">
      <div class="level-left">
        <div class="level-item">
          <ul>
            <li>Admin</li>
            <li>Anuncios</li>
          </ul>
        </div>
      </div>
    </div>
  </section>
  <section class="hero is-hero-bar">
    <div class="hero-body">
      <div class="level">
        <div class="level-left">
          <div class="level-item">
            <h1 class="title">Anuncios (negocios destacados)</h1>
          </div>
        </div>
        <div class="level-right">
          <div class="level-item">

          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="section is-main-section">

    <div class="notification is-info is-light">
      <span class="icon"><i class="mdi mdi-information"></i></span>
      Cada lado de la página principal tiene <b>3 espacios fijos</b>. Cada espacio puede mostrar
      de <b>2 a 5 imágenes en carrusel</b> (generadas con IA). Sube, ordena o quita imágenes de cada espacio aquí.
    </div>
    <div class="field mb-4">
      <div class="control">
        <div class="select">
          <select id="filtro-municipio-anuncio">
            @foreach ($municipios as $m)
              <option value="{{ $m->id }}">{{ $m->nombre }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
    <div class="columns">
      <div class="column is-6">
        <h3 class="title is-5"><span class="icon"><i class="mdi mdi-page-layout-sidebar-left"></i></span> Columna izquierda</h3>
        <div id="col-izquierda"></div>
      </div>
      <div class="column is-6">
        <h3 class="title is-5"><span class="icon"><i class="mdi mdi-page-layout-sidebar-right"></i></span> Columna derecha</h3>
        <div id="col-derecha"></div>
      </div>
    </div>

    <div id="modal-anuncio" class="modal">
      <div class="modal-background" onclick="cerrarModalAnuncio()"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">Espacio <span id="modal-anuncio-titulo"></span></p>
          <button class="delete" aria-label="close" onclick="cerrarModalAnuncio()"></button>
        </header>
        <section class="modal-card-body">
          <input type="hidden" id="modal-anuncio-id">
          <p class="help mb-3">Máximo 5 imágenes por espacio. Se muestran en el orden en que las agregues.</p>
          <div class="columns is-multiline" id="modal-anuncio-imgs"></div>
          <div class="field mt-4">
            <div class="field file is-fullwidth">
              <label class="upload control">
                <a class="button is-primary is-fullwidth" id="btn-agregar-imagen">
                  <span class="icon"><i class="mdi mdi-upload"></i></span>
                  <span>Agregar imagen al carrusel</span>
                </a>
                <input type="file" id="input-nueva-imagen" accept="image/*">
              </label>
            </div>
          </div>
        </section>
        <footer class="modal-card-foot">
          <button class="button" onclick="cerrarModalAnuncio()">Cerrar</button>
        </footer>
      </div>
    </div>

  </section>
  <footer class="footer">
    <div class="container-fluid">
      <div class="level">
        <div class="level-left">
          <div class="level-item">
            © 2026, Empleabilidad Zacapoaxtla
          </div>
        </div>
        <div class="level-right">
          <div class="level-item">
            <div class="logo">
              <a href="https://justboil.me"><img src="{{ asset('assets/admin/img/justboil-logo.svg') }}" alt="JustBoil.me" style="height:20px;"></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
</div>

<script type="text/javascript" src="{{ asset('assets/admin/js/main.min.js') }}"></script>
<script>
  // ===== Datos reales, mandados desde el Controller (evita el problema de comillas en atributos HTML) =====
  const ANUNCIOS = @json($anuncios);

  let nextImgId = 1; // solo se usa para las imágenes que agregues en esta sesión, antes de guardarlas de verdad

  const selMun = document.getElementById('filtro-municipio-anuncio');

  function getImagenesAnuncio(anuncioId) {
    const a = ANUNCIOS.find(x => x.id === anuncioId);
    return a ? a.imagenes : [];
  }

  function renderEspacio(a) {
    const imgs = a.imagenes.slice(0, 5);
    const thumbs = imgs.map(i => `<img src="${i.imagen}" class="anuncio-thumb" style="width:60px;height:40px;margin-right:4px;">`).join('') || '<span class="has-text-grey">Sin imágenes</span>';
    return `
      <div class="card mb-4">
        <div class="card-content">
          <div class="level is-mobile">
            <div class="level-left"><b>Espacio ${a.orden}</b></div>
            <div class="level-right"><span class="tag ${a.estado === 'activo' ? 'is-success' : 'is-grey-dark'}">${a.estado}</span></div>
          </div>
          <div class="mb-3">${thumbs}</div>
          <div class="buttons">
            <button class="button is-small is-info" onclick="abrirModalAnuncio(${a.id})"><span class="icon"><i class="mdi mdi-image-multiple"></i></span><span>Gestionar imágenes</span></button>
            <button class="button is-small ${a.estado === 'activo' ? 'is-warning' : 'is-success'}" onclick="toggleAnuncio(${a.id})">
              <span class="icon"><i class="mdi ${a.estado === 'activo' ? 'mdi-eye-off' : 'mdi-eye'}"></i></span>
            </button>
          </div>
        </div>
      </div>`;
  }

  function pintarAnuncios() {
    const munId = parseInt(selMun.value, 10);
    const izq = ANUNCIOS.filter(a => a.municipio_id === munId && a.posicion === 'izquierda').sort((a, b) => a.orden - b.orden);
    const der = ANUNCIOS.filter(a => a.municipio_id === munId && a.posicion === 'derecha').sort((a, b) => a.orden - b.orden);
    document.getElementById('col-izquierda').innerHTML = izq.map(renderEspacio).join('') || '<p class="has-text-grey">Sin espacios configurados.</p>';
    document.getElementById('col-derecha').innerHTML = der.map(renderEspacio).join('') || '<p class="has-text-grey">Sin espacios configurados.</p>';
  }

  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

  function toggleAnuncio(id) {
    fetch(`/admin/anuncios/${id}/toggle`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
    })
      .then(res => {
        if (!res.ok) throw new Error();
        location.reload();
      })
      .catch(() => alert('Ocurrió un error al actualizar el espacio. Intenta de nuevo.'));
  }

  let modalAnuncioId = null;
  function abrirModalAnuncio(id) {
    modalAnuncioId = id;
    const a = ANUNCIOS.find(x => x.id === id);
    document.getElementById('modal-anuncio-titulo').textContent = `${a.posicion} #${a.orden}`;
    document.getElementById('modal-anuncio-id').value = id;
    pintarImagenesModal();
    document.getElementById('modal-anuncio').classList.add('is-active');
  }
  function cerrarModalAnuncio() {
    document.getElementById('modal-anuncio').classList.remove('is-active');
    location.reload();
  }
  function pintarImagenesModal() {
    const imgs = getImagenesAnuncio(modalAnuncioId);
    document.getElementById('modal-anuncio-imgs').innerHTML = imgs.map((i, idx) => `
      <div class="column is-4 has-text-centered">
        <img src="${i.imagen}" style="width:100%; border-radius:6px; border:1px solid #eee;">
        <p class="mt-1"><small>Imagen ${idx + 1}</small></p>
        <button class="button is-small is-danger mt-1" onclick="quitarImagen(${i.id})"><span class="icon"><i class="mdi mdi-trash-can"></i></span></button>
      </div>`).join('');
    const btn = document.getElementById('btn-agregar-imagen');
    btn.classList.toggle('is-static', imgs.length >= 5);
    if (imgs.length >= 5) {
      btn.querySelector('span:last-child').textContent = 'Máximo de 5 imágenes alcanzado';
    }
  }
  function quitarImagen(imgId) {
    if (!confirm('¿Quitar esta imagen del carrusel?')) return;
    fetch(`/admin/anuncio-imagenes/${imgId}`, {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
    })
      .then(res => {
        if (!res.ok) throw new Error();
        // quita la imagen del arreglo local para refrescar el modal sin recargar toda la página
        const a = ANUNCIOS.find(x => x.id === modalAnuncioId);
        a.imagenes = a.imagenes.filter(i => i.id !== imgId);
        pintarImagenesModal();
      })
      .catch(() => alert('Ocurrió un error al quitar la imagen. Intenta de nuevo.'));
  }
  document.getElementById('input-nueva-imagen').addEventListener('change', function (e) {
    const imgs = getImagenesAnuncio(modalAnuncioId);
    if (imgs.length >= 5) { alert('Este espacio ya tiene el máximo de 5 imágenes.'); e.target.value = ''; return; }
    const file = e.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('imagen', file);

    fetch(`/admin/anuncios/${modalAnuncioId}/imagenes`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken },
      body: formData,
    })
      .then(res => res.json().then(data => ({ ok: res.ok, data })))
      .then(({ ok, data }) => {
        if (!ok) { alert(data.error || 'Ocurrió un error al subir la imagen.'); return; }
        const a = ANUNCIOS.find(x => x.id === modalAnuncioId);
        a.imagenes.push(data.imagen);
        pintarImagenesModal();
        e.target.value = '';
      })
      .catch(() => alert('Ocurrió un error al subir la imagen. Intenta de nuevo.'));
  });

  selMun.addEventListener('change', pintarAnuncios);
  pintarAnuncios();
</script>


  <script>
    (function () {
      const logoutBtns = document.querySelectorAll('.js-logout');
      if (!logoutBtns.length) return;
      logoutBtns.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
          e.preventDefault();
          if (!confirm('¿Seguro que quieres cerrar sesión?')) return;
          const csrfMeta = document.querySelector('meta[name="csrf-token"]');
          const csrfToken = csrfMeta ? csrfMeta.content : '';
          fetch('/logout', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
          }).finally(() => { window.location.href = '/'; });
        });
      });
    })();
  </script>
</body>
</html>
