<!DOCTYPE html>
<html lang="es" class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Publicar Vacante · Empleador</title>

  <link rel="stylesheet" href="{{ asset('assets/usuario/css/main.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/switch.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/employer-dashboard.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
  <link rel="icon" type="img/" href="{{ asset('assets/usuario/img/icono.png') }}">

  <style>
    /* ============================================================
       PUBLICAR VACANTE - ESTILO MEJORADO
       ============================================================ */

    .publicar-card {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      max-width: auto;
      margin: 0 auto;
    }

    .publicar-card-header {
      background: linear-gradient(135deg, #ff7a18, #ffb347);
      padding: 22px 28px;
      color: #fff;
    }

    .publicar-card-header h2 {
      font-size: 20px;
      font-weight: 700;
      margin: 0;
    }

    .publicar-card-header p {
      font-size: 13px;
      opacity: 0.9;
      margin-top: 4px;
    }

    .publicar-card-body {
      padding: 28px;
    }

    .form-group {
      margin-bottom: 18px;
    }

    .form-group label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 6px;
      color: #374151;
    }

    .form-group label .required {
      color: #dc2626;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 11px 14px;
      border: 1.5px solid #e5e7eb;
      border-radius: 10px;
      font-size: 14px;
      outline: none;
      transition: 0.25s;
      font-family: inherit;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      border-color: #ff7a18;
      box-shadow: 0 0 0 3px rgba(255, 122, 24, 0.12);
    }

    .form-group textarea {
      resize: vertical;
      min-height: 100px;
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    .form-row-3 {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 16px;
    }

    .form-hint {
      font-size: 12px;
      color: #9ca3af;
      margin-top: 4px;
    }

    .btn-publicar {
      width: 100%;
      padding: 13px;
      background: linear-gradient(45deg, #ff7a18, #ffb347);
      color: #fff;
      border: none;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      transition: 0.3s;
      margin-top: 8px;
    }

    .btn-publicar:hover {
      opacity: 0.88;
      transform: translateY(-1px);
    }

    .success-toast {
      display: none;
      position: fixed;
      top: 70px;
      right: 20px;
      z-index: 1000;
      max-width: 320px;
      background: #dcfce7;
      border: 1.5px solid #86efac;
      color: #16a34a;
      border-radius: 10px;
      padding: 12px 16px;
      margin-bottom: 16px;
      font-weight: 600;
      font-size: 14px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .error-toast {
      display: none;
      position: fixed;
      top: 70px;
      right: 20px;
      z-index: 1000;
      max-width: 320px;
      background: #fee2e2;
      border: 1.5px solid #fca5a5;
      color: #b91c1c;
      border-radius: 10px;
      padding: 12px 16px;
      margin-bottom: 16px;
      font-weight: 600;
      font-size: 14px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Checkbox de beneficios */
    .beneficios-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
      margin-top: 6px;
    }

    .beneficios-grid label {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 13px;
      font-weight: 400;
      color: #374151;
      cursor: pointer;
    }

    .beneficios-grid input[type="checkbox"] {
      width: 18px;
      height: 18px;
      accent-color: #ff7a18;
      cursor: pointer;
    }

    @media (max-width: 768px) {
      .form-row {
        grid-template-columns: 1fr;
      }
      .form-row-3 {
        grid-template-columns: 1fr;
      }
      .beneficios-grid {
        grid-template-columns: 1fr 1fr;
      }
      .publicar-card-body {
        padding: 16px;
      }
    }

    @media (max-width: 480px) {
      .beneficios-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/ads-widget.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/theme-conectaya.css') }}">

  <style>
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
    .banner-texto h2 { font-size: 24px; margin: 0; }
    .banner-texto p { margin: 0; font-size: 14px; }
    .banner-destaca h2 { margin: 0; font-size: 22px; }
    .banner-destaca h1 { margin: 0; font-size: 32px; color: #ffcf33; font-weight: bold; }
    .banner-icono img { width: 70px; }
    .btn-anunciar { background: #ffc107; color: #000; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-weight: bold; display: inline-block; }
    .banner-boton small { display: block; text-align: center; margin-top: 3px; color: #fff; }
    .banner-persona { height: 90px; }
    .banner-persona img { height: 90px; width: auto; object-fit: cover; border-radius: 999px; }

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
            <span class="crumb-section">Empleador</span>
            <i class="mdi mdi-chevron-right crumb-sep"></i>
            <span class="crumb-page">Publicar Vacante</span>
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

    <!-- SIDEBAR -->
    <aside class="aside is-placed-left is-expanded" id="mainSidebar">
      <div class="aside-tools">
        <div class="aside-tools-label">
          <span><b> Modo Empleador</b></span>
        </div>
      </div>

      <div class="sidebar-role-switch">
        <div class="switch-row" id="switchRow">
          <div class="active-bg employer-bg" id="activeBg"></div>
          <span class="role-text worker-text" id="labelWorker">TRABAJADOR</span>
          <span class="role-text employer-text active" id="labelEmployer">EMPLEADOR</span>
        </div>
        <div class="role-tooltip-sidebar" id="roleTooltip">
          <div class="tooltip-title">
            <span id="tooltipRoleName">🏢 <span class="highlight-employer">Modo Empleador</span></span>
          </div>
          <div class="tooltip-desc" id="tooltipDesc">Panel exclusivo para contratar talento</div>
        </div>
      </div>

      <div class="menu is-menu-main">
        <p class="menu-label">Panel</p>
        <ul class="menu-list">
          <li>
            <a href="/usuario/empleador" class="has-icon">
              <span class="icon"><i class="mdi mdi-desktop-mac"></i></span>
              <span class="menu-item-label">Inicio</span>
            </a>
          </li>
          <p class="menu-label">Acciones</p>
          <li>
            <a href="/usuario/buscar-talento" class="has-icon">
              <span class="icon"><i class="mdi mdi-magnify"></i></span>
              <span class="menu-item-label">Buscar trabajo</span>
            </a>
          </li>
          <li>
            <a href="/usuario/mis-vacantes" class="has-icon">
              <span class="icon"><i class="mdi mdi-briefcase"></i></span>
              <span class="menu-item-label">Mis vacantes</span>
            </a>
          </li>
          <li>
            <a href="/usuario/postulantes" class="has-icon">
              <span class="icon"><i class="mdi mdi-account-group"></i></span>
              <span class="menu-item-label">Postulantes</span>
            </a>
          </li>
        </ul>
        <ul class="menu-list">
          <li>
            <a href="/usuario/publicar-vacante" class="is-active router-link-active has-icon">
              <span class="icon has-update-mark"><i class="mdi mdi-plus-circle"></i></span>
              <span class="menu-item-label">Publicar vacante</span>
            </a>
          </li>
          <li>
            <a href="/usuario/profile" class="has-icon">
              <span class="icon"><i class="mdi mdi-account-circle"></i></span>
              <span class="menu-item-label">Perfil</span>
            </a>
          </li>
        </ul>
      </div>
    </aside>

    <!-- CONTENIDO -->
    <section class="section is-main-section">
      <div class="container">

        <div class="role-notice">
          <span class="notice-icon">🏢</span>
          <span>Estás en modo <strong class="notice-role employer">Empleador</strong> · Publica una nueva vacante para encontrar talento.</span>
        </div>

        <div class="publicar-card">
          <div class="publicar-card-header">
            <h2><i class="mdi mdi-{{ $vacante ? 'pencil' : 'plus-circle' }}"></i> {{ $vacante ? 'Editar Vacante' : 'Publicar Nueva Vacante' }}</h2>
            <p>Completa los siguientes datos para publicar tu oferta de trabajo.</p>
          </div>
          <div class="publicar-card-body">

            <div class="success-toast" id="successToast">
              <i class="mdi mdi-check-circle"></i> {{ $vacante ? '¡Vacante actualizada exitosamente!' : '¡Vacante publicada exitosamente!' }}
            </div>
            <div class="error-toast" id="errorToast">
              <i class="mdi mdi-alert-circle"></i> <span id="errorToastText">Ocurrió un error.</span>
            </div>

            <form id="vacanteForm">

              <!-- TÍTULO -->
              <div class="form-group">
                <label for="titulo">Título de la Vacante <span class="required">*</span></label>
                <input type="text" id="titulo" name="titulo" value="{{ $vacante->titulo ?? '' }}" placeholder="Ej: Electricista Industrial" required>
              </div>

              <!-- PUBLICANTE Y UBICACIÓN -->
              <div class="form-row">
                <div class="form-group">
                  <label for="publicante">Nombre del publicante <span class="required">*</span></label>
                  <input type="text" id="publicante" name="publicante" value="{{ $vacante->publicante ?? $usuario->nombre }}" placeholder="Ej: Juan Méndez" required>
                  <span class="form-hint">Autocompletado de tu perfil. Cámbialo si publicas a nombre de un negocio.</span>
                </div>
                <div class="form-group">
                  <label for="ubicacion">Ubicación <span class="required">*</span></label>
                  <input type="text" id="ubicacion" name="ubicacion" value="{{ $vacante->ubicacion ?? ($usuario->localidad->nombre ?? '') }}" placeholder="Ej: Las Lomas, Zacapoaxtla" required>
                  <span class="form-hint">Autocompletado de tu perfil. Cámbialo si el trabajo es en otro lugar.</span>
                </div>
              </div>

              <!-- DETALLES PRINCIPALES -->
              <div class="form-row-3">
                <div class="form-group">
                  <label for="trabajadores">Número de trabajadores <span class="required">*</span></label>
                  <input type="number" id="trabajadores" name="trabajadores_requeridos" value="{{ $vacante->trabajadores_requeridos ?? '' }}" placeholder="Ej: 2" min="1" required>
                </div>
                <div class="form-group">
                  <label for="tipo_pago">Tipo de pago <span class="required">*</span></label>
                  <select id="tipo_pago" name="tipo_pago" required>
                    <option value="">Selecciona...</option>
                    <option value="Pago al día" {{ ($vacante->tipo_pago ?? '') === 'Pago al día' ? 'selected' : '' }}>Pago al día</option>
                    <option value="Pago por destajo" {{ ($vacante->tipo_pago ?? '') === 'Pago por destajo' ? 'selected' : '' }}>Pago por destajo</option>
                    <option value="Pago semanal" {{ ($vacante->tipo_pago ?? '') === 'Pago semanal' ? 'selected' : '' }}>Pago semanal</option>
                    <option value="Pago quincenal" {{ ($vacante->tipo_pago ?? '') === 'Pago quincenal' ? 'selected' : '' }}>Pago quincenal</option>
                    <option value="Pago mensual" {{ ($vacante->tipo_pago ?? '') === 'Pago mensual' ? 'selected' : '' }}>Pago mensual</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="salario">Salario</label>
                  <input type="text" id="salario" name="salario" value="{{ $vacante->salario ?? '' }}" placeholder="Ej: $250/día o $350/día + bono">
                </div>
              </div>

              <!-- EXPERIENCIA Y TIPO DE CONTRATO -->
              <div class="form-row">
                <div class="form-group">
                  <label for="experiencia">Experiencia requerida <span class="required">*</span></label>
                  <select id="experiencia" name="experiencia" required>
                    <option value="">Selecciona...</option>
                    <option value="Sin experiencia" {{ ($vacante->experiencia ?? '') === 'Sin experiencia' ? 'selected' : '' }}>Sin experiencia</option>
                    <option value="6 meses mínimo" {{ ($vacante->experiencia ?? '') === '6 meses mínimo' ? 'selected' : '' }}>6 meses mínimo</option>
                    <option value="1 año mínimo" {{ ($vacante->experiencia ?? '') === '1 año mínimo' ? 'selected' : '' }}>1 año mínimo</option>
                    <option value="2 años mínimo" {{ ($vacante->experiencia ?? '') === '2 años mínimo' ? 'selected' : '' }}>2 años mínimo</option>
                    <option value="3 años mínimo" {{ ($vacante->experiencia ?? '') === '3 años mínimo' ? 'selected' : '' }}>3 años mínimo</option>
                    <option value="5 años mínimo" {{ ($vacante->experiencia ?? '') === '5 años mínimo' ? 'selected' : '' }}>5 años mínimo</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="contrato">Tipo de contrato <span class="required">*</span></label>
                  <select id="contrato" name="contrato" required>
                    <option value="">Selecciona...</option>
                    <option value="Temporal" {{ ($vacante->contrato ?? '') === 'Temporal' ? 'selected' : '' }}>Temporal</option>
                    <option value="Temporada" {{ ($vacante->contrato ?? '') === 'Temporada' ? 'selected' : '' }}>Temporada</option>
                    <option value="Por obra" {{ ($vacante->contrato ?? '') === 'Por obra' ? 'selected' : '' }}>Por obra</option>
                    <option value="Fijo" {{ ($vacante->contrato ?? '') === 'Fijo' ? 'selected' : '' }}>Fijo</option>
                    <option value="Eventual" {{ ($vacante->contrato ?? '') === 'Eventual' ? 'selected' : '' }}>Eventual</option>
                  </select>
                </div>
              </div>

              <!-- DESCRIPCIÓN -->
              <div class="form-group">
                <label for="descripcion">Descripción del trabajo <span class="required">*</span></label>
                <textarea id="descripcion" name="descripcion" placeholder="Describe las responsabilidades y requisitos del puesto..." required>{{ $vacante->descripcion ?? '' }}</textarea>
              </div>

              <!-- BENEFICIOS -->
              <div class="form-group">
                <label>Beneficios</label>
                <div class="beneficios-grid">
                  <label><input type="checkbox" name="beneficios[]" value="Comida incluida" {{ in_array('Comida incluida', $vacante->beneficios ?? []) ? 'checked' : '' }}> 🍽️ Comida incluida</label>
                  <label><input type="checkbox" name="beneficios[]" value="Alojamiento" {{ in_array('Alojamiento', $vacante->beneficios ?? []) ? 'checked' : '' }}> 🏠 Alojamiento</label>
                  <label><input type="checkbox" name="beneficios[]" value="Transporte incluido" {{ in_array('Transporte incluido', $vacante->beneficios ?? []) ? 'checked' : '' }}> 🚐 Transporte incluido</label>
                  <label><input type="checkbox" name="beneficios[]" value="Equipo de seguridad" {{ in_array('Equipo de seguridad', $vacante->beneficios ?? []) ? 'checked' : '' }}> 🦺 Equipo de seguridad</label>
                  <label><input type="checkbox" name="beneficios[]" value="Uniforme" {{ in_array('Uniforme', $vacante->beneficios ?? []) ? 'checked' : '' }}> 👕 Uniforme</label>
                  <label><input type="checkbox" name="beneficios[]" value="Bono por rendimiento" {{ in_array('Bono por rendimiento', $vacante->beneficios ?? []) ? 'checked' : '' }}> 💰 Bono por rendimiento</label>
                  <label><input type="checkbox" name="beneficios[]" value="Herramientas proporcionadas" {{ in_array('Herramientas proporcionadas', $vacante->beneficios ?? []) ? 'checked' : '' }}> 🔧 Herramientas</label>
                  <label><input type="checkbox" name="beneficios[]" value="Pago puntual" {{ in_array('Pago puntual', $vacante->beneficios ?? []) ? 'checked' : '' }}> ✅ Pago puntual</label>
                </div>
              </div>

              <!-- FECHAS -->
              <div class="form-row">
                <div class="form-group">
                  <label for="fecha_trabajo">Día del trabajo <span class="required">*</span></label>
                  <input type="text" id="fecha_trabajo" name="fecha_trabajo" value="{{ $vacante->fecha_trabajo ?? '' }}" placeholder="Ej: 08/07/2026 o 15 oct - 30 nov" required>
                  <span class="form-hint">Puedes poner una fecha específica o un rango</span>
                </div>
                <div class="form-group">
                  <label for="duracion">Duración de trabajo</label>
                  <input type="text" id="duracion" name="duracion" value="{{ $vacante->duracion ?? '' }}" placeholder="Ej: 1 día, 2 meses, 3 semanas">
                  <span class="form-hint">Ejemplo: 1 día, 2 meses, 3 semanas</span>
                </div>
              </div>

              <div class="form-group">
                <label for="fecha_limite">Límite para postular</label>
                <input type="date" id="fecha_limite" name="fecha_limite" value="{{ $vacante && $vacante->fecha_limite ? $vacante->fecha_limite->format('Y-m-d') : '' }}">
                <span class="form-hint">Fecha hasta la cual los trabajadores pueden postularse</span>
              </div>

              <!-- CONTACTO -->
              <div class="form-row">
                <div class="form-group">
                  <label for="telefono">Teléfono de contacto <span class="required">*</span></label>
                  <input type="tel" id="telefono" name="telefono" value="{{ $vacante->telefono ?? '' }}" placeholder="Ej: 55 1234 5678" required>
                </div>
                <div class="form-group">
                  <label for="whatsapp">WhatsApp</label>
                  <input type="tel" id="whatsapp" name="whatsapp" value="{{ $vacante->whatsapp ?? '' }}" placeholder="Ej: 55 1234 5678">
                  <span class="form-hint">Si es diferente al teléfono</span>
                </div>
              </div>

              <!-- IMAGEN -->
              <div class="form-group">
                <label for="imagen">Imagen de la vacante</label>
                @if ($vacante && $vacante->imagen)
                  <div class="mb-2"><img src="{{ $vacante->imagen }}" style="max-width:140px; border-radius:10px;"></div>
                  <span class="form-hint">Imagen actual. Sube una nueva solo si quieres reemplazarla.</span>
                @endif
                <input type="file" id="imagen" name="imagen" accept="image/*">
                <span class="form-hint">Opcional. Se recomienda una imagen representativa del trabajo</span>
              </div>

              <div class="form-hint"><span class="required">*</span> Campos obligatorios</div>

              <button type="submit" class="btn-publicar">
                <i class="mdi mdi-send"></i> {{ $vacante ? 'Guardar Cambios' : 'Publicar Vacante' }}
              </button>
            </form>

          </div>
        </div>

      </div>
    </section>

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
          <a href="/anunciar" class="btn-anunciar"></a>¡ANÚNCIATE AQUÍ!</a>
          <small>Más información →</small>
        </div>
        <div class="banner-persona">
          <img src="{{ asset('img/anuncios/persona.png') }}" alt="Anunciante">
        </div>
      </div>
    </div>

    <!-- FOOTER -->
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
  <script>
    (function() {
      const labelWorker = document.getElementById('labelWorker');
      const labelEmployer = document.getElementById('labelEmployer');
      const activeBg = document.getElementById('activeBg');
      const tooltip = document.getElementById('roleTooltip');
      let isWorker = false;
      let tooltipTimeout;

      function showTooltip() {
        clearTimeout(tooltipTimeout);
        tooltip.classList.add('show');
        tooltipTimeout = setTimeout(() => {
          tooltip.classList.remove('show');
        }, 2500);
      }

      function updateUI() {
        labelWorker.classList.toggle('active', isWorker);
        labelEmployer.classList.toggle('active', !isWorker);
        activeBg.className = 'active-bg ' + (isWorker ? 'worker-bg' : 'employer-bg');
        if (isWorker) {
          document.getElementById('tooltipRoleName').innerHTML = '👷 <span class="highlight-worker">Modo Trabajador</span>';
          document.getElementById('tooltipDesc').textContent = 'Publica y gestiona tus servicios';
        } else {
          document.getElementById('tooltipRoleName').innerHTML = '🏢 <span class="highlight-employer">Modo Empleador</span>';
          document.getElementById('tooltipDesc').textContent = 'Panel exclusivo para contratar talento';
        }
      }

      function toggleRole(e) {
        e.stopPropagation();
        isWorker = !isWorker;
        updateUI();
        showTooltip();
        localStorage.setItem('userRole', isWorker ? 'worker' : 'employer');
        if (isWorker) window.location.href = '/usuario';
      }

      labelWorker.addEventListener('click', function(e) { if (!isWorker) toggleRole(e); });
      labelEmployer.addEventListener('click', function(e) { if (isWorker) toggleRole(e); });

      const switchRow = document.querySelector('.switch-row');
      switchRow.addEventListener('mouseenter', function() { tooltip.classList.add('show'); });
      switchRow.addEventListener('mouseleave', function() {
        clearTimeout(tooltipTimeout);
        setTimeout(() => tooltip.classList.remove('show'), 200);
      });

      const savedRole = localStorage.getItem('userRole');
      if (savedRole === 'worker') {
        isWorker = true;
        window.location.href = '/usuario';
      }
      updateUI();

      // ============================================================
      // FORMULARIO DE PUBLICACIÓN (guarda de verdad en la base de datos)
      // ============================================================
      const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
      const successToast = document.getElementById('successToast');
      const errorToast = document.getElementById('errorToast');
      const form = document.getElementById('vacanteForm');

      form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);

        const url = @json($vacante ? "/usuario/mis-vacantes/{$vacante->id}" : '/usuario/publicar-vacante');

        @if ($vacante)
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
              document.getElementById('errorToastText').textContent = msg;
              errorToast.style.display = 'block';
              successToast.style.display = 'none';
              return;
            }
            successToast.style.display = 'block';
            errorToast.style.display = 'none';
            setTimeout(() => {
              window.location.href = '/usuario/mis-vacantes';
            }, 2000);
          })
          .catch((err) => {
            console.error('Error al guardar la vacante:', err);
            document.getElementById('errorToastText').textContent = 'Ocurrió un error de conexión. Intenta de nuevo.';
            errorToast.style.display = 'block';
          });
      });

    })();
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
