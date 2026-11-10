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

    .form-group .form-hint {
      display: block;
    }

    /* Duración del trabajo: el número y la unidad, uno junto al otro */
    .duracion-fila {
      display: flex;
      gap: 10px;
    }

    .duracion-fila select {
      flex: 1;
      min-width: 0;
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

    /* Los toasts aparecen deslizándose desde la derecha (se ocultan solos, ver JS). */
    .success-toast, .error-toast {
      animation: toastSlideIn 0.35s ease-out;
    }

    @keyframes toastSlideIn {
      from { transform: translateX(120%); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }

    /* Botón deshabilitado mientras se está publicando (evita doble clic = doble publicación). */
    .btn-publicar:disabled {
      opacity: 0.6;
      cursor: not-allowed;
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
  <link rel="stylesheet" href="{{ asset('css/anuncio-negocio.css') }}">

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
              <a href="/usuario/pronostico" class="has-icon">
                  <span class="icon"><i class="mdi mdi-chart-line"></i></span>
                  <span class="menu-item-label">Pronóstico</span>
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
        <div style="border-radius:14px; margin:0 0 18px; padding:14px 18px; background:#fff8f0; border:1px solid #ffe0bf; color:#5b4636; line-height:1.55;">
          <strong>¿Qué haces aquí?</strong> Publicas el trabajo que ofreces: describe lo que necesitas que hagan y cuántas personas ocupas. Las personas se postulan y las ves en <strong>"Postulantes"</strong>.
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

            @php
              // Fechas: el calendario solo deja elegir de mañana en adelante (máximo 2 años).
              $manana = now()->addDay()->toDateString();
              $tope = now()->addYears(2)->toDateString();
              $trabajoIso = $vacante ? ($vacante->fecha_trabajo_iso ?? '') : '';
              $trabajoAntes = ($vacante && $vacante->fecha_trabajo && !$trabajoIso) ? $vacante->fecha_trabajo : null;
              $limiteIso = $vacante && $vacante->fecha_limite ? $vacante->fecha_limite->format('Y-m-d') : '';
              $estaVencida = $vacante && $vacante->esta_vencida;

              // Duración: "3 días" -> número 3 + unidad días (se arma con dos combos).
              $duracionTexto = $vacante->duracion ?? '';
              $duracionValida = (bool) preg_match('/^(\d{1,2}) (día|días|semana|semanas|mes|meses|año|años)$/u', $duracionTexto);
              $duracionAntes = ($duracionTexto !== '' && !$duracionValida) ? $duracionTexto : null;

              // Salario: solo el monto ("$250" -> 250).
              $salarioTexto = trim($vacante->salario ?? '');
              $salarioValor = '';
              if (preg_match('/^\$?\s*(\d{1,3}(?:,\d{3})+|\d+)(\.\d{1,2})?$/', $salarioTexto, $ms)) {
                $salarioValor = str_replace(',', '', $ms[1]) . ($ms[2] ?? '');
              }
              $salarioAntes = ($salarioTexto !== '' && $salarioTexto !== 'A convenir' && $salarioValor === '') ? $salarioTexto : null;

              // Teléfonos: solo 10 dígitos.
              $soloTel = function ($v) {
                $d = preg_replace('/\D/', '', (string) $v);
                return strlen($d) > 10 ? substr($d, -10) : $d;
              };
            @endphp

            <form id="vacanteForm" novalidate
                  data-manana="{{ $manana }}" data-tope="{{ $tope }}"
                  data-trabajo-original="{{ $trabajoIso }}" data-limite-original="{{ $limiteIso }}"
                  data-vencida="{{ $estaVencida ? '1' : '' }}"
                  data-duracion="{{ $duracionValida ? $duracionTexto : '' }}">

              @if ($estaVencida)
                <div class="form-group" id="avisoVencida" style="background:#fff4e5; border:1px solid #f5c38a; border-radius:8px; padding:12px 14px; margin-bottom:14px; font-size:13.5px; color:#9a4a00; line-height:1.5;">
                  <strong><i class="mdi mdi-alert-outline"></i> Esta vacante ya venció{{ $vacante->fecha_limite ? ' (su límite para postular fue el ' . $vacante->fecha_limite->format('d/m/Y') . ')' : '' }} y no se está mostrando a los trabajadores.</strong><br>
                  Para volver a publicarla, elige un <strong>nuevo «Día del trabajo»</strong> y un <strong>nuevo «Límite para postular»</strong> (desde mañana). Si solo guardas otros cambios sin mover las fechas, seguirá vencida.
                </div>
              @endif

              @if ($tituloDescripcionBloqueada)
                <div class="form-group" style="background:#fef7e0; border:1px solid #f5d78e; border-radius:8px; padding:10px 14px; margin-bottom:14px; font-size:13px; color:#8a6d1f;">
                  <i class="mdi mdi-lock-outline"></i>
                  Esta vacante ya tiene postulaciones, así que el título y la descripción ya no se pueden cambiar (para no confundir a quien ya se postuló pensando que era para este trabajo). El resto de los datos sí se puede editar. Si necesitas más trabajadores para un tipo de trabajo distinto, publica una vacante nueva.
                </div>
              @endif

              <!-- TÍTULO -->
              <div class="form-group">
                <label for="titulo">Título de la Vacante <span class="required">*</span></label>
                <input type="text" id="titulo" name="titulo" value="{{ $vacante->titulo ?? '' }}" placeholder="Ej: Auxiliar administrativo, Ayudante general, Cocinero..." maxlength="100" autocomplete="off" required {{ $tituloDescripcionBloqueada ? 'disabled' : '' }}>
              </div>

              <!-- CATEGORÍA Y SUBCATEGORÍA -->
              <div class="form-row">
                <div class="form-group">
                  <label for="categoria_id">Categoría <span class="required">*</span></label>
                  <select id="categoria_id" name="categoria_id" required>
                    <option value="">Selecciona una categoría</option>
                    @foreach ($categorias as $categoria)
                      <option value="{{ $categoria->id }}" {{ (int) ($vacante->categoria_id ?? 0) === (int) $categoria->id ? 'selected' : '' }}>{{ $categoria->nombre }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="subcategoria_id">Subcategoría <span class="required">*</span></label>
                  <select id="subcategoria_id" name="subcategoria_id" required disabled>
                    <option value="">Primero elige una categoría</option>
                  </select>
                </div>
              </div>
              <span class="form-hint" style="display:block; margin:-6px 0 14px;">
                Sirve para mostrarte en qué días y meses hay más demanda de este tipo de trabajo.
                <a id="verPronostico" href="/usuario/pronostico" target="_blank" rel="noopener" style="display:none; font-weight:700; color:#e8590c;">
                  <i class="mdi mdi-chart-line"></i> Ver pronóstico de esta categoría
                </a>
              </span>

              <!-- PUBLICANTE Y UBICACIÓN -->
              <div class="form-row">
                <div class="form-group">
                  <label for="publicante">Nombre del publicante <span class="required">*</span></label>
                  <input type="text" id="publicante" name="publicante" value="{{ $vacante->publicante ?? $usuario->nombre }}" placeholder="Ej: Juan Méndez" maxlength="100" autocomplete="off" required>
                  <span class="form-hint">Autocompletado de tu perfil. Cámbialo si publicas a nombre de un negocio.</span>
                </div>
                <div class="form-group">
                  <label for="ubicacion">Ubicación <span class="required">*</span></label>
                  <input type="text" id="ubicacion" name="ubicacion" value="{{ $vacante->ubicacion ?? ($usuario->localidad->nombre ?? '') }}" placeholder="Ej: Las Lomas, Zacapoaxtla" maxlength="150" autocomplete="off" required>
                  <span class="form-hint">Autocompletado de tu perfil. Cámbialo si el trabajo es en otro lugar.</span>
                </div>
              </div>

              <!-- DETALLES PRINCIPALES -->
              <div class="form-row-3">
                <div class="form-group">
                  <label for="trabajadores">Número de trabajadores <span class="required">*</span></label>
                  <input type="number" id="trabajadores" name="trabajadores_requeridos" value="{{ $vacante->trabajadores_requeridos ?? '' }}" placeholder="Ej: 2" min="1" max="100" step="1" inputmode="numeric" required>
                  <span class="form-hint">De 1 a 100</span>
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
                  <label for="salario">Salario (MXN)</label>
                  <input type="text" id="salario" name="salario" value="{{ $salarioValor }}" placeholder="Ej: 250" inputmode="decimal" maxlength="10" autocomplete="off">
                  <span class="form-hint">Solo el monto, en pesos. Si lo dejas vacío dirá «A convenir».</span>
                  @if ($salarioAntes)
                    <span class="form-hint" style="color:#8a6d1f;">Antes decía: «{{ $salarioAntes }}». Escribe el monto si quieres mostrarlo.</span>
                  @endif
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
                <textarea id="descripcion" name="descripcion" placeholder="Describe las responsabilidades y requisitos del puesto..." maxlength="2000" required {{ $tituloDescripcionBloqueada ? 'disabled' : '' }}>{{ $vacante->descripcion ?? '' }}</textarea>
              </div>

              <!-- DOCUMENTOS QUE PIDES A QUIEN SE POSTULE -->
              <div class="form-group">
                <label>¿Qué documentos necesitas de quien se postule?</label>
                <span class="form-hint" style="display:block; margin-bottom:6px;">Opcional — marca solo lo que de verdad necesites para este puesto.</span>
                <div class="beneficios-grid">
                  <label><input type="checkbox" name="requiere_cv" value="1" {{ ($vacante->requiere_cv ?? false) ? 'checked' : '' }}> 📄 CV</label>
                  <label><input type="checkbox" name="requiere_solicitud_empleo" value="1" {{ ($vacante->requiere_solicitud_empleo ?? false) ? 'checked' : '' }}> 📋 Solicitud de empleo</label>
                </div>
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
                  <input type="date" id="fecha_trabajo" name="fecha_trabajo" value="{{ $trabajoIso }}" min="{{ $manana }}" max="{{ $tope }}" required>
                  <span class="form-hint">Elígelo en el calendario (a partir de mañana).</span>
                  @if ($trabajoAntes)
                    <span class="form-hint" style="color:#8a6d1f;">Antes decía: «{{ $trabajoAntes }}». Elige la fecha en el calendario.</span>
                  @endif
                </div>
                <div class="form-group">
                  <label for="duracion_num">Duración de trabajo</label>
                  <div class="duracion-fila">
                    <select id="duracion_num" aria-label="Número"></select>
                    <select id="duracion_unidad" aria-label="Unidad"></select>
                  </div>
                  <input type="hidden" id="duracion" name="duracion" value="">
                  <span class="form-hint">Opcional. Elige el número y si son días, semanas, meses o años.</span>
                  @if ($duracionAntes)
                    <span class="form-hint" style="color:#8a6d1f;">Antes decía: «{{ $duracionAntes }}». Elígela de nuevo con los selectores.</span>
                  @endif
                </div>
              </div>

              <div class="form-group">
                <label for="fecha_limite">Límite para postular</label>
                <input type="date" id="fecha_limite" name="fecha_limite" value="{{ $limiteIso }}" min="{{ $manana }}" max="{{ $trabajoIso && $trabajoIso < $tope ? $trabajoIso : $tope }}">
                <span class="form-hint">Último día en que se puede postular (ese día todavía cuenta). Después la vacante deja de verse. Si lo dejas vacío, será el día del trabajo.</span>
                <span class="form-hint" id="avisoLimite" style="display:none; color:#b45309; font-weight:600;"></span>
              </div>

              <!-- CONTACTO -->
              <div class="form-row">
                <div class="form-group">
                  <label for="telefono">Teléfono de contacto <span class="required">*</span></label>
                  <input type="tel" id="telefono" name="telefono" value="{{ $soloTel($vacante->telefono ?? '') }}" placeholder="Ej: 2221234567" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" autocomplete="tel-national" required>
                  <span class="form-hint">10 dígitos. Tu número permanecerá privado y protegido. No se mostrará públicamente..</span>
                </div>
                <div class="form-group">
                  <label for="whatsapp">WhatsApp</label>
                  <input type="tel" id="whatsapp" name="whatsapp" value="{{ $soloTel($vacante->whatsapp ?? '') }}" placeholder="Ej: 2221234567" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" autocomplete="off">
                  <span class="form-hint">10 dígitos. Tu número permanecerá privado y protegido. No se mostrará públicamente..</span>
                </div>
              </div>

              <!-- IMAGEN -->
              <div class="form-group">
                <label for="imagen">Imagen de la vacante</label>
                @if ($vacante && $vacante->tiene_imagen_propia)
                  <div class="mb-2"><img src="{{ $vacante->imagen }}" style="max-width:140px; border-radius:10px;"></div>
                  <span class="form-hint">Imagen actual. Sube una nueva solo si quieres reemplazarla.</span>
                @endif
                <input type="file" id="imagen" name="imagen" accept="image/jpeg,image/png,image/webp">
                <span class="form-hint">Opcional. JPG, PNG o WEBP, máximo 4 MB. Se recomienda una imagen representativa del trabajo.</span>
              </div>

              <div class="form-hint"><span class="required">*</span> Campos obligatorios</div>

              <button type="submit" class="btn-publicar" id="btnPublicar">
                <i class="mdi mdi-send"></i> {{ $vacante ? 'Guardar Cambios' : 'Publicar Vacante' }}
              </button>
            </form>

          </div>
        </div>

      </div>
    </section>

    <div class="container my-4 d-none d-md-block">
      @include('partials.anuncio-negocio')
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
  <script src="{{ asset('assets/usuario/js/validaciones.js') }}"></script>
  <script>
    // ===== Categoría -> Subcategoría (la subcategoría depende de la categoría) =====
    (function () {
      const categorias = @json($categorias);
      const catSelect = document.getElementById('categoria_id');
      const subSelect = document.getElementById('subcategoria_id');
      const subGuardada = @json($vacante->subcategoria_id ?? null);

      function cargarSubcategorias(catId, subSeleccionada) {
        if (!catId) {
          subSelect.disabled = true;
          subSelect.innerHTML = '<option value="">Primero elige una categoría</option>';
          return;
        }
        const categoria = categorias.find(c => Number(c.id) === Number(catId));
        const subs = categoria ? categoria.subcategorias : [];
        subSelect.innerHTML = '<option value="">Selecciona una subcategoría</option>';
        subs.forEach(sub => {
          const opt = document.createElement('option');
          opt.value = sub.id;
          opt.textContent = sub.nombre;
          subSelect.appendChild(opt);
        });
        subSelect.disabled = false;
        if (subSeleccionada) subSelect.value = subSeleccionada;
      }

      // Enlace "Ver pronóstico de esta categoría": se abre en otra pestaña ya filtrado
      const enlacePronostico = document.getElementById('verPronostico');
      function actualizarEnlacePronostico() {
        if (!catSelect.value) {
          enlacePronostico.style.display = 'none';
          return;
        }
        const params = new URLSearchParams({ categoria_id: catSelect.value });
        if (subSelect.value) params.set('subcategoria_id', subSelect.value);
        enlacePronostico.href = '/usuario/pronostico?' + params.toString();
        enlacePronostico.style.display = 'inline';
      }

      catSelect.addEventListener('change', () => {
        cargarSubcategorias(catSelect.value, null);
        actualizarEnlacePronostico();
      });
      subSelect.addEventListener('change', actualizarEnlacePronostico);

      // Al editar: la categoría ya viene seleccionada desde el servidor
      if (catSelect.value) cargarSubcategorias(catSelect.value, subGuardada);
      actualizarEnlacePronostico();
    })();

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
      const btnPublicar = document.getElementById('btnPublicar');
      const textoOriginalBoton = btnPublicar.innerHTML;

      // ===== Validaciones del formulario (ver validaciones.js) =====
      const V = window.Validaciones;
      const manana = form.dataset.manana;
      const tope = form.dataset.tope;
      const trabajoOriginal = form.dataset.trabajoOriginal;
      const limiteOriginal = form.dataset.limiteOriginal;

      V.filtroTexto(document.getElementById('titulo'));
      V.filtroTexto(document.getElementById('publicante'));
      V.filtroTexto(document.getElementById('ubicacion'));
      V.filtroTextoLargo(document.getElementById('descripcion'));
      ['titulo', 'descripcion'].forEach(id => V.contador(document.getElementById(id)));
      V.entero(document.getElementById('trabajadores'), 100);
      V.decimal(document.getElementById('salario'), { maxEnteros: 7, decimales: 2 });
      V.soloDigitos(document.getElementById('telefono'), 10);
      V.soloDigitos(document.getElementById('whatsapp'), 10);
      V.vigilarImagen(document.getElementById('imagen'));
      V.iniciarDuracion({ num: '#duracion_num', unidad: '#duracion_unidad', oculto: '#duracion', valorInicial: form.dataset.duracion });
      V.iniciarFechas({ trabajo: '#fecha_trabajo', limite: '#fecha_limite', aviso: '#avisoLimite', manana: manana, tope: tope });

      const reglasVacante = [
        { campo: 'titulo', nombre: 'Título', tipo: 'texto', requerido: true, min: 3, max: 100 },
        { campo: 'categoria_id', nombre: 'Categoría', tipo: 'seleccion', requerido: true },
        { campo: 'subcategoria_id', nombre: 'Subcategoría', tipo: 'seleccion', requerido: true },
        { campo: 'publicante', nombre: 'Nombre del publicante', tipo: 'texto', requerido: true, min: 3, max: 100 },
        { campo: 'ubicacion', nombre: 'Ubicación', tipo: 'texto', requerido: true, min: 3, max: 150 },
        { campo: 'trabajadores_requeridos', nombre: 'Número de trabajadores', tipo: 'entero', requerido: true, min: 1, max: 100 },
        { campo: 'tipo_pago', nombre: 'Tipo de pago', tipo: 'seleccion', requerido: true },
        { campo: 'salario', nombre: 'Salario', tipo: 'decimal', requerido: false, min: 1, max: 1000000 },
        { campo: 'experiencia', nombre: 'Experiencia requerida', tipo: 'seleccion', requerido: true },
        { campo: 'contrato', nombre: 'Tipo de contrato', tipo: 'seleccion', requerido: true },
        { campo: 'descripcion', nombre: 'Descripción', tipo: 'largo', requerido: true, min: 20, max: 2000 },
        {
          campo: 'fecha_trabajo', nombre: 'Día del trabajo', requerido: true,
          custom: V.reglaFecha({ nombre: 'Día del trabajo', minimo: manana, maximo: tope, original: trabajoOriginal })
        },
        {
          campo: 'fecha_limite', nombre: 'Límite para postular', requerido: false,
          custom: function (v) {
            const msg = V.reglaFecha({ nombre: 'Límite para postular', minimo: manana, maximo: tope, original: limiteOriginal })(v);
            if (msg) return msg;
            const trabajo = document.getElementById('fecha_trabajo').value;
            if (trabajo && V.fechaReal(trabajo) && (v !== limiteOriginal || trabajo !== trabajoOriginal) && v > trabajo) {
              return 'El límite para postular no puede ser después del día del trabajo.';
            }
            return null;
          }
        },
        {
          campo: 'duracion', mostrarEn: 'duracion_num', nombre: 'Duración de trabajo', requerido: false,
          custom: function () {
            const n = document.getElementById('duracion_num').value;
            const u = document.getElementById('duracion_unidad').value;
            return (n && u === '') ? 'Elige si son días, semanas, meses o años.' : null;
          }
        },
        { campo: 'telefono', nombre: 'Teléfono de contacto', tipo: 'telefono', requerido: true },
        { campo: 'whatsapp', nombre: 'WhatsApp', tipo: 'telefono', requerido: false },
        { campo: 'imagen', nombre: 'Imagen', tipo: 'imagen', requerido: false }
      ];

      function avisarError(texto) {
        document.getElementById('errorToastText').textContent = texto;
        errorToast.style.display = 'block';
        successToast.style.display = 'none';
        setTimeout(() => { errorToast.style.display = 'none'; }, 5000);
      }

      form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Evita doble clic: si ya se está mandando la publicación, ignora clics/Enter extra.
        if (btnPublicar.disabled) return;

        // Revisa todos los campos; si algo está mal, lo marca en rojo y no manda nada.
        if (!V.validar(form, reglasVacante)) {
          avisarError('Revisa los campos marcados en rojo.');
          return;
        }

        // Si la vacante ya venció y con estas fechas va a SEGUIR vencida, se avisa
        // antes de guardar (para que nadie piense que ya quedó publicada).
        if (form.dataset.vencida === '1') {
          const lim = document.getElementById('fecha_limite').value;
          const trab = document.getElementById('fecha_trabajo').value;
          const limiteFinal = lim || (trab && trab >= manana ? trab : limiteOriginal);
          if (!limiteFinal || limiteFinal < manana) {
            const seguir = confirm(
              '⚠️ Esta vacante ya venció y, con estas fechas, SIGUE VENCIDA: no se va a publicar ni la van a ver los trabajadores.\n\n' +
              'Para volver a publicarla cambia el «Día del trabajo» y el «Límite para postular» a una fecha desde mañana.\n\n' +
              '¿Guardar los cambios de todas formas?'
            );
            if (!seguir) return;
          }
        }

        const formData = new FormData(form);

        const url = @json($vacante ? "/usuario/mis-vacantes/{$vacante->id}" : '/usuario/publicar-vacante');

        @if ($vacante)
          // PHP no procesa bien multipart/form-data en peticiones PATCH/PUT.
          // Lo mandamos como POST con _method=PATCH para que Laravel lo trate como PATCH.
          formData.append('_method', 'PATCH');
        @endif

        btnPublicar.disabled = true;
        btnPublicar.innerHTML = 'Publicando...';

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
                V.errorServidor(form, data.errors, { duracion: 'duracion_num', trabajadores_requeridos: 'trabajadores' });
              }
              document.getElementById('errorToastText').textContent = msg;
              errorToast.style.display = 'block';
              successToast.style.display = 'none';
              setTimeout(() => { errorToast.style.display = 'none'; }, 5000);
              btnPublicar.disabled = false;
              btnPublicar.innerHTML = textoOriginalBoton;
              return;
            }
            successToast.style.display = 'block';
            errorToast.style.display = 'none';
            // El botón se queda deshabilitado: ya vamos a navegar a otra página.
            setTimeout(() => {
              window.location.href = '/usuario/mis-vacantes';
            }, 2000);
          })
          .catch((err) => {
            console.error('Error al guardar la vacante:', err);
            document.getElementById('errorToastText').textContent = 'Ocurrió un error de conexión. Intenta de nuevo.';
            errorToast.style.display = 'block';
            setTimeout(() => { errorToast.style.display = 'none'; }, 5000);
            btnPublicar.disabled = false;
            btnPublicar.innerHTML = textoOriginalBoton;
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
