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

<style>
  .tag.is-aprobado { background:#e6f7ec; color:#1c7c3f; }
  .tag.is-pendiente { background:#fff6e0; color:#9c6d00; }
  .tag.is-rechazado { background:#fdeaea; color:#c0392b; }
  .tag.is-suspendido { background:#2c2c2c; color:#fff; }
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
          <a href="/admin/usuarios" class="is-active router-link-active has-icon">
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
            <li>Usuarios</li>
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
            <h1 class="title">Usuarios y verificación</h1>
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
        <p class="card-header-title"><span class="icon"><i class="mdi mdi-account-multiple default"></i></span>Usuarios registrados</p>
        <button class="button is-link is-small" style="margin:8px 12px 0 0;" onclick="document.getElementById('modal-crear-admin').classList.add('is-active')">
          <span class="icon"><i class="mdi mdi-shield-plus"></i></span>
          <span>Crear administrador</span>
        </button>
      </header>
      <div class="card-content">
        <div class="field mb-4">
          <div class="control has-icons-left">
            <input class="input" type="text" id="buscar-usuario" placeholder="Buscar por nombre o email...">
            <span class="icon is-small is-left"><i class="mdi mdi-magnify"></i></span>
          </div>
        </div>
        <table class="table is-fullwidth is-striped is-hoverable">
          <thead>
            <tr>
              <th></th><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Localidad</th><th>Modo</th>
              <th>Verificación</th><th>Estado</th><th class="is-actions-cell">Acciones</th>
            </tr>
          </thead>
          <tbody id="tbl-usuarios">
            @forelse ($usuarios as $u)
              <tr>
                <td class="is-image-cell"><div class="image"><img src="https://avatars.dicebear.com/v2/initials/{{ urlencode($u->nombre) }}.svg" class="is-rounded"></div></td>
                <td data-label="Nombre">
                  {{ $u->nombre }}
                  @if ($u->motivo_suspension)
                    <br><small class="has-text-danger">{{ $u->motivo_suspension }}</small>
                  @endif
                </td>
                <td data-label="Email">{{ $u->email }}</td>
                <td data-label="Teléfono">{{ $u->telefono ?? '—' }}</td>
                <td data-label="Localidad">{{ $u->localidad->nombre ?? '—' }}</td>
                <td data-label="Modo"><span class="tag {{ $u->modo_activo === 'empleador' ? 'is-link' : 'is-primary' }}">{{ $u->modo_activo ?? 'trabajador' }}</span></td>
                <td data-label="Verificación"><span class="tag is-{{ $u->verificacion_estado }}">{{ $u->verificacion_estado }}</span></td>
                <td data-label="Estado"><span class="tag {{ $u->estado === 'suspendido' ? 'is-suspendido' : ($u->estado === 'activo' ? 'is-success' : 'is-grey-dark') }}">{{ $u->estado }}</span></td>
                <td class="is-actions-cell">
                  <div class="buttons is-right">
                    @if ($u->documentosVerificacion->count())
                      <button
                        class="button is-small is-info"
                        title="Ver documentos"
                        onclick="verDocumentos({{ $u->id }}, this)"
                        data-docs='@json($u->documentosVerificacion)'
                      >
                        <span class="icon"><i class="mdi mdi-file-eye"></i></span>
                      </button>
                    @endif
                    @if ($u->estado === 'suspendido')
                      <button class="button is-small is-success" title="Reactivar" onclick="reactivarUsuario({{ $u->id }})">
                        <span class="icon"><i class="mdi mdi-lock-open"></i></span>
                      </button>
                    @else
                      <button class="button is-small is-dark" title="Suspender cuenta" onclick="abrirSuspension({{ $u->id }}, '{{ addslashes($u->nombre) }}')">
                        <span class="icon"><i class="mdi mdi-account-lock"></i></span>
                      </button>
                    @endif
                    @if ($u->rol === 'admin' && $u->id !== auth()->id())
                      <button class="button is-small is-warning" title="Quitar administrador" onclick="quitarAdmin({{ $u->id }}, '{{ addslashes($u->nombre) }}')">
                        <span class="icon"><i class="mdi mdi-shield-off"></i></span>
                      </button>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="has-text-centered has-text-grey">No hay usuarios registrados todavía.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div id="modal-verificacion" class="modal">
      <div class="modal-background" onclick="document.getElementById('modal-verificacion').classList.remove('is-active')"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">Documentos de <span id="modal-verif-nombre"></span></p>
          <button class="delete" aria-label="close" onclick="document.getElementById('modal-verificacion').classList.remove('is-active')"></button>
        </header>
        <section class="modal-card-body">
          <input type="hidden" id="modal-verif-usuario-id">
          <div class="columns" id="modal-verif-docs"></div>
        </section>
        <footer class="modal-card-foot">
          <button class="button is-success" onclick="resolverVerificacion('aprobado')"><span class="icon"><i class="mdi mdi-check"></i></span><span>Aprobar</span></button>
          <button class="button is-danger" onclick="resolverVerificacion('rechazado')"><span class="icon"><i class="mdi mdi-close"></i></span><span>Rechazar</span></button>
          <button class="button" onclick="document.getElementById('modal-verificacion').classList.remove('is-active')">Cancelar</button>
        </footer>
      </div>
    </div>

    <div id="modal-crear-admin" class="modal">
      <div class="modal-background" onclick="document.getElementById('modal-crear-admin').classList.remove('is-active')"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title"><i class="mdi mdi-shield-plus"></i>&nbsp; Crear administrador</p>
          <button class="delete" aria-label="close" onclick="document.getElementById('modal-crear-admin').classList.remove('is-active')"></button>
        </header>
        <section class="modal-card-body">
          <p class="mb-4 has-text-grey">Esto crea una cuenta <strong>nueva y separada</strong>, solo para administración — no toca ninguna cuenta existente.</p>
          <div class="field">
            <label class="label">Nombre completo</label>
            <div class="control">
              <input class="input" type="text" id="admin-nombre" placeholder="Nombre del administrador">
            </div>
          </div>
          <div class="field">
            <label class="label">Correo electrónico</label>
            <div class="control">
              <input class="input" type="email" id="admin-email" placeholder="admin@ejemplo.com">
            </div>
          </div>
          <div class="field">
            <label class="label">Contraseña</label>
            <div class="control">
              <input class="input" type="password" id="admin-password" placeholder="Mínimo 8 caracteres">
            </div>
          </div>
          <div class="field">
            <label class="label">Confirmar contraseña</label>
            <div class="control">
              <input class="input" type="password" id="admin-password-confirm" placeholder="Repite la contraseña">
            </div>
          </div>
        </section>
        <footer class="modal-card-foot">
          <button class="button is-link" onclick="crearAdmin()"><i class="mdi mdi-content-save"></i>&nbsp;Crear cuenta</button>
          <button class="button" onclick="document.getElementById('modal-crear-admin').classList.remove('is-active')">Cancelar</button>
        </footer>
      </div>
    </div>

    <div id="modal-suspender" class="modal">
      <div class="modal-background" onclick="document.getElementById('modal-suspender').classList.remove('is-active')"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">Suspender cuenta de <span id="modal-susp-nombre"></span></p>
          <button class="delete" aria-label="close" onclick="document.getElementById('modal-suspender').classList.remove('is-active')"></button>
        </header>
        <section class="modal-card-body">
          <input type="hidden" id="modal-susp-usuario-id">
          <div class="field">
            <label class="label">Motivo de la suspensión</label>
            <div class="control">
              <textarea class="textarea" id="modal-susp-motivo" placeholder="Ej. reportes por fraude, contenido falso, etc."></textarea>
            </div>
          </div>
        </section>
        <footer class="modal-card-foot">
          <button class="button is-danger" onclick="confirmarSuspension()"><span class="icon"><i class="mdi mdi-account-lock"></i></span><span>Suspender</span></button>
          <button class="button" onclick="document.getElementById('modal-suspender').classList.remove('is-active')">Cancelar</button>
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
  // ===== Ver documentos de verificación =====
  function verDocumentos(usuarioId, btn) {
    const docs = JSON.parse(btn.dataset.docs || '[]');
    const nombre = btn.closest('tr').querySelector('[data-label="Nombre"]').childNodes[0].textContent.trim();
    document.getElementById('modal-verif-nombre').textContent = nombre;
    document.getElementById('modal-verif-docs').innerHTML = docs.map(d => `
      <div class="column is-half has-text-centered">
        <p class="label" style="text-transform:capitalize">${d.tipo_documento}</p>
        <img src="${d.archivo}" style="max-width:100%; border-radius:6px; border:1px solid #eee;">
      </div>`).join('');
    document.getElementById('modal-verif-usuario-id').value = usuarioId;
    document.getElementById('modal-verificacion').classList.add('is-active');
  }

  function resolverVerificacion(nuevoEstado) {
    const id = document.getElementById('modal-verif-usuario-id').value;
    const confirmMsg = nuevoEstado === 'aprobado'
      ? '¿Aprobar la verificación de este usuario?'
      : '¿Rechazar la verificación de este usuario?';
    if (!confirm(confirmMsg)) return;

    fetch(`/admin/usuarios/${id}/verificacion`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
      },
      body: JSON.stringify({ estado: nuevoEstado }),
    })
      .then(res => { if (!res.ok) throw new Error(); return res.json(); })
      .then(() => {
        document.getElementById('modal-verificacion').classList.remove('is-active');
        location.reload();
      })
      .catch(() => alert('Ocurrió un error al guardar la verificación. Intenta de nuevo.'));
  }

  // ===== Suspender / reactivar cuenta (guarda de verdad en la base de datos) =====
  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

  function abrirSuspension(id, nombre) {
    document.getElementById('modal-susp-nombre').textContent = nombre;
    document.getElementById('modal-susp-usuario-id').value = id;
    document.getElementById('modal-susp-motivo').value = '';
    document.getElementById('modal-suspender').classList.add('is-active');
  }

  function confirmarSuspension() {
    const id = document.getElementById('modal-susp-usuario-id').value;
    const motivo = document.getElementById('modal-susp-motivo').value.trim();

    fetch(`/admin/usuarios/${id}/suspender`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
      },
      body: JSON.stringify({ motivo: motivo }),
    })
      .then(res => {
        if (!res.ok) throw new Error('No se pudo suspender');
        document.getElementById('modal-suspender').classList.remove('is-active');
        location.reload();
      })
      .catch(() => alert('Ocurrió un error al suspender la cuenta. Intenta de nuevo.'));
  }

  function reactivarUsuario(id) {
    if (!confirm('¿Reactivar esta cuenta?')) return;

    fetch(`/admin/usuarios/${id}/reactivar`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
      },
    })
      .then(res => {
        if (!res.ok) throw new Error('No se pudo reactivar');
        location.reload();
      })
      .catch(() => alert('Ocurrió un error al reactivar la cuenta. Intenta de nuevo.'));
  }

  function crearAdmin() {
    const nombre = document.getElementById('admin-nombre').value.trim();
    const email = document.getElementById('admin-email').value.trim();
    const password = document.getElementById('admin-password').value;
    const passwordConfirm = document.getElementById('admin-password-confirm').value;

    if (!nombre || !email || !password) {
      alert('Llena todos los campos.');
      return;
    }
    if (password.length < 8) {
      alert('La contraseña debe tener al menos 8 caracteres.');
      return;
    }
    if (password !== passwordConfirm) {
      alert('Las contraseñas no coinciden.');
      return;
    }

    fetch('/admin/usuarios/crear-admin', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
      body: JSON.stringify({
        nombre: nombre,
        email: email,
        password: password,
        password_confirmation: passwordConfirm,
      }),
    })
      .then(res => res.json().then(data => ({ ok: res.ok, data })))
      .then(({ ok, data }) => {
        if (!ok) {
          let msg = data.message || 'Ocurrió un error al crear la cuenta.';
          if (data.errors) msg = Object.values(data.errors).flat().join(' ');
          alert('❌ ' + msg);
          return;
        }
        alert('✅ Administrador creado correctamente.');
        location.reload();
      })
      .catch(() => alert('❌ Ocurrió un error de conexión.'));
  }

  function quitarAdmin(id, nombre) {
    if (!confirm(`¿Quitarle el rol de administrador a ${nombre}? Va a volver a ser un usuario normal.`)) return;

    fetch(`/admin/usuarios/${id}/quitar-admin`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
    })
      .then(res => res.json().then(data => ({ ok: res.ok, data })))
      .then(({ ok, data }) => {
        if (!ok) { alert('❌ ' + (data.error || 'Ocurrió un error.')); return; }
        location.reload();
      })
      .catch(() => alert('Ocurrió un error al quitar el rol de administrador. Intenta de nuevo.'));
  }

  // ===== Buscador (filtra las filas ya renderizadas, sin recargar la página) =====
  document.getElementById('buscar-usuario').addEventListener('input', function (e) {
    const f = e.target.value.toLowerCase();
    document.querySelectorAll('#tbl-usuarios tr').forEach(function (tr) {
      const nombreCell = tr.querySelector('[data-label="Nombre"]');
      const emailCell = tr.querySelector('[data-label="Email"]');
      if (!nombreCell || !emailCell) return; // fila de "no hay usuarios"
      const coincide = nombreCell.textContent.toLowerCase().includes(f) || emailCell.textContent.toLowerCase().includes(f);
      tr.style.display = coincide ? '' : 'none';
    });
  });
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
