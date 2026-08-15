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
          <a href="/admin/vacantes" class="is-active router-link-active has-icon">
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
          <a href="/admin/anuncios" class="has-icon">
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
            <li>Vacantes</li>
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
            <h1 class="title">Vacantes</h1>
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

    <div class="card has-table">
      <header class="card-header">
        <p class="card-header-title"><span class="icon"><i class="mdi mdi-briefcase-search default"></i></span>Vacantes publicadas</p>
      </header>
      <div class="card-content">
        <div class="field is-grouped mb-4">
          <div class="control has-icons-left is-expanded">
            <input class="input" type="text" id="buscar-vacante" placeholder="Buscar por título o empleador...">
            <span class="icon is-small is-left"><i class="mdi mdi-magnify"></i></span>
          </div>
          <div class="control">
            <div class="select">
              <select id="filtro-estado-vacante">
                <option value="">Todos los estados</option>
                <option value="activa">Activa</option>
                <option value="cerrada">Cerrada</option>
                <option value="vencida">Vencida</option>
              </select>
            </div>
          </div>
        </div>
        <table class="table is-fullwidth is-striped is-hoverable">
          <thead>
            <tr><th>Título</th><th>Publicante</th><th>Ubicación</th><th>Salario</th><th>Fecha límite</th><th>Estado</th><th class="is-actions-cell">Acciones</th></tr>
          </thead>
          <tbody id="tbl-vacantes">
            @forelse ($vacantes as $v)
              <tr data-estado="{{ $v->estado }}">
                <td data-label="Título">{{ $v->titulo }}</td>
                <td data-label="Publicante">{{ $v->publicante }}</td>
                <td data-label="Ubicación">{{ $v->ubicacion }}</td>
                <td data-label="Salario">{{ $v->salario }}</td>
                <td data-label="Fecha límite"><small class="has-text-grey">{{ $v->fecha_limite ? $v->fecha_limite->format('d/m/Y') : '—' }}</small></td>
                <td data-label="Estado">
                  <span class="tag {{ $v->estado === 'activa' ? 'is-success' : ($v->estado === 'vencida' ? 'is-danger' : 'is-grey-dark') }}">{{ $v->estado }}</span>
                </td>
                <td class="is-actions-cell">
                  <div class="buttons is-right">
                    <button
                      class="button is-small is-primary"
                      title="Ver detalle"
                      onclick="verVacante(this)"
                      data-vacante='@json($v)'
                      data-fecha-limite="{{ $v->fecha_limite ? $v->fecha_limite->format('d/m/Y') : '—' }}"
                      data-postulantes="{{ $v->postulaciones_count }}"
                    >
                      <span class="icon"><i class="mdi mdi-eye"></i></span>
                    </button>
                    @if ($v->estado === 'activa')
                      <button class="button is-small is-warning" title="Cerrar vacante" onclick="cerrarVacante({{ $v->id }}, this)">
                        <span class="icon"><i class="mdi mdi-lock"></i></span>
                      </button>
                    @else
                      <button class="button is-small is-success" title="Reactivar" onclick="reactivarVacante({{ $v->id }}, this)">
                        <span class="icon"><i class="mdi mdi-lock-open"></i></span>
                      </button>
                    @endif
                    <button class="button is-small is-danger" title="Eliminar" onclick="eliminarVacante({{ $v->id }}, this)">
                      <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="has-text-centered has-text-grey">No hay vacantes publicadas todavía.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div id="modal-vacante" class="modal">
      <div class="modal-background" onclick="document.getElementById('modal-vacante').classList.remove('is-active')"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">Detalle de la vacante</p>
          <button class="delete" aria-label="close" onclick="document.getElementById('modal-vacante').classList.remove('is-active')"></button>
        </header>
        <section class="modal-card-body" id="modal-vacante-body"></section>
        <footer class="modal-card-foot">
          <button class="button" onclick="document.getElementById('modal-vacante').classList.remove('is-active')">Cerrar</button>
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
  // ===== Ver detalle (usa los datos ya renderizados en el botón, sin mock) =====
  function verVacante(btn) {
    const v = JSON.parse(btn.dataset.vacante);
    document.getElementById('modal-vacante-body').innerHTML = `
      <p><b>Título:</b> ${v.titulo}</p>
      <p><b>Publicante:</b> ${v.publicante}</p>
      <p><b>Ubicación:</b> ${v.ubicacion}</p>
      <p><b>Trabajadores requeridos:</b> ${v.trabajadores_requeridos}</p>
      <p><b>Tipo de pago:</b> ${v.tipo_pago}</p>
      <p><b>Salario:</b> ${v.salario}</p>
      <p><b>Experiencia:</b> ${v.experiencia}</p>
      <p><b>Contrato:</b> ${v.contrato}</p>
      <p><b>Fecha límite:</b> ${btn.dataset.fechaLimite}</p>
      <p><b>Postulaciones recibidas:</b> ${btn.dataset.postulantes}</p>`;
    document.getElementById('modal-vacante').classList.add('is-active');
  }

  // ===== Acciones (guardan de verdad en la base de datos) =====
  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

  function cerrarVacante(id, btn) {
    if (!confirm('¿Cerrar esta vacante? Ya no aparecerá disponible para postularse.')) return;
    fetch(`/admin/vacantes/${id}/cerrar`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
    })
      .then(res => {
        if (!res.ok) throw new Error();
        location.reload();
      })
      .catch(() => alert('Ocurrió un error al cerrar la vacante. Intenta de nuevo.'));
  }

  function reactivarVacante(id, btn) {
    fetch(`/admin/vacantes/${id}/reactivar`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
    })
      .then(res => {
        if (!res.ok) throw new Error();
        location.reload();
      })
      .catch(() => alert('Ocurrió un error al reactivar la vacante. Intenta de nuevo.'));
  }

  function eliminarVacante(id, btn) {
    if (!confirm('¿Eliminar esta vacante? Esta acción no se puede deshacer.')) return;
    fetch(`/admin/vacantes/${id}`, {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
    })
      .then(res => {
        if (!res.ok) throw new Error();
        location.reload();
      })
      .catch(() => alert('Ocurrió un error al eliminar la vacante. Intenta de nuevo.'));
  }

  // ===== Buscador + filtro de estado (filtran las filas ya renderizadas) =====
  function filtrarVacantes() {
    const f = document.getElementById('buscar-vacante').value.toLowerCase();
    const estadoF = document.getElementById('filtro-estado-vacante').value;
    document.querySelectorAll('#tbl-vacantes tr').forEach(function (tr) {
      const tituloCell = tr.querySelector('[data-label="Título"]');
      const publicanteCell = tr.querySelector('[data-label="Publicante"]');
      if (!tituloCell || !publicanteCell) return; // fila de "no hay vacantes"
      const texto = (tituloCell.textContent + ' ' + publicanteCell.textContent).toLowerCase();
      const coincideTexto = texto.includes(f);
      const coincideEstado = !estadoF || tr.dataset.estado === estadoF;
      tr.style.display = (coincideTexto && coincideEstado) ? '' : 'none';
    });
  }
  document.getElementById('buscar-vacante').addEventListener('input', filtrarVacantes);
  document.getElementById('filtro-estado-vacante').addEventListener('change', filtrarVacantes);
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
