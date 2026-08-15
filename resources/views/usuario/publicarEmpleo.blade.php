<!DOCTYPE html>
<html lang="es" class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Publicar Empleo · Dashboard</title>

  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/main.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/switch.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="icon" type="img/" href="{{ asset('assets/usuario/img/icono.png') }}">

  <style>
    /* Estilos adicionales para el formulario mejorado */
    .preview-img {
      max-width: 120px;
      max-height: 120px;
      border-radius: 12px;
      border: 2px dashed #ddd;
      margin-top: 8px;
      object-fit: cover;
    }

    .subcategoria-group {
      transition: all 0.2s;
    }
  </style>
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

    /* ===== BANNER "¿TIENES UN NEGOCIO?" - RESPONSIVO ===== */
@media (max-width: 767px) {
  .banner-negocio {
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 30px 22px 26px;
    gap: 10px;
    position: relative;
    overflow: visible;
  }

  /* Ícono de la tienda en un circulito destacado, arriba de todo */
  .banner-icono {
    order: -3;
    background: rgba(255, 255, 255, 0.16);
    border: 1.5px solid rgba(255, 207, 51, 0.4);
    width: 68px;
    height: 68px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 4px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
  }

  .banner-icono img {
    width: 38px;
    filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.2));
  }

  /* Título principal */
  .banner-texto {
    order: -2;
  }

  .banner-texto h2 {
    font-size: 19px;
    font-weight: 800;
    letter-spacing: 0.3px;
    margin-bottom: 4px;
  }

  .banner-texto p {
    font-size: 12.5px;
    opacity: 0.88;
    max-width: 260px;
    margin: 0 auto;
    line-height: 1.45;
  }

  /* Línea divisoria decorativa */
  .banner-destaca {
    order: -1;
    margin-top: 10px;
    padding-top: 12px;
    border-top: 1px solid rgba(255, 255, 255, 0.18);
    width: 100%;
  }

  .banner-destaca h2 {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    opacity: 0.75;
    margin-bottom: 2px;
  }

  .banner-destaca h1 {
    font-size: 25px;
    font-weight: 800;
    text-shadow: 0 2px 8px rgba(255, 207, 51, 0.35);
  }

  /* Botón, con más presencia */
  .banner-boton {
    width: 100%;
    margin-top: 6px;
  }

  .btn-anunciar {
    display: block;
    width: 100%;
    padding: 13px;
    font-size: 14.5px;
    letter-spacing: 0.3px;
    border-radius: 30px;
    text-align: center;
    box-shadow: 0 6px 16px rgba(255, 193, 7, 0.35);
    transition: transform 0.15s;
  }

  .btn-anunciar:active {
    transform: scale(0.97);
  }

  .banner-boton small {
    display: block;
    margin-top: 8px;
    font-size: 11px;
    opacity: 0.7;
  }

  /* Foto de la persona: chiquita, como un detalle final, no la quitamos */
  .banner-persona {
    order: 1;
    margin-top: 14px;
    height: auto;
  }

  .banner-persona img {
    height: 54px;
    width: 54px;
    border: 2px solid rgba(255, 255, 255, 0.5);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  }
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
            <span class="crumb-page">Publicar empleo</span>
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

    <!-- ==================== SIDEBAR ==================== -->
    <aside class="aside is-placed-left is-expanded">
      <div class="aside-tools">
        <div class="aside-tools-label"><span><b>Modo Trabajador</b></span></div>
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
          <li><a href="/usuario" class="has-icon"><span class="icon"><i class="mdi mdi-desktop-mac"></i></span><span
                class="menu-item-label">Inicio</span></a></li>
        </ul>
        <p class="menu-label">Otros</p>
        <ul class="menu-list">
          <li><a href="/usuario/verEmpleos" class="has-icon"><span class="icon"><i
                  class="mdi mdi-briefcase"></i></span><span class="menu-item-label">Ver empleos</span></a></li>
          <li><a href="/usuario/publicarEmpleo" class="is-active router-link-active has-icon"><span
                class="icon has-update-mark"><i class="mdi mdi-square-edit-outline"></i></span><span
                class="menu-item-label">Publicar empleo</span></a></li>
          <li><a href="/usuario/misEmpleos" class="has-icon"><span class="icon"><i
                  class="mdi mdi-format-list-bulleted"></i></span><span class="menu-item-label">Mis empleos</span></a>
          </li>
          <li><a href="/usuario/solicitudes" class="has-icon"><span class="icon"><i
                  class="mdi mdi-account-clock"></i></span><span class="menu-item-label">Solicitudes</span></a>
          </li>
          <li><a href="/usuario/profile" class="has-icon"><span class="icon"><i
                  class="mdi mdi-account-circle"></i></span><span class="menu-item-label">Perfil</span></a></li>
        </ul>
      </div>
    </aside>

    <!-- ==================== FORMULARIO PRINCIPAL ==================== -->
    <section class="section is-main-section publicar-section">
      <!-- AVISO DE ROL -->
      <div class="role-notice" id="roleNotice">
        <span class="notice-icon">👷</span>
        <span>Estás en modo <strong class="notice-role worker" id="roleNameDisplay">Trabajador</strong> · Puedes
          gestionar tus servicios y buscar empleos.</span>
      </div>
      <div class="publicar-card">
        <div class="publicar-card-header">
          <h2>{{ $servicio ? '✏️ Editar Publicación' : '📋 Nueva Publicación' }}</h2>
          <p>Completa el formulario para publicar tu oferta de trabajo o servicio</p>
        </div>
        <div class="publicar-card-body">
          <div id="successToast" class="success-toast" style="display: none;">✅ {{ $servicio ? '¡Cambios guardados con éxito!' : '¡Tu empleo fue publicado con éxito!' }}
          </div>
          <div id="errorToast" class="success-toast" style="display: none; background:#ffe0e0; color:#c62828;">❌ Error:
            completa todos los campos obligatorios.</div>

          <form id="formPublicar">
            <!-- Título -->
            <div class="form-group">
              <label>Título del empleo o servicio *</label>
              <input type="text" id="titulo" name="titulo" value="{{ $servicio->titulo ?? '' }}" placeholder="Ej. Plomero profesional, Diseñador gráfico..." required>
            </div>

            <!-- Categoría y Subcategoría (dinámico, con datos reales de tu base de datos) -->
            <div class="form-row">
              <div class="form-group">
                <label>Categoría *</label>
                <select id="categoriaSelect" name="categoria_id" required>
                  <option value="">Seleccionar categoría</option>
                </select>
              </div>
              <div class="form-group subcategoria-group">
                <label>Subcategoría *</label>
                <select id="subcategoriaSelect" name="subcategoria_id" required disabled>
                  <option value="">Primero elige una categoría</option>
                </select>
              </div>
            </div>

            <!-- Descripción -->
            <div class="form-group">
              <label>Descripción *</label>
              <textarea id="descripcion" name="descripcion" rows="5" placeholder="Describe el trabajo, requisitos, horarios, beneficios..."
                required>{{ $servicio->descripcion ?? '' }}</textarea>
            </div>

            <!-- Precio -->
            <div class="form-group">
              <label>Precio (MXN) *</label>
              <input type="number" id="precio" name="precio" value="{{ $servicio->precio ?? '' }}" step="0.01" min="0" placeholder="Ej. 200.00" required
                onkeydown="if (event.key === '-' || event.key === 'e') event.preventDefault();"
                oninput="if (this.value < 0) this.value = 0;">
              <p class="form-hint">Ingresa el costo del servicio o sueldo ofrecido</p>
            </div>

            <!-- Imagen (opcional, ya se sube de verdad) -->
            <div class="form-group">
              <label>Imagen del servicio (opcional)</label>
              @if ($servicio && $servicio->imagen)
                <div class="mb-2"><img src="{{ $servicio->imagen }}" style="max-width:120px; border-radius:10px;"></div>
                <p class="form-hint">Imagen actual. Sube una nueva solo si quieres reemplazarla.</p>
              @endif
              <input type="file" id="imagen" name="imagen" accept="image/jpeg,image/png,image/jpg,image/webp">
              <div id="previewContainer"></div>
              <p class="form-hint">Formatos: JPG, PNG, WEBP. Máx 4MB.</p>
            </div>

            <!-- Ubicación y contacto -->
            <div class="form-row">
              <div class="form-group">
                <label>Ubicación *</label>
                <input type="text" id="ubicacion" name="ubicacion" value="{{ $servicio->ubicacion ?? ($usuario->localidad->nombre ?? '') }}" placeholder="Ej. Zacapoaxtla, Puebla" required>
                <p class="form-hint">Autocompletado de tu perfil. Cámbialo si el servicio es en otro lugar.</p>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Teléfono de contacto</label>
                <input type="tel" id="telefono" name="telefono" value="{{ $servicio->telefono ?? $usuario->telefono }}" placeholder="Ej. 222 123 4567">
              </div>
              <div class="form-group">
                <label>WhatsApp</label>
                <input type="tel" id="whatsapp" name="whatsapp" value="{{ $servicio->whatsapp ?? $usuario->whatsapp }}" placeholder="Ej. 222 123 4567">
              </div>
            </div>

            <button type="submit" class="btn-publicar"><i class="mdi mdi-send"></i> {{ $servicio ? 'Guardar Cambios' : 'Publicar empleo' }}</button>
          </form>
        </div>
      </div>
    </section>

    <!-- ========================= -->
    <!-- ANUNCIOS INFERIOR -->
    <!-- ========================= -->

    <div class="container my-4 d-none d-md-block">

      <div class="banner-negocio">

        <div class="banner-texto">
          <h2>¿TIENES UN NEGOCIO?</h2>
          <p>Promociona tu negocio y llega a más clientes en tu municipio</p>
        </div>

        <div class="banner-icono">
          <img src="{{ asset('img/anuncios/tienda.png') }}" alt="Tienda">
        </div>

        <div class="banner-destaca">
          <h2>DESTACA TU NEGOCIO</h2>
          <h1>EN ¡CONECTAYA!</h1>
        </div>

        <div class="banner-boton">
          <a href="/anunciar" class="btn-anunciar">
            ¡ANÚNCIATE AQUÍ!
          </a>
          <small>Más información →</small>
        </div>

        <div class="banner-persona">
          <img src="{{ asset('img/anuncios/persona.png') }}" alt="Anunciante">
        </div>

      </div>

    </div>

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
    // ==================== DATOS REALES (vienen del Controller, ya no son inventados) ====================
    const categorias = @json($categorias);

    // Cargar categorías en el select
    const catSelect = document.getElementById('categoriaSelect');
    const subcatSelect = document.getElementById('subcategoriaSelect');

    categorias.forEach(cat => {
      const option = document.createElement('option');
      option.value = cat.id;
      option.textContent = cat.nombre;
      catSelect.appendChild(option);
    });

    // Evento cambio de categoría
    catSelect.addEventListener('change', function () {
      const catId = parseInt(this.value);
      if (!catId) {
        subcatSelect.disabled = true;
        subcatSelect.innerHTML = '<option value="">Primero elige una categoría</option>';
        return;
      }
      const categoria = categorias.find(c => c.id === catId);
      const filtradas = categoria ? categoria.subcategorias : [];
      subcatSelect.innerHTML = '<option value="">Seleccionar subcategoría</option>';
      filtradas.forEach(sub => {
        const opt = document.createElement('option');
        opt.value = sub.id;
        opt.textContent = sub.nombre;
        subcatSelect.appendChild(opt);
      });
      subcatSelect.disabled = false;
    });

    // ===== Si estamos editando, precargar la categoría/subcategoría ya guardadas =====
    const servicioCategoriaId = @json($servicio->categoria_id ?? null);
    const servicioSubcategoriaId = @json($servicio->subcategoria_id ?? null);
    if (servicioCategoriaId) {
      catSelect.value = servicioCategoriaId;
      catSelect.dispatchEvent(new Event('change'));
      subcatSelect.value = servicioSubcategoriaId;
    }

    // Vista previa de imagen
    const imagenInput = document.getElementById('imagen');
    const previewContainer = document.getElementById('previewContainer');
    imagenInput.addEventListener('change', function (e) {
      previewContainer.innerHTML = '';
      const file = e.target.files[0];
      if (file && (file.type.startsWith('image/'))) {
        const reader = new FileReader();
        reader.onload = function (ev) {
          const img = document.createElement('img');
          img.src = ev.target.result;
          img.className = 'preview-img';
          previewContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
      }
    });

    // ==================== Envío del formulario (guarda de verdad en la base de datos) ====================
    const form = document.getElementById('formPublicar');
    const successToast = document.getElementById('successToast');
    const errorToast = document.getElementById('errorToast');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      const titulo = document.getElementById('titulo').value.trim();
      const categoria = catSelect.value;
      const subcategoria = subcatSelect.value;
      const descripcion = document.getElementById('descripcion').value.trim();
      const precio = document.getElementById('precio').value;
      const ubicacion = document.getElementById('ubicacion').value.trim();

      const faltantes = [];
      if (!titulo) faltantes.push('Título');
      if (!categoria) faltantes.push('Categoría');
      if (!subcategoria) faltantes.push('Subcategoría');
      if (!descripcion) faltantes.push('Descripción');
      if (!precio) faltantes.push('Precio');
      if (!ubicacion) faltantes.push('Ubicación');

      if (faltantes.length > 0) {
        errorToast.textContent = '❌ Falta llenar: ' + faltantes.join(', ') + '.';
        errorToast.style.display = 'block';
        successToast.style.display = 'none';
        setTimeout(() => errorToast.style.display = 'none', 4000);
        return;
      }

      if (parseFloat(precio) < 0) {
        errorToast.textContent = '❌ El precio no puede ser negativo.';
        errorToast.style.display = 'block';
        successToast.style.display = 'none';
        setTimeout(() => errorToast.style.display = 'none', 3000);
        return;
      }

      const formData = new FormData(form);

      const url = @json($servicio ? "/usuario/misEmpleos/{$servicio->id}" : '/usuario/publicarEmpleo');

      @if ($servicio)
        // PHP no procesa bien multipart/form-data en peticiones PATCH/PUT.
        // Lo mandamos como POST con _method=PATCH para que Laravel lo trate como PATCH.
        formData.append('_method', 'PATCH');
      @endif

      fetch(url, {
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
            let msg = data.message || 'Ocurrió un error al publicar.';
            if (data.errors) {
              msg = Object.values(data.errors).flat().join(' ');
            }
            errorToast.textContent = '❌ ' + msg;
            errorToast.style.display = 'block';
            successToast.style.display = 'none';
            return;
          }
          successToast.style.display = 'block';
          errorToast.style.display = 'none';
          setTimeout(() => { window.location.href = '/usuario/misEmpleos'; }, 1500);
        })
        .catch((err) => {
          console.error('Error al guardar el servicio:', err);
          errorToast.textContent = '❌ Ocurrió un error de conexión. Intenta de nuevo.';
          errorToast.style.display = 'block';
        });
    });
  </script>
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
