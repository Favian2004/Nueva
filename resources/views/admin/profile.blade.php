<!DOCTYPE html>
<html lang="es" class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Mi perfil · Admin · Empleabilidad Zacapoaxtla</title>

  <link rel="stylesheet" href="{{ asset('assets/admin/css/main.min.css') }}">

  <link rel="dns-prefetch" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
  <style>
    .aside-tools-label b { font-weight: 700; }
    .is-image-cell .image img { object-fit: cover; }
    .anuncio-thumb { width: 160px; height: 90px; object-fit: cover; border-radius: 6px; }
    .badge-dot { display:inline-block; width:8px; height:8px; border-radius:50%; margin-right:6px; }

    /* ===== Perfil admin: encabezado ===== */
    .profile-hero {
      background: linear-gradient(120deg, #6b1021 0%, #b12d25 100%);
      border-radius: 18px;
      padding: 2.2rem 2rem;
      color: #fff;
      margin-bottom: 1.75rem;
      display: flex;
      align-items: center;
      gap: 1.75rem;
      flex-wrap: wrap;
      box-shadow: 0 10px 30px rgba(107, 16, 33, 0.25);
    }
    .profile-hero .avatar-wrap {
      position: relative;
      width: 110px;
      height: 110px;
      flex-shrink: 0;
    }
    .profile-hero .avatar-wrap img {
      width: 100%;
      height: 100%;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid rgba(255,255,255,0.6);
      background: #fff;
    }
    .profile-hero .avatar-edit {
      position: absolute;
      right: -2px;
      bottom: -2px;
      background: #ff7a18;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      border: 3px solid #6b1021;
      transition: .2s;
    }
    .profile-hero .avatar-edit:hover { background: #ffb347; }
    .profile-hero .avatar-edit i { color: #fff; font-size: 15px; }
    .profile-hero h1 { font-size: 1.7rem; font-weight: 800; margin-bottom: 0.35rem; }
    .profile-hero .profile-sub { opacity: 0.9; font-size: 0.95rem; margin-bottom: 0.75rem; }
    .profile-hero .tags { margin-bottom: 0; }
    .profile-hero .tag.is-admin-role { background:#ff7a18; color:#fff; font-weight:700; }
    .profile-hero .tag.is-estado-activo { background:#25D366; color:#fff; }
    .profile-hero .tag.is-verificado { background:#2563eb; color:#fff; }

    .profile-section-title {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-weight: 700;
      font-size: 1.05rem;
      color: #6b1021;
    }
    .profile-section-title .icon { color: #ff7a18; }

    .field-readonly-hint {
      font-size: 0.78rem;
      color: #9a9a9a;
    }

    .btn-guardar-perfil {
      background: linear-gradient(45deg, #ff7a18, #ffb347) !important;
      border: none !important;
      color: #fff !important;
      font-weight: 700;
      border-radius: 10px !important;
    }

    .toast-perfil {
      position: fixed;
      top: 80px;
      right: 20px;
      z-index: 999;
      padding: 12px 20px;
      border-radius: 10px;
      font-weight: 600;
      font-size: 14px;
      box-shadow: 0 6px 20px rgba(0,0,0,.15);
      display: none;
    }
    .toast-perfil.exito { background: #dcfce7; color: #16a34a; }
    .toast-perfil.error { background: #fdeaea; color: #c0392b; }
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
              <img src="{{ $admin->foto_perfil ?? 'https://avatars.dicebear.com/v2/initials/' . urlencode($admin->nombre) . '.svg' }}" alt="Admin">
            </div>
            <div class="is-user-name"><span>{{ $admin->nombre }}</span></div>
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
            <li>Mi perfil</li>
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
            <h1 class="title">Mi perfil</h1>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section is-main-section">

    <div id="toastExito" class="toast-perfil exito"><i class="mdi mdi-check-circle"></i> <span id="toastExitoTexto">Cambios guardados</span></div>
    <div id="toastError" class="toast-perfil error"><i class="mdi mdi-alert-circle"></i> <span id="toastErrorTexto">Ocurrió un error</span></div>

    <!-- ===== ENCABEZADO DE PERFIL ===== -->
    <div class="profile-hero">
      <div class="avatar-wrap">
        <img id="fotoPreview" src="{{ $admin->foto_perfil ?? 'https://avatars.dicebear.com/v2/initials/' . urlencode($admin->nombre) . '.svg' }}" alt="Admin">
        <label class="avatar-edit">
          <i class="mdi mdi-camera"></i>
          <input type="file" id="fotoInput" accept="image/*" style="display:none">
        </label>
      </div>
      <div>
        <h1 id="heroNombre">{{ $admin->nombre }}</h1>
        <p class="profile-sub" id="heroSub">{{ $admin->email }} · {{ $admin->localidad->nombre ?? 'Zacapoaxtla' }}</p>
        <div class="tags">
          <span class="tag is-admin-role"><i class="mdi mdi-shield-account" style="margin-right:4px;"></i> Administrador</span>
          <span class="tag is-estado-activo">Cuenta {{ $admin->estado }}</span>
          <span class="tag is-verificado">Verificación {{ $admin->verificacion_estado }}</span>
        </div>
      </div>
    </div>

    <div class="tile is-ancestor">
      <div class="tile is-parent">
        <div class="card tile is-child">
          <header class="card-header">
            <p class="card-header-title profile-section-title">
              <span class="icon"><i class="mdi mdi-account-edit default"></i></span>
              Información personal
            </p>
          </header>
          <div class="card-content">
            <form id="formPerfilAdmin" enctype="multipart/form-data">
              <div class="field is-horizontal">
                <div class="field-label is-normal"><label class="label">Foto de perfil</label></div>
                <div class="field-body">
                  <div class="field">
                    <div class="field file">
                      <label class="upload control">
                        <a class="button is-primary">
                          <span class="icon"><i class="mdi mdi-upload default"></i></span>
                          <span>Cambiar foto</span>
                        </a>
                        <input type="file" id="fotoInput2" accept="image/*">
                      </label>
                    </div>
                    <p class="help">JPG o PNG. Se guarda en <code>usuarios.foto_perfil</code>.</p>
                  </div>
                </div>
              </div>
              <hr>
              <div class="field is-horizontal">
                <div class="field-label is-normal"><label class="label">Nombre</label></div>
                <div class="field-body">
                  <div class="field">
                    <div class="control">
                      <input type="text" autocomplete="on" name="nombre" id="inputNombre" value="{{ $admin->nombre }}" class="input" required>
                    </div>
                    <p class="help">Obligatorio. Tu nombre completo.</p>
                  </div>
                </div>
              </div>
              <div class="field is-horizontal">
                <div class="field-label is-normal"><label class="label">Correo</label></div>
                <div class="field-body">
                  <div class="field">
                    <div class="control has-icons-left">
                      <input type="email" autocomplete="on" name="email" id="inputEmail" value="{{ $admin->email }}" class="input" required>
                      <span class="icon is-small is-left"><i class="mdi mdi-email"></i></span>
                    </div>
                    <p class="help">Obligatorio. No puede repetirse con otro usuario.</p>
                  </div>
                </div>
              </div>
              <div class="field is-horizontal">
                <div class="field-label is-normal"><label class="label">Teléfono</label></div>
                <div class="field-body">
                  <div class="field">
                    <div class="control has-icons-left">
                      <input type="tel" name="telefono" value="{{ $admin->telefono }}" placeholder="Ej. 222 123 4567" class="input">
                      <span class="icon is-small is-left"><i class="mdi mdi-phone"></i></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="field is-horizontal">
                <div class="field-label is-normal"><label class="label">WhatsApp</label></div>
                <div class="field-body">
                  <div class="field">
                    <div class="control has-icons-left">
                      <input type="tel" name="whatsapp" value="{{ $admin->whatsapp }}" placeholder="Ej. 222 123 4567" class="input">
                      <span class="icon is-small is-left"><i class="mdi mdi-whatsapp"></i></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="field is-horizontal">
                <div class="field-label is-normal"><label class="label">Localidad</label></div>
                <div class="field-body">
                  <div class="field">
                    <div class="control">
                      <input type="text" class="input" value="{{ $admin->localidad->nombre ?? '—' }}" disabled>
                    </div>
                    <p class="help">Para cambiar tu localidad, contacta a soporte por ahora.</p>
                  </div>
                </div>
              </div>
              <div class="field is-horizontal">
                <div class="field-label is-normal"><label class="label">Descripción</label></div>
                <div class="field-body">
                  <div class="field">
                    <div class="control">
                      <textarea class="textarea" name="descripcion" rows="3" placeholder="Cuéntanos brevemente sobre ti (opcional)">{{ $admin->descripcion }}</textarea>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <div class="field is-horizontal">
                <div class="field-label is-normal"></div>
                <div class="field-body">
                  <div class="field">
                    <div class="control">
                      <button type="submit" class="button btn-guardar-perfil">
                        <span class="icon"><i class="mdi mdi-content-save"></i></span>
                        <span>Guardar cambios</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="tile is-parent" style="max-width: 340px;">
        <div class="card tile is-child">
          <header class="card-header">
            <p class="card-header-title profile-section-title">
              <span class="icon"><i class="mdi mdi-card-account-details default"></i></span>
              Estado de la cuenta
            </p>
          </header>
          <div class="card-content">
            <div class="field">
              <label class="label">Rol</label>
              <span class="tag is-admin-role is-medium"><i class="mdi mdi-shield-account" style="margin-right:4px;"></i> Administrador</span>
            </div>
            <hr>
            <div class="field">
              <label class="label">Estado de cuenta</label>
              <span class="tag is-estado-activo is-medium">{{ ucfirst($admin->estado) }}</span>
            </div>
            <hr>
            <div class="field">
              <label class="label">Verificación</label>
              <span class="tag is-verificado is-medium">{{ ucfirst($admin->verificacion_estado) }}</span>
            </div>
            <hr>
            <div class="field">
              <label class="label">Miembro desde</label>
              <p class="field-readonly-hint">{{ $admin->created_at->format('F Y') }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <header class="card-header">
        <p class="card-header-title profile-section-title">
          <span class="icon"><i class="mdi mdi-lock default"></i></span>
          {{ $admin->password ? 'Cambiar contraseña' : 'Crear una contraseña' }}
        </p>
      </header>
      <div class="card-content">
        @if (!$admin->password)
          <div class="notification is-info is-light">
            <i class="mdi mdi-information"></i> Todavía no tienes contraseña. Crea una aquí para poder iniciar sesión con tu correo y contraseña.
          </div>
        @endif
        <form id="formPasswordAdmin">
          @if ($admin->password)
            <div class="field is-horizontal">
              <div class="field-label is-normal">
                <label class="label">Contraseña actual</label>
              </div>
              <div class="field-body">
                <div class="field">
                  <div class="control">
                    <input type="password" name="password_actual" id="passActualAdmin" autocomplete="current-password" class="input" required>
                  </div>
                  <p class="help">Obligatorio. Tu contraseña actual.</p>
                </div>
              </div>
            </div>
            <hr>
          @endif
          <div class="field is-horizontal">
            <div class="field-label is-normal">
              <label class="label">Nueva contraseña</label>
            </div>
            <div class="field-body">
              <div class="field">
                <div class="control">
                  <input type="password" autocomplete="new-password" id="passNuevaAdmin" class="input" required minlength="8">
                </div>
                <p class="help">Obligatorio. Mínimo 8 caracteres.</p>
              </div>
            </div>
          </div>
          <div class="field is-horizontal">
            <div class="field-label is-normal">
              <label class="label">Confirmar contraseña</label>
            </div>
            <div class="field-body">
              <div class="field">
                <div class="control">
                  <input type="password" autocomplete="new-password" id="passConfirmAdmin" class="input" required minlength="8">
                </div>
                <p class="help">Obligatorio. Repite la nueva contraseña.</p>
              </div>
            </div>
          </div>
          <hr>
          <div class="field is-horizontal">
            <div class="field-label is-normal"></div>
            <div class="field-body">
              <div class="field">
                <div class="control">
                  <button type="submit" class="button btn-guardar-perfil">
                    <span class="icon"><i class="mdi mdi-lock-check"></i></span>
                    <span>{{ $admin->password ? 'Actualizar contraseña' : 'Crear contraseña' }}</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </form>
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
  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
  let fotoPendiente = null;

  function mostrarToast(exito, texto) {
    const id = exito ? 'toastExito' : 'toastError';
    const textoId = exito ? 'toastExitoTexto' : 'toastErrorTexto';
    document.getElementById(textoId).textContent = texto;
    document.getElementById(id).style.display = 'block';
    setTimeout(() => { document.getElementById(id).style.display = 'none'; }, 3500);
  }

  function manejarFoto(file) {
    if (!file) return;
    fotoPendiente = file;
    const reader = new FileReader();
    reader.onload = e => { document.getElementById('fotoPreview').src = e.target.result; };
    reader.readAsDataURL(file);
  }

  document.getElementById('fotoInput').addEventListener('change', e => manejarFoto(e.target.files[0]));
  document.getElementById('fotoInput2').addEventListener('change', e => manejarFoto(e.target.files[0]));

  // ===== Guardar información personal =====
  document.getElementById('formPerfilAdmin').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    if (fotoPendiente) formData.append('foto', fotoPendiente);
    formData.append('_method', 'PATCH');

    fetch('/admin/profile', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
      body: formData,
    })
      .then(res => res.json().then(data => ({ ok: res.ok, data })))
      .then(({ ok, data }) => {
        if (!ok) {
          let msg = data.message || 'Ocurrió un error al guardar.';
          if (data.errors) msg = Object.values(data.errors).flat().join(' ');
          mostrarToast(false, msg);
          return;
        }
        mostrarToast(true, 'Perfil actualizado correctamente');
        document.getElementById('heroNombre').textContent = data.admin.nombre;
        document.getElementById('heroSub').textContent = data.admin.email + ' · {{ $admin->localidad->nombre ?? "Zacapoaxtla" }}';
        document.querySelector('.is-user-name span').textContent = data.admin.nombre;
      })
      .catch(() => mostrarToast(false, 'Ocurrió un error de conexión.'));
  });

  // ===== Cambiar/crear contraseña =====
  document.getElementById('formPasswordAdmin').addEventListener('submit', function (e) {
    e.preventDefault();

    const passActualEl = document.getElementById('passActualAdmin');
    const body = {
      password_nueva: document.getElementById('passNuevaAdmin').value,
      password_nueva_confirmation: document.getElementById('passConfirmAdmin').value,
    };
    if (passActualEl) body.password_actual = passActualEl.value;

    fetch('/admin/profile/password', {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
      body: JSON.stringify(body),
    })
      .then(res => res.json().then(data => ({ ok: res.ok, data })))
      .then(({ ok, data }) => {
        if (!ok) {
          let msg = data.message || 'Ocurrió un error al actualizar la contraseña.';
          if (data.errors) msg = Object.values(data.errors).flat().join(' ');
          mostrarToast(false, msg);
          return;
        }
        mostrarToast(true, 'Contraseña actualizada correctamente');
        this.reset();
      })
      .catch(() => mostrarToast(false, 'Ocurrió un error de conexión.'));
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
        const csrfTokenLogout = csrfMeta ? csrfMeta.content : '';
        fetch('/logout', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfTokenLogout, 'Accept': 'application/json' },
        }).finally(() => { window.location.href = '/'; });
      });
    });
  })();
</script>
</body>
</html>
