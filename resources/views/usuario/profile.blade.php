<!DOCTYPE html>
<!-- profile.html - Perfil profesional mejorado -->
<html lang="es" class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Mi Perfil · Dashboard de Servicios</title>

  <!-- CSS Base -->
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/main.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/switch.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="icon" type="img/" href="{{ asset('assets/usuario/img/icono.png') }}">

  <link rel="stylesheet" href="{{ asset('assets/usuario/css/ads-widget.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/theme-conectaya.css') }}">

  <style>
    /* ===== BANNER "¿TIENES UN NEGOCIO?" ===== */
    .banner-negocio {
      width: 100%;
      background: linear-gradient(90deg, #6b1021, #8f1d2f, #b12d25);
      border-radius: 12px;
      padding: 8px 25px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      color: white;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0, 0, 0, .15);
    }
    .banner-texto h2 {
      font-size: 24px;
      margin: 0;
    }
    .banner-texto p {
      margin: 0;
      font-size: 14px;
    }
    .banner-destaca h2 {
      margin: 0;
      font-size: 22px;
    }
    .banner-destaca h1 {
      margin: 0;
      font-size: 32px;
      color: #ffcf33;
      font-weight: bold;
    }
    .banner-icono img {
      width: 70px;
    }
    .btn-anunciar {
      background: #ffc107;
      color: #000;
      text-decoration: none;
      padding: 10px 18px;
      border-radius: 8px;
      font-weight: bold;
      display: inline-block;
    }
    .banner-boton small {
      display: block;
      text-align: center;
      margin-top: 3px;
      color: #fff;
    }
    .banner-persona {
      height: 90px;
    }
    .banner-persona img {
      height: 90px;
      width: auto;
      object-fit: cover;
      border-radius: 999px;
    }
  </style>
</head>
<body>
<div id="app">

  <!-- NAVBAR -->
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
        <div class="navbar-start">
          <div class="navbar-item breadcrumb-nav">
            <i class="mdi mdi-map-marker-outline"></i>
            <span class="crumb-section">Usuario</span>
            <i class="mdi mdi-chevron-right crumb-sep"></i>
            <span class="crumb-page">Inicio</span>
          </div>
        </div>
        <div class="navbar-end">
          <a title="Log out" href="#" id="logoutBtn" class="navbar-item is-desktop-icon-only">
            <span class="icon"><i class="mdi mdi-logout"></i></span>
            <span>Cerrar Sesión</span>
          </a>
        </div>
      </div>
    </nav>

  <!-- SIDEBAR (con el ítem "Perfil" activo) -->
  <aside class="aside is-placed-left is-expanded">
    <div class="aside-tools">
      <span><b> Perfil General</b></span>
    </div>
    <!-- ===== INTERRUPTOR AJUSTADO ===== -->
      <div class="sidebar-role-switch">
        <div class="switch-row" id="switchRow">
          <div class="active-bg worker-bg" id="activeBg"></div>
          <span class="role-text worker-text active" id="labelWorker">TRABAJADOR</span>
          <span class="role-text employer-text" id="labelEmployer">EMPLEADOR</span>
        </div>

        <!-- Tooltip -->
        <div class="role-tooltip-sidebar" id="roleTooltip">
          <div class="tooltip-title">
            <span id="tooltipRoleName">👷 <span class="highlight-worker">Modo Trabajador</span></span>
          </div>
          <div class="tooltip-desc" id="tooltipDesc">Publica y gestiona tus servicios</div>
        </div>
      </div>
    <div class="menu is-menu-main">
      <p class="menu-label">General</p>
      <ul class="menu-list">
        <li><a href="/usuario" class="has-icon"><span class="icon"><i class="mdi mdi-desktop-mac"></i></span><span class="menu-item-label">Inicio</span></a></li>
      </ul>
      <p class="menu-label">Otros</p>
      <ul class="menu-list">
        <li id="sidebarVerEmpleos"><a href="/usuario/verEmpleos" class="has-icon"><span class="icon"><i class="mdi mdi-briefcase"></i></span><span class="menu-item-label">Ver empleos</span></a></li>
        <li id="sidebarPublicar"><a href="/usuario/publicarEmpleo" class="has-icon"><span class="icon"><i class="mdi mdi-square-edit-outline"></i></span><span class="menu-item-label">Publicar empleo</span></a></li>
        <li id="sidebarMisEmpleos"><a href="/usuario/misEmpleos" class="has-icon"><span class="icon"><i class="mdi mdi-format-list-bulleted"></i></span><span class="menu-item-label">Mis empleos</span></a></li>
        <li id="sidebarSolicitudes"><a href="/usuario/solicitudes" class="has-icon"><span class="icon"><i class="mdi mdi-account-clock"></i></span><span class="menu-item-label">Solicitudes</span></a></li>
        <li id="sidebarBuscarTalento" style="display:none;"><a href="/usuario/buscar-talento" class="has-icon"><span class="icon"><i class="mdi mdi-magnify"></i></span><span class="menu-item-label">Buscar trabajo</span></a></li>
        <li id="sidebarMisVacantes" style="display:none;"><a href="/usuario/mis-vacantes" class="has-icon"><span class="icon"><i class="mdi mdi-briefcase-outline"></i></span><span class="menu-item-label">Mis vacantes</span></a></li>
        <li id="sidebarPostulantes" style="display:none;"><a href="/usuario/postulantes" class="has-icon"><span class="icon"><i class="mdi mdi-account-group"></i></span><span class="menu-item-label">Postulantes</span></a></li>
        <li id="sidebarPublicarVacante" style="display:none;"><a href="/usuario/publicar-vacante" class="has-icon"><span class="icon"><i class="mdi mdi-plus-circle"></i></span><span class="menu-item-label">Publicar vacante</span></a></li>
        <li><a href="/usuario/profile" class="is-active router-link-active has-icon"><span class="icon has-update-mark"><i class="mdi mdi-account-circle"></i></span><span class="menu-item-label">Perfil</span></a></li>
      </ul>
    </div>
  </aside>

  <!-- CONTENIDO PRINCIPAL MEJORADO -->
  <section class="section is-main-section">
    <div class="container">

      <!-- Toast de notificación -->
      <div id="profileToast" class="notification is-success" style="display: none; position: fixed; top: 70px; right: 20px; z-index: 1000; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);"></div>

      <!-- Estadísticas rápidas (más pro) -->
      <div class="columns">
        <div class="column">
          <div class="stats-card">
            <span class="icon is-large"><i class="mdi mdi-briefcase mdi-36px" style="color:#ff7a18"></i></span>
            <div class="stat-number">{{ $serviciosPublicados }}</div>
            <p class="has-text-grey">Servicios publicados</p>
          </div>
        </div>
        <div class="column">
          <div class="stats-card">
            <span class="icon is-large"><i class="mdi mdi-star mdi-36px" style="color:#ffb347"></i></span>
            <div class="stat-number">{{ $calificacionPromedio ?? '—' }}</div>
            <p class="has-text-grey">Calificación promedio{{ $totalCalificaciones ? " ({$totalCalificaciones} " . ($totalCalificaciones === 1 ? 'reseña' : 'reseñas') . ")" : '' }}</p>
          </div>
        </div>
        <div class="column">
          <div class="stats-card">
            <span class="icon is-large"><i class="mdi mdi-account-group mdi-36px" style="color:#ff7a18"></i></span>
            <div class="stat-number">{{ $clientesAtendidos }}</div>
            <p class="has-text-grey">Clientes atendidos</p>
          </div>
        </div>
      </div>

      <!-- TABS para organizar la información -->
      <div class="tabs is-boxed is-centered" id="profileTabs">
        <ul>
          <li class="is-active" data-tab="tab-info"><a><span class="icon"><i class="mdi mdi-account"></i></span> Información personal</a></li>
          <li data-tab="tab-stats"><a><span class="icon"><i class="mdi mdi-chart-line"></i></span> Estadísticas</a></li>
          <li data-tab="tab-docs"><a><span class="icon"><i class="mdi mdi-folder-account"></i></span> Documentos</a></li>
        </ul>
      </div>

      <!-- PANEL 1: Información personal (editable, con datos reales) -->
      <div id="tab-info" class="tab-content" style="display: block;">
        <div class="tile is-ancestor">
          <!-- Columna izquierda: Avatar + estado -->
          <div class="tile is-parent is-4">
            <div class="card tile is-child">
              <div class="card-content has-text-centered">
                <div class="profile-avatar-large" id="avatarCircle"
                  @if ($usuario->foto_perfil) style="background-image:url('{{ $usuario->foto_perfil }}'); background-size:cover; background-position:center;" @endif>
                  @if (!$usuario->foto_perfil)
                    {{ collect(explode(' ', $usuario->nombre))->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))->take(2)->implode('') }}
                  @endif
                </div>
                <div class="mt-3">
                  <span id="verificationBadge" class="verification-badge {{ $usuario->verificacion_estado === 'aprobado' ? 'aprobado' : 'pending' }}">
                    <i class="mdi mdi-shield-check"></i>
                    {{ $usuario->verificacion_estado === 'aprobado' ? 'Cuenta verificada' : 'Verificación pendiente' }}
                  </span>
                </div>
                <div class="mt-3">
                  <button id="uploadPhotoBtn" class="button is-small btn-outline-orange"><i class="mdi mdi-camera"></i> Cambiar foto</button>
                  <input type="file" id="photoInput" accept="image/*" style="display: none;">
                </div>
                <hr>
                <div class="info-row"><i class="mdi mdi-email"></i> <span id="displayEmailStatic">{{ $usuario->email }}</span></div>
                <div class="info-row"><i class="mdi mdi-phone"></i> <span id="displayPhoneStatic">{{ $usuario->telefono ?? 'Sin teléfono' }}</span></div>
                <div class="info-row"><i class="mdi mdi-map-marker"></i> <span id="displayMunicipioStatic">{{ $usuario->localidad->nombre ?? '—' }}</span></div>
                <div class="info-row"><i class="mdi mdi-calendar"></i> Miembro desde: <strong>{{ $usuario->created_at->translatedFormat('F Y') }}</strong></div>
              </div>
            </div>
          </div>

          <!-- Columna derecha: Formulario de edición -->
<div class="tile is-parent">
  <div class="card tile is-child perfil-card">

    <header class="card-header perfil-header">
      <p class="card-header-title">
        <span class="icon">
          <i class="mdi mdi-account-edit"></i>
        </span>
        Editar perfil
      </p>
    </header>

    <div class="card-content">
      <form id="formPerfil">

        <div class="field">
          <label class="label">Nombre completo</label>
          <div class="control">
            <input type="text" id="inputNombre" name="nombre" class="input perfil-input"
              value="{{ $usuario->nombre }}" required>
          </div>
        </div>

        <div class="field">
          <label class="label">Correo electrónico</label>
          <div class="control">
            <input type="email" id="inputEmail" name="email" class="input perfil-input"
              value="{{ $usuario->email }}" required>
          </div>
        </div>

        <div class="field">
          <label class="label">Teléfono</label>
          <div class="control">
            <input type="tel" id="inputPhone" name="telefono" class="input perfil-input"
              value="{{ $usuario->telefono }}">
          </div>
        </div>

        <div class="field">
          <label class="label">Localidad</label>
          <div class="control">
            <div class="select is-fullwidth">
              <select id="inputLocalidad" name="localidad_id" class="perfil-input" required>
                @foreach ($localidades as $loc)
                  <option value="{{ $loc->id }}" {{ $usuario->localidad_id === $loc->id ? 'selected' : '' }}>{{ $loc->nombre }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <p class="help">Municipio: {{ $usuario->localidad->municipio->nombre ?? 'Zacapoaxtla' }}.</p>
        </div>

        <div class="field">
          <label class="label">Descripción / Biografía</label>
          <div class="control">
            <textarea id="inputDescripcion" name="descripcion"
              class="textarea perfil-input"
              rows="4"
              placeholder="Cuéntanos sobre ti...">{{ $usuario->descripcion }}</textarea>
          </div>
        </div>

        <div class="field mt-5">
          <button type="submit" class="btn-guardar-perfil">
            <i class="mdi mdi-content-save"></i>
            Guardar cambios
          </button>
        </div>

      </form>
    </div>

  </div>
</div>
        </div>

        <!-- Cambiar/crear contraseña -->
        <div class="card mt-4">
          <header class="card-header"><p class="card-header-title"><span class="icon"><i class="mdi mdi-lock-reset"></i></span>{{ $usuario->password ? 'Cambiar contraseña' : 'Crear una contraseña' }}</p></header>
          <div class="card-content">
            @if (!$usuario->password)
              <div class="notification is-info is-light">
                <i class="mdi mdi-information"></i> Entraste con Google, así que todavía no tienes contraseña. Puedes crear una aquí para también poder iniciar sesión con tu correo y contraseña.
              </div>
            @endif
            <form id="formPass">
              @if ($usuario->password)
                <div class="field"><label class="label">Contraseña actual</label><div class="control" style="position:relative;"><input type="password" id="passActual" name="password_actual" class="input" placeholder="••••••••" style="padding-right:2.5em;" required><span onclick="verPasswordPerfil('passActual', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; color:#999;"><i class="mdi mdi-eye"></i></span></div></div>
              @endif
              <div class="field"><label class="label">Nueva contraseña</label><div class="control" style="position:relative;"><input type="password" id="passNueva" name="password_nueva" class="input" placeholder="Mínimo 8 caracteres" style="padding-right:2.5em;" required><span onclick="verPasswordPerfil('passNueva', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; color:#999;"><i class="mdi mdi-eye"></i></span></div></div>
              <div class="field"><label class="label">Confirmar contraseña</label><div class="control" style="position:relative;"><input type="password" id="passConfirm" name="password_nueva_confirmation" class="input" placeholder="Repite la nueva contraseña" style="padding-right:2.5em;" required><span onclick="verPasswordPerfil('passConfirm', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; color:#999;"><i class="mdi mdi-eye"></i></span></div></div>
              <div class="field"><button type="submit" class="button is-primary" style="background: linear-gradient(45deg, #ff7a18, #ffb347); border: none;">{{ $usuario->password ? 'Actualizar contraseña' : 'Crear contraseña' }}</button></div>
            </form>
          </div>
        </div>
      </div>

      <!-- PANEL 2: Estadísticas / Actividad reciente (todavía sin sistema de reseñas real) -->
      <div id="tab-stats" class="tab-content" style="display: none;">
        <div class="card">
          <div class="card-content">
            <h3 class="title is-5"><i class="mdi mdi-chart-timeline-variant"></i> Actividad reciente</h3>
            <div class="notification is-warning is-light">
              <i class="mdi mdi-information"></i> Esta sección todavía es solo de muestra — tu base de datos aún no tiene un sistema de reseñas ni bitácora de actividad. La conectamos cuando construyamos esa parte.
            </div>
          </div>
        </div>
      </div>

      <!-- PANEL 3: Documentos de verificación (ahora reales) -->
      <div id="tab-docs" class="tab-content" style="display: none;">
        <div class="card">
          <div class="card-content">
            <h3 class="title is-5"><i class="mdi mdi-folder-upload"></i> Documentos para verificación</h3>

            <div class="document-card">
              <span><i class="mdi mdi-card-account-details"></i> Identificación oficial (INE)</span>
              <span class="tag {{ $docIne && $docIne->estado === 'aprobado' ? 'is-success' : ($docIne && $docIne->estado === 'rechazado' ? 'is-danger' : 'is-warning') }}">
                {{ $docIne ? ucfirst($docIne->estado) : 'No subido' }}
              </span>
              <button class="button is-small btn-outline-orange" onclick="document.getElementById('inputIne').click()">Subir</button>
              <input type="file" id="inputIne" accept="image/*" style="display:none" onchange="subirDocumento('ine', this)">
            </div>

            <div class="document-card">
              <span><i class="mdi mdi-face-recognition"></i> Selfie con identificación</span>
              <span class="tag {{ $docSelfie && $docSelfie->estado === 'aprobado' ? 'is-success' : ($docSelfie && $docSelfie->estado === 'rechazado' ? 'is-danger' : 'is-warning') }}">
                {{ $docSelfie ? ucfirst($docSelfie->estado) : 'No subido' }}
              </span>
              <button class="button is-small btn-outline-orange" onclick="document.getElementById('inputSelfie').click()">Subir</button>
              <input type="file" id="inputSelfie" accept="image/*" style="display:none" onchange="subirDocumento('selfie', this)">
            </div>

            <div class="notification is-info is-light mt-4"><i class="mdi mdi-information"></i> Los documentos verificados te darán la insignia "Verificado" y mayor confianza. Un administrador los revisa manualmente.</div>
          </div>
        </div>
      </div>
    </div>
  </section>



  <footer class="footer">
    <div class="container-fluid">
      <div class="level">
        <div class="level-left">
          <div class="level-item">© 2025 · Empleos Zacapoaxtla · Tu próxima oportunidad laboral</div>
        </div>
      </div>
    </div>
  </footer>
</div>

<script src="{{ asset('assets/usuario/js/main.js') }}"></script>
<script src="{{ asset('assets/usuario/js/interruptor.js') }}"></script>
<script>
  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

  // Control de pestañas
  const tabs = document.querySelectorAll('#profileTabs li');
  const contents = {
    'tab-info': document.getElementById('tab-info'),
    'tab-stats': document.getElementById('tab-stats'),
    'tab-docs': document.getElementById('tab-docs')
  };
  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const target = tab.dataset.tab;
      tabs.forEach(t => t.classList.remove('is-active'));
      tab.classList.add('is-active');
      Object.values(contents).forEach(c => c.style.display = 'none');
      contents[target].style.display = 'block';
    });
  });

  function verPasswordPerfil(inputId, iconSpan) {
    const input = document.getElementById(inputId);
    const icon = iconSpan.querySelector('i');
    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('mdi-eye');
      icon.classList.add('mdi-eye-off');
    } else {
      input.type = 'password';
      icon.classList.remove('mdi-eye-off');
      icon.classList.add('mdi-eye');
    }
  }

  function mostrarToast(msg, type) {
    let toast = document.getElementById('profileToast');
    toast.textContent = msg;
    toast.className = `notification ${type}`;
    toast.style.display = 'block';
    setTimeout(() => { toast.style.display = 'none'; }, 3000);
  }

  // ===== Guardar información personal (guarda de verdad en la base de datos) =====
  const formPerfil = document.getElementById('formPerfil');
  let fotoPendiente = null;

  formPerfil.addEventListener('submit', (e) => {
    e.preventDefault();

    const formData = new FormData(formPerfil);
    if (fotoPendiente) formData.append('foto', fotoPendiente);

    // PHP no procesa bien multipart/form-data en peticiones PATCH/PUT.
    // Lo mandamos como POST con _method=PATCH para que Laravel lo trate como PATCH.
    formData.append('_method', 'PATCH');

    fetch('/usuario/profile', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
      },
      body: formData,
    })
      .then(res => res.json().then(data => ({ ok: res.ok, data })))
      .then(({ ok, data }) => {
        if (!ok) {
          let msg = data.message || 'Ocurrió un error al guardar.';
          if (data.errors) {
            msg = Object.values(data.errors).flat().join(' ');
          }
          mostrarToast('❌ ' + msg, 'is-danger');
          return;
        }
        mostrarToast('✅ Perfil actualizado correctamente', 'is-success');
        document.getElementById('displayEmailStatic').textContent = data.usuario.email;
        document.getElementById('displayPhoneStatic').textContent = data.usuario.telefono || 'Sin teléfono';
        if (data.usuario.localidad) {
          document.getElementById('displayMunicipioStatic').textContent = data.usuario.localidad.nombre;
        }
      })
      .catch((err) => {
        console.error('Error al guardar el perfil:', err);
        mostrarToast('❌ Ocurrió un error de conexión.', 'is-danger');
      });
  });

  // ===== Cambiar/crear contraseña (guarda de verdad en la base de datos) =====
  const formPass = document.getElementById('formPass');
  formPass.addEventListener('submit', (e) => {
    e.preventDefault();

    const passActualEl = document.getElementById('passActual');
    const body = {
      password_nueva: document.getElementById('passNueva').value,
      password_nueva_confirmation: document.getElementById('passConfirm').value,
    };
    if (passActualEl) {
      body.password_actual = passActualEl.value;
    }

    fetch('/usuario/profile/password', {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
      },
      body: JSON.stringify(body),
    })
      .then(res => res.json().then(data => ({ ok: res.ok, data })))
      .then(({ ok, data }) => {
        if (!ok) {
          let msg = data.message || 'Ocurrió un error al actualizar la contraseña.';
          if (data.errors) {
            msg = Object.values(data.errors).flat().join(' ');
          }
          mostrarToast('❌ ' + msg, 'is-danger');
          return;
        }
        mostrarToast(data.creada ? '✅ Contraseña creada. Ya puedes entrar también con correo y contraseña.' : '✅ Contraseña actualizada correctamente.', 'is-success');
        formPass.reset();
      })
      .catch((err) => {
        console.error('Error al actualizar la contraseña:', err);
        mostrarToast('❌ Ocurrió un error de conexión.', 'is-danger');
      });
  });

  // ===== Subir foto de perfil (guarda de verdad al enviar el formulario de arriba) =====
  const uploadBtn = document.getElementById('uploadPhotoBtn');
  const photoInput = document.getElementById('photoInput');
  const avatarCircle = document.getElementById('avatarCircle');
  uploadBtn.addEventListener('click', () => photoInput.click());
  photoInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
      fotoPendiente = file;
      const reader = new FileReader();
      reader.onload = function (ev) {
        avatarCircle.style.backgroundImage = `url(${ev.target.result})`;
        avatarCircle.style.backgroundSize = 'cover';
        avatarCircle.style.backgroundPosition = 'center';
        avatarCircle.textContent = '';
        mostrarToast('🖼️ Foto lista — dale "Guardar cambios" para que se suba de verdad.', 'is-info');
      };
      reader.readAsDataURL(file);
    }
  });

  // ===== Subir documentos de verificación (guarda de verdad) =====
  function subirDocumento(tipo, input) {
    const file = input.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('archivo', file);

    fetch(`/usuario/profile/documento/${tipo}`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken },
      body: formData,
    })
      .then(res => {
        if (!res.ok) throw new Error();
        mostrarToast('📄 Documento subido, en espera de revisión.', 'is-success');
        setTimeout(() => location.reload(), 1200);
      })
      .catch(() => mostrarToast('❌ Ocurrió un error al subir el documento.', 'is-danger'));
  }
</script>
  <script src="{{ asset('assets/usuario/js/dashboard-data.js') }}"></script>
  <script src="{{ asset('assets/usuario/js/ads-widget.js') }}"></script>

  <script>
    (function () {
      const logoutBtn = document.getElementById('logoutBtn');
      if (!logoutBtn) return;
      logoutBtn.addEventListener('click', function (e) {
        e.preventDefault();
        if (!confirm('¿Seguro que quieres cerrar sesión?')) return;
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfMeta ? csrfMeta.content : '';
        fetch('/logout', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        }).finally(() => { window.location.href = '/'; });
      });
    })();
  </script>
</body>
</html>
