<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Crear mi CV · SINTECZATE</title>
<link rel="icon" type="img/" href="{{ asset('assets/usuario/img/icono.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Fraunces:wght@500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
<style>
  :root {
    --vino: #6b1021; --vino-claro: #8f1d2f; --dorado: #c68f1f; --dorado-claro: #f3d68a;
    --papel: #fdf8f0; --papel-oscuro: #f3ead9; --texto: #241a15; --texto-suave: #6b5d52;
  }
  * { box-sizing: border-box; }
  body { margin: 0; background: #efe8d8; font-family: 'Nunito', sans-serif; color: var(--texto); }

  .top-bar { background: var(--vino); color: #fff; padding: 14px 28px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
  .top-bar .marca { font-family: 'Fraunces', serif; font-weight: 600; font-size: 17px; }
  .top-bar a.salir { color: #fff; opacity: .85; text-decoration: none; font-size: 13px; }
  .progreso-wrap { display: flex; align-items: center; gap: 12px; font-size: 12.5px; }
  .progreso-barra { width: 140px; height: 7px; background: rgba(255,255,255,0.22); border-radius: 20px; overflow: hidden; }
  .progreso-relleno { height: 100%; width: 12.5%; background: var(--dorado-claro); border-radius: 20px; transition: width .25s; }

  .layout { display: grid; grid-template-columns: 210px 1fr 300px; max-width: 1320px; margin: 0 auto; min-height: calc(100vh - 54px); }

  .pasos-sidebar { background: var(--papel); padding: 26px 18px; border-right: 1px solid #e6dbc5; }
  .pasos-titulo { font-family: 'Fraunces', serif; font-size: 15px; font-weight: 600; color: var(--vino); margin: 0 0 4px; padding: 0 10px; }
  .pasos-sub { font-size: 11.5px; color: var(--texto-suave); margin: 0 0 18px; padding: 0 10px; }
  .paso-item { display: flex; align-items: center; gap: 10px; padding: 10px; border-radius: 10px; font-size: 13.5px; color: var(--texto-suave); cursor: pointer; margin-bottom: 2px; transition: 0.15s; }
  .paso-item:hover { background: var(--papel-oscuro); }
  .paso-item.activo { background: #f7e9c9; color: var(--vino); font-weight: 700; }
  .paso-check { width: 20px; height: 20px; border-radius: 50%; border: 2px solid #d8cba9; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 11px; }
  .paso-item.activo .paso-check { border-color: var(--vino); background: var(--vino); color: #fff; }
  .paso-finalizar { margin-top: 14px; padding-top: 14px; border-top: 1px solid #e6dbc5; }

  .contenido { padding: 36px 44px; }
  .contenido h1 { font-family: 'Fraunces', serif; font-size: 24px; font-weight: 600; margin: 0 0 6px; }
  .contenido .ayuda { font-size: 13.5px; color: var(--texto-suave); margin: 0 0 28px; }

  .campo-foto { display: flex; align-items: center; gap: 16px; margin-bottom: 26px; }
  .foto-circulo { width: 68px; height: 68px; border-radius: 50%; background: var(--papel-oscuro); border: 2px dashed #d8cba9; display: flex; align-items: center; justify-content: center; color: var(--texto-suave); font-size: 22px; flex-shrink: 0; overflow: hidden; }
  .foto-circulo img { width: 100%; height: 100%; object-fit: cover; }
  .btn-subir-foto { background: #fff; border: 1.5px solid var(--dorado); color: var(--vino); padding: 9px 18px; border-radius: 10px; font-weight: 700; font-size: 13px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }

  .fila-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
  .campo label { display: block; font-size: 12.5px; font-weight: 700; margin-bottom: 6px; }
  .campo input, .campo select, .campo textarea {
    width: 100%; padding: 11px 14px; border-radius: 10px; border: 1.5px solid #e6dbc5;
    font-family: 'Nunito', sans-serif; font-size: 14px; outline: none; background: #fff; color: var(--texto);
  }
  .campo textarea { resize: vertical; min-height: 80px; }
  .campo input:focus, .campo select:focus, .campo textarea:focus { border-color: var(--dorado); box-shadow: 0 0 0 3px rgba(198,143,31,0.14); }
  .campo { margin-bottom: 16px; }
  .contador { font-size: 11.5px; color: var(--texto-suave); text-align: right; margin-top: 3px; }

  .adicional-toggle { display: flex; align-items: center; justify-content: space-between; background: var(--papel-oscuro); padding: 13px 18px; border-radius: 12px; cursor: pointer; margin: 22px 0 0; }
  .adicional-toggle-texto strong { display: block; font-size: 13.5px; }
  .adicional-toggle-texto small { font-size: 11.5px; color: var(--texto-suave); }
  .adicional-toggle i { color: var(--vino); font-size: 20px; transition: transform 0.2s; }
  .adicional-cuerpo { padding: 18px 4px 4px; }

  .aviso-opcional { display: flex; align-items: flex-start; gap: 8px; font-size: 12px; line-height: 1.5; color: var(--texto-suave); background: #faf1dc; border-left: 3px solid var(--dorado); border-radius: 6px; padding: 10px 12px; margin: 14px 0 0; }
  .aviso-opcional i { color: var(--dorado); font-size: 15px; flex-shrink: 0; margin-top: 1px; }

  /* ===== TARJETAS REPETIBLES (formación, experiencia, cursos, idiomas, referencias) ===== */
  .tarjeta-repetible { background: var(--papel-oscuro); border-radius: 12px; padding: 18px; margin-bottom: 14px; position: relative; }
  .tarjeta-repetible .btn-quitar { position: absolute; top: 12px; right: 12px; background: none; border: none; color: #b04040; cursor: pointer; font-size: 18px; }
  .btn-agregar { background: #fff; border: 1.5px dashed var(--dorado); color: var(--vino); padding: 12px; border-radius: 10px; font-weight: 700; font-size: 13.5px; cursor: pointer; width: 100%; display: flex; align-items: center; justify-content: center; gap: 6px; }

  /* ===== TAGS (competencias, intereses) ===== */
  .tag-input-wrap { display: flex; flex-wrap: wrap; gap: 8px; padding: 10px; border: 1.5px solid #e6dbc5; border-radius: 10px; background: #fff; }
  .tag-chip { display: flex; align-items: center; gap: 6px; background: #f7e9c9; color: var(--vino); font-weight: 700; font-size: 12.5px; padding: 5px 8px 5px 13px; border-radius: 20px; }
  .tag-chip button { background: none; border: none; color: var(--vino); cursor: pointer; font-size: 14px; line-height: 1; }
  .tag-input-wrap input { border: none; outline: none; flex: 1; min-width: 140px; font-family: 'Nunito', sans-serif; font-size: 13.5px; padding: 4px; }

  .nav-botones { display: flex; justify-content: space-between; margin-top: 34px; padding-top: 22px; border-top: 1px solid #e6dbc5; }
  .btn-volver { background: none; border: 1.5px solid #d8cba9; color: var(--texto-suave); padding: 11px 22px; border-radius: 10px; font-weight: 700; font-size: 13.5px; cursor: pointer; }
  .btn-continuar { background: var(--vino); color: #fff; border: none; padding: 11px 26px; border-radius: 10px; font-weight: 700; font-size: 13.5px; cursor: pointer; box-shadow: 0 6px 16px rgba(107,16,33,0.22); }
  .btn-continuar:disabled { opacity: .5; cursor: default; }

  .paso-panel { display: none; }
  .paso-panel.activo { display: block; }

  .subir-alterna { border: 1.5px dashed var(--dorado); border-radius: 12px; padding: 18px; text-align: center; margin-bottom: 26px; }
  .subir-alterna p { font-size: 13px; color: var(--texto-suave); margin: 0 0 10px; }
  .subir-alterna label { background: #fff; border: 1.5px solid var(--dorado); color: var(--vino); padding: 9px 18px; border-radius: 10px; font-weight: 700; font-size: 13px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
  .subir-alterna input[type=file] { display: none; }
  .subir-alterna .archivo-nombre { font-size: 12.5px; color: var(--vino); font-weight: 700; margin-top: 8px; }

  .toast { position: fixed; bottom: 24px; right: 24px; background: var(--vino); color: #fff; padding: 14px 22px; border-radius: 10px; font-weight: 700; font-size: 13.5px; box-shadow: 0 10px 30px rgba(0,0,0,.2); display: none; z-index: 999; }
  .toast.error { background: #b03030; }

  @media (max-width: 760px) {
    .layout { grid-template-columns: 1fr; }
    .pasos-sidebar { display: flex; overflow-x: auto; gap: 6px; padding: 14px; }
    .pasos-titulo, .pasos-sub, .paso-finalizar { display: none; }
    .paso-item { white-space: nowrap; margin-bottom: 0; }
    .contenido { padding: 26px 20px; }
    .fila-2 { grid-template-columns: 1fr; }
    .preview-panel { display: none; }
  }

  /* ===== VISTA PREVIA EN MINIATURA ===== */
  .preview-panel {
    background: #efe2c9;
    padding: 22px 16px;
    border-left: 1px solid #e0d2ab;
    overflow-y: auto;
    max-height: calc(100vh - 54px);
    position: sticky;
    top: 54px;
  }

  .preview-etiqueta {
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: #8a6d1f;
    margin: 0 0 12px;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .preview-hoja {
    background: var(--papel);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 10px 26px rgba(107,16,33,0.16);
    font-size: 7.2px;
    transform-origin: top left;
  }

  .preview-encabezado {
    background: linear-gradient(135deg, var(--vino) 0%, var(--vino-claro) 100%);
    padding: 14px 14px 12px;
    display: flex;
    gap: 8px;
    align-items: center;
  }

  .preview-foto {
    width: 30px; height: 30px; border-radius: 50%; background: var(--dorado-claro);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Fraunces', serif; font-size: 10px; font-weight: 700; color: var(--vino);
    flex-shrink: 0; overflow: hidden; border: 1.5px solid rgba(255,255,255,.35);
  }
  .preview-foto img { width: 100%; height: 100%; object-fit: cover; }

  .preview-nombre { color: #fff; font-family: 'Fraunces', serif; font-size: 11px; font-weight: 600; line-height: 1.2; }
  .preview-nombre small { display: block; color: rgba(255,255,255,.85); font-family: 'Nunito', sans-serif; font-size: 6.5px; font-weight: 400; margin-top: 2px; }

  .preview-contacto { background: var(--papel-oscuro); padding: 5px 14px; font-size: 6px; color: var(--texto-suave); display: flex; flex-wrap: wrap; gap: 6px; }

  .preview-cuerpo { padding: 12px 14px; }
  .preview-resumen { font-size: 6.8px; font-style: italic; color: var(--texto); border-left: 2px solid var(--dorado); padding-left: 7px; margin: 0 0 12px; line-height: 1.4; }

  .preview-sec { margin-bottom: 11px; }
  .preview-sec-titulo { font-family: 'Fraunces', serif; font-size: 7.8px; font-weight: 600; color: var(--vino); margin: 0 0 5px; padding-bottom: 3px; border-bottom: 1px solid #e4d8c2; }
  .preview-item { margin-bottom: 5px; }
  .preview-item-t { font-weight: 700; font-size: 7px; color: var(--texto); }
  .preview-item-s { font-size: 6.3px; color: var(--texto-suave); }

  .preview-tags { display: flex; flex-wrap: wrap; gap: 3px; }
  .preview-tag { font-size: 6px; font-weight: 700; color: var(--vino); background: #f7e9c9; padding: 2px 6px; border-radius: 8px; }

  .preview-vacio { text-align: center; padding: 30px 10px; color: var(--texto-suave); font-size: 11px; }
  .preview-vacio i { font-size: 26px; color: var(--dorado); display: block; margin-bottom: 8px; }
</style>
</head>
<body>

  <form id="formCv" enctype="multipart/form-data">
  @csrf

  <div class="top-bar">
    <span class="marca">¡SINTECZATE! · @if ($servicio ?? null) CV para "{{ $servicio->titulo }}" @else CV para tu postulación a "{{ $postulacion->vacante->titulo }}" @endif</span>
    <div class="progreso-wrap">
      <span>Progreso</span>
      <div class="progreso-barra"><div class="progreso-relleno" id="progresoRelleno"></div></div>
      <span id="progresoTexto">12%</span>
    </div>
    <a href="{{ ($servicio ?? null) ? '/usuario/misEmpleos/' . $servicio->id . '/editar' : '/usuario/postulantes' }}" class="salir">Salir sin guardar</a>
  </div>

  <div class="layout">

    <div class="pasos-sidebar" id="pasosSidebar">
      <p class="pasos-titulo">Crear un CV</p>
      <p class="pasos-sub">Todo es opcional — llena lo que quieras</p>

      <div class="paso-item activo" data-paso="0"><span class="paso-check">1</span> Encabezado</div>
      <div class="paso-item" data-paso="1"><span class="paso-check">2</span> Resumen</div>
      <div class="paso-item" data-paso="2"><span class="paso-check">3</span> Formación</div>
      <div class="paso-item" data-paso="3"><span class="paso-check">4</span> Experiencia</div>
      <div class="paso-item" data-paso="4"><span class="paso-check">5</span> Cursos</div>
      <div class="paso-item" data-paso="5"><span class="paso-check">6</span> Competencias e idiomas</div>
      <div class="paso-item" data-paso="6"><span class="paso-check">7</span> Otras secciones</div>

      <div class="paso-finalizar">
        <div class="paso-item" data-paso="7"><span class="paso-check"><i class="mdi mdi-flag-checkered"></i></span> Finalizar</div>
      </div>
    </div>

    <div class="contenido">

      {{-- ===================== PASO 0: ENCABEZADO ===================== --}}
      <div class="paso-panel activo" data-panel="0">
        <h1>¿Cómo pueden contactarte?</h1>
        <p class="ayuda">Te recomendamos incluir al menos un teléfono. El resto es a tu elección. Todo lo escribes desde cero, no se toma nada de tu perfil.</p>

        <div class="subir-alterna">
          <p><i class="mdi mdi-file-upload-outline"></i> ¿Ya tienes un CV hecho? Súbelo aquí en vez de llenar el formulario.</p>
          <label><i class="mdi mdi-upload"></i> Subir CV (PDF o Word) <input type="file" id="inputArchivoCv" accept=".pdf,.doc,.docx"></label>
          <div class="archivo-nombre" id="archivoNombre"></div>
        </div>

        <div class="campo-foto">
          <div class="foto-circulo" id="fotoPreview"><i class="mdi mdi-account"></i></div>
          <label class="btn-subir-foto"><i class="mdi mdi-camera-outline"></i> Subir foto<input type="file" name="foto" id="inputFoto" accept="image/*" style="display:none;"></label>
        </div>

        <div class="fila-2">
          <div class="campo"><label>Nombre</label><input type="text" name="nombre" placeholder="Ej. Lorena"></div>
          <div class="campo"><label>Apellidos</label><input type="text" name="apellidos" placeholder="Ej. Martínez Alvarez"></div>
        </div>
        <div class="campo"><label>Puesto o rol que buscas (opcional)</label><input type="text" name="titulo" placeholder="Ej. Auxiliar administrativo"></div>

        @if ($ubicacion)
          <p style="font-size:12px; color:var(--texto-suave); margin:-6px 0 16px; display:flex; align-items:center; gap:5px;">
            <i class="mdi mdi-map-marker-outline" style="color:var(--dorado);"></i>
            Tu CV va a mostrar automáticamente tu ubicación: <strong>{{ $ubicacion }}</strong> (la misma de tu perfil).
          </p>
        @endif
        <div class="fila-2">
          <div class="campo"><label>Teléfono</label><input type="text" name="telefono" placeholder="Ej. 233 116 1247"></div>
          <div class="campo"><label>Correo electrónico</label><input type="text" name="correo" placeholder="Ej. tucorreo@gmail.com"></div>
        </div>

        <div class="adicional-toggle" onclick="toggleAdicional(this)">
          <div class="adicional-toggle-texto"><strong>Información adicional</strong><small>Completa solo lo que quieras — todo es opcional</small></div>
          <i class="mdi mdi-chevron-down"></i>
        </div>
        <div class="adicional-cuerpo" id="adicionalCuerpo" style="display:none;">
          <div class="fila-2">
            <div class="campo"><label>Dirección</label><input type="text" name="direccion" placeholder="Opcional"></div>
            <div class="campo"><label>Fecha de nacimiento</label><input type="date" name="fecha_nacimiento"></div>
          </div>
          <div class="fila-2">
            <div class="campo"><label>Nacionalidad</label><input type="text" name="nacionalidad" placeholder="Ej. Mexicana"></div>
            <div class="campo">
              <label>Estado civil</label>
              <select name="estado_civil">
                <option value="">Selecciona (opcional)</option>
                <option>Soltero/a</option><option>Casado/a</option><option>Unión libre</option><option>Otro</option>
              </select>
            </div>
          </div>
          <div class="fila-2">
            <div class="campo">
              <label>Tipo de documento</label>
              <select name="tipo_documento">
                <option value="">Selecciona (opcional)</option>
                <option>INE</option><option>Pasaporte</option><option>Cédula profesional</option>
              </select>
            </div>
            <div class="campo"><label>Detalles del documento</label><input type="text" name="detalles_documento" placeholder="Opcional"></div>
          </div>
          <p class="aviso-opcional"><i class="mdi mdi-shield-alert-outline"></i> Todos estos campos son opcionales. Antes de llenarlos, piénsalo bien: esta información la va a ver cualquiera que reciba tu CV, así que compártela solo si de verdad es necesario.</p>
        </div>
      </div>

      {{-- ===================== PASO 1: RESUMEN ===================== --}}
      <div class="paso-panel" data-panel="1">
        <h1>Resumen profesional</h1>
        <p class="ayuda">Unas líneas breves sobre qué buscas. Opcional, pero le da un toque profesional.</p>
        <div class="campo">
          <textarea name="resumen" maxlength="500" placeholder="Ej. Busco una oportunidad donde pueda aportar orden y responsabilidad..." oninput="actualizarContador(this)"></textarea>
          <div class="contador"><span id="contadorResumen">0</span>/500</div>
        </div>
      </div>

      {{-- ===================== PASO 2: FORMACIÓN ===================== --}}
      <div class="paso-panel" data-panel="2">
        <h1>Formación</h1>
        <p class="ayuda">Tus estudios, del más reciente al más antiguo. Agrega los que quieras.</p>
        <div id="listaFormacion"></div>
        <button type="button" class="btn-agregar" onclick="agregarFormacion()"><i class="mdi mdi-plus"></i> Agregar estudio</button>
      </div>

      {{-- ===================== PASO 3: EXPERIENCIA ===================== --}}
      <div class="paso-panel" data-panel="3">
        <h1>Experiencia laboral</h1>
        <p class="ayuda">Trabajos anteriores, del más reciente al más antiguo.</p>
        <div id="listaExperiencia"></div>
        <button type="button" class="btn-agregar" onclick="agregarExperiencia()"><i class="mdi mdi-plus"></i> Agregar experiencia</button>
      </div>

      {{-- ===================== PASO 4: CURSOS ===================== --}}
      <div class="paso-panel" data-panel="4">
        <h1>Cursos y certificaciones</h1>
        <p class="ayuda">Cursos, talleres o certificaciones que hayas tomado.</p>
        <div id="listaCursos"></div>
        <button type="button" class="btn-agregar" onclick="agregarCurso()"><i class="mdi mdi-plus"></i> Agregar curso</button>
      </div>

      {{-- ===================== PASO 5: COMPETENCIAS E IDIOMAS ===================== --}}
      <div class="paso-panel" data-panel="5">
        <h1>Competencias e idiomas</h1>
        <p class="ayuda">Escribe una competencia y presiona Enter para agregarla.</p>
        <div class="campo">
          <label>Competencias</label>
          <div class="tag-input-wrap" id="wrapHabilidades">
            <input type="text" placeholder="Ej. Atención al cliente" onkeydown="agregarTag(event, 'habilidades')">
          </div>
        </div>

        <h1 style="margin-top:30px; font-size:19px;">Idiomas</h1>
        <div id="listaIdiomas"></div>
        <button type="button" class="btn-agregar" onclick="agregarIdioma()"><i class="mdi mdi-plus"></i> Agregar idioma</button>
      </div>

      {{-- ===================== PASO 6: OTRAS SECCIONES (Intereses + Referencias) ===================== --}}
      <div class="paso-panel" data-panel="6">
        <h1>Otras secciones</h1>
        <p class="ayuda">Intereses personales y referencias — ambos opcionales.</p>

        <div class="campo">
          <label>Intereses</label>
          <div class="tag-input-wrap" id="wrapIntereses">
            <input type="text" placeholder="Ej. Fútbol" onkeydown="agregarTag(event, 'intereses')">
          </div>
        </div>

        <h1 style="margin-top:30px; font-size:19px;">Referencias</h1>
        <div id="listaReferencias"></div>
        <button type="button" class="btn-agregar" onclick="agregarReferencia()"><i class="mdi mdi-plus"></i> Agregar referencia</button>
      </div>

      {{-- ===================== PASO 7: FINALIZAR ===================== --}}
      <div class="paso-panel" data-panel="7">
        <h1>¿Todo listo?</h1>
        <p class="ayuda">Revisa que la información esté bien y guarda tu CV. Puedes editarlo cuando quieras después.</p>
        <div style="background:var(--papel-oscuro); border-radius:12px; padding:20px; text-align:center;">
          <i class="mdi mdi-check-decagram-outline" style="font-size:36px; color:var(--dorado);"></i>
          <p style="font-size:14px; color:var(--texto-suave); margin:10px 0 0;">Al guardar, tu CV va a poder verlo cualquier empleador o cliente que revise tu perfil, servicio o postulación.</p>
        </div>
      </div>

      <div class="nav-botones">
        <button type="button" class="btn-volver" id="btnVolver" onclick="cambiarPaso(-1)">Volver</button>
        <button type="button" class="btn-continuar" id="btnContinuar" onclick="cambiarPaso(1)">Continuar</button>
      </div>

    </div>

    {{-- ===================== VISTA PREVIA EN MINIATURA ===================== --}}
    <div class="preview-panel">
      <p class="preview-etiqueta"><i class="mdi mdi-eye-outline"></i> Vista previa</p>
      <div class="preview-hoja" id="previewHoja">
        <div class="preview-vacio">
          <i class="mdi mdi-file-account-outline"></i>
          Empieza a llenar el formulario y aquí vas viendo cómo se va armando tu CV
        </div>
      </div>
    </div>

  </div>
  </form>

  <div class="toast" id="toast"></div>

<script>
  const UBICACION_USUARIO = @json($ubicacion);
  const TOTAL_PASOS = 8;
  let pasoActual = 0;
  let contadorFormacion = 0, contadorExperiencia = 0, contadorCursos = 0, contadorIdiomas = 0, contadorReferencias = 0;

  function irAPaso(n) {
    pasoActual = n;
    document.querySelectorAll('.paso-panel').forEach(p => p.classList.toggle('activo', parseInt(p.dataset.panel) === n));
    document.querySelectorAll('.paso-item').forEach(p => {
      const num = parseInt(p.dataset.paso);
      p.classList.toggle('activo', num === n);
      p.querySelector('.paso-check').innerHTML = num < n ? '<i class="mdi mdi-check"></i>' : (num === 7 ? '<i class="mdi mdi-flag-checkered"></i>' : (num + 1));
      p.querySelector('.paso-check').style.background = num < n ? '#5a8a5f' : '';
      p.querySelector('.paso-check').style.borderColor = num < n ? '#5a8a5f' : '';
      if (num < n) p.querySelector('.paso-check').style.color = '#fff';
    });
    document.getElementById('progresoRelleno').style.width = Math.round(((n + 1) / TOTAL_PASOS) * 100) + '%';
    document.getElementById('progresoTexto').textContent = Math.round(((n + 1) / TOTAL_PASOS) * 100) + '%';
    document.getElementById('btnVolver').textContent = n === 0 ? 'Salir a editar servicio' : 'Volver';
    document.getElementById('btnContinuar').textContent = n === TOTAL_PASOS - 1 ? 'Guardar mi CV' : 'Continuar';
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function cambiarPaso(delta) {
    const siguiente = pasoActual + delta;
    if (siguiente < 0) { window.location.href = '{{ ($servicio ?? null) ? "/usuario/misEmpleos/" . $servicio->id . "/editar" : "/usuario/postulantes" }}'; return; }
    if (siguiente >= TOTAL_PASOS) { guardarCv(); return; }
    irAPaso(siguiente);
  }

  document.querySelectorAll('.paso-item').forEach(p => p.addEventListener('click', () => irAPaso(parseInt(p.dataset.paso))));

  function toggleAdicional(el) {
    const cuerpo = document.getElementById('adicionalCuerpo');
    const abierto = cuerpo.style.display !== 'none';
    cuerpo.style.display = abierto ? 'none' : 'block';
    el.querySelector('i').style.transform = abierto ? 'rotate(0deg)' : 'rotate(180deg)';
  }

  function actualizarContador(el) {
    document.getElementById('contadorResumen').textContent = el.value.length;
  }

  // ===== Foto =====
  document.getElementById('inputFoto').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => { document.getElementById('fotoPreview').innerHTML = `<img src="${ev.target.result}">`; actualizarPreview(); };
    reader.readAsDataURL(file);
  });

  // ===== Subir CV ya hecho (alternativa) =====
  document.getElementById('inputArchivoCv').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;
    document.getElementById('archivoNombre').innerHTML = `<i class="mdi mdi-file-check-outline"></i> ${file.name}`;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const fd = new FormData();
    fd.append('archivo', file);
    fetch('{{ ($servicio ?? null) ? "/usuario/misEmpleos/" . $servicio->id . "/cv/subir-archivo" : "/usuario/postulantes/" . $postulacion->id . "/cv/subir-archivo" }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken }, body: fd })
      .then(res => res.json().then(data => ({ ok: res.ok, data })))
      .then(({ ok }) => {
        if (!ok) { mostrarToast('Ocurrió un error al subir el archivo.', true); return; }

        const esPostulacion = !{{ ($servicio ?? null) ? 'true' : 'false' }};
        if (esPostulacion) {
          document.body.innerHTML = '<div style="display:flex; align-items:center; justify-content:center; height:100vh; font-family:Nunito,sans-serif; text-align:center; padding:20px;"><div><i class="mdi mdi-check-decagram" style="font-size:48px; color:#c68f1f;"></i><p style="font-size:16px; color:#241a15; margin-top:12px;">¡CV subido! Ya puedes cerrar esta pestaña.</p></div></div>';
          setTimeout(() => window.close(), 1200);
          return;
        }

        mostrarToast('CV subido. Ya puedes salir, o llenar el formulario también si quieres.');
      })
      .catch(() => mostrarToast('Ocurrió un error de conexión.', true));
  });

  // ===== Tarjetas repetibles: Formación =====
  function agregarFormacion(datos = {}) {
    const i = contadorFormacion++;
    const div = document.createElement('div');
    div.className = 'tarjeta-repetible';
    div.innerHTML = `
      <button type="button" class="btn-quitar" onclick="this.parentElement.remove()"><i class="mdi mdi-close"></i></button>
      <div class="fila-2">
        <div class="campo"><label>Nivel / título</label><input type="text" name="formacion[${i}][titulo]" placeholder="Ej. Bachillerato general" value="${datos.titulo || ''}"></div>
        <div class="campo"><label>Institución</label><input type="text" name="formacion[${i}][institucion]" placeholder="Ej. CBTA No. 92" value="${datos.institucion || ''}"></div>
      </div>
      <div class="campo"><label>Fecha</label><input type="text" name="formacion[${i}][fecha]" placeholder="Ej. 2020 – 2023" value="${datos.fecha || ''}"></div>
    `;
    document.getElementById('listaFormacion').appendChild(div);
  }

  // ===== Tarjetas repetibles: Experiencia =====
  function agregarExperiencia(datos = {}) {
    const i = contadorExperiencia++;
    const div = document.createElement('div');
    div.className = 'tarjeta-repetible';
    div.innerHTML = `
      <button type="button" class="btn-quitar" onclick="this.parentElement.remove()"><i class="mdi mdi-close"></i></button>
      <div class="fila-2">
        <div class="campo"><label>Puesto</label><input type="text" name="experiencia[${i}][puesto]" placeholder="Ej. Auxiliar de tienda" value="${datos.puesto || ''}"></div>
        <div class="campo"><label>Lugar</label><input type="text" name="experiencia[${i}][lugar]" placeholder="Ej. Abarrotes La Esquina" value="${datos.lugar || ''}"></div>
      </div>
      <div class="campo"><label>Fecha</label><input type="text" name="experiencia[${i}][fecha]" placeholder="Ej. 2023 – 2025" value="${datos.fecha || ''}"></div>
      <div class="campo"><label>Descripción (opcional)</label><textarea name="experiencia[${i}][descripcion]" placeholder="¿Qué hacías en este trabajo?">${datos.descripcion || ''}</textarea></div>
    `;
    document.getElementById('listaExperiencia').appendChild(div);
  }

  // ===== Tarjetas repetibles: Cursos =====
  function agregarCurso(datos = {}) {
    const i = contadorCursos++;
    const div = document.createElement('div');
    div.className = 'tarjeta-repetible';
    div.innerHTML = `
      <button type="button" class="btn-quitar" onclick="this.parentElement.remove()"><i class="mdi mdi-close"></i></button>
      <div class="fila-2">
        <div class="campo"><label>Nombre del curso</label><input type="text" name="cursos[${i}][nombre]" placeholder="Ej. Atención al cliente" value="${datos.nombre || ''}"></div>
        <div class="campo"><label>Institución</label><input type="text" name="cursos[${i}][institucion]" placeholder="Ej. DIF Municipal" value="${datos.institucion || ''}"></div>
      </div>
      <div class="campo"><label>Año</label><input type="text" name="cursos[${i}][anio]" placeholder="Ej. 2024" value="${datos.anio || ''}"></div>
    `;
    document.getElementById('listaCursos').appendChild(div);
  }

  // ===== Tarjetas repetibles: Idiomas =====
  function agregarIdioma(datos = {}) {
    const i = contadorIdiomas++;
    const div = document.createElement('div');
    div.className = 'tarjeta-repetible';
    div.innerHTML = `
      <button type="button" class="btn-quitar" onclick="this.parentElement.remove()"><i class="mdi mdi-close"></i></button>
      <div class="fila-2">
        <div class="campo"><label>Idioma</label><input type="text" name="idiomas[${i}][nombre]" placeholder="Ej. Náhuatl" value="${datos.nombre || ''}"></div>
        <div class="campo"><label>Nivel</label>
          <select name="idiomas[${i}][nivel]">
            <option value="">Selecciona</option>
            <option ${datos.nivel === 'Básico' ? 'selected' : ''}>Básico</option>
            <option ${datos.nivel === 'Intermedio' ? 'selected' : ''}>Intermedio</option>
            <option ${datos.nivel === 'Avanzado' ? 'selected' : ''}>Avanzado</option>
            <option ${datos.nivel === 'Nativo' ? 'selected' : ''}>Nativo</option>
          </select>
        </div>
      </div>
    `;
    document.getElementById('listaIdiomas').appendChild(div);
  }

  // ===== Tarjetas repetibles: Referencias =====
  function agregarReferencia(datos = {}) {
    const i = contadorReferencias++;
    const div = document.createElement('div');
    div.className = 'tarjeta-repetible';
    div.innerHTML = `
      <button type="button" class="btn-quitar" onclick="this.parentElement.remove()"><i class="mdi mdi-close"></i></button>
      <div class="fila-2">
        <div class="campo"><label>Nombre</label><input type="text" name="referencias[${i}][nombre]" placeholder="Ej. Rosa Hernández" value="${datos.nombre || ''}"></div>
        <div class="campo"><label>Relación</label><input type="text" name="referencias[${i}][relacion]" placeholder="Ej. Ex-jefa" value="${datos.relacion || ''}"></div>
      </div>
      <div class="campo"><label>Teléfono</label><input type="text" name="referencias[${i}][telefono]" placeholder="Ej. 233 104 5521" value="${datos.telefono || ''}"></div>
    `;
    document.getElementById('listaReferencias').appendChild(div);
  }

  // ===== Tags (Competencias / Intereses) =====
  const tagsGuardados = { habilidades: [], intereses: [] };
  function agregarTag(e, campo) {
    if (e.key !== 'Enter') return;
    e.preventDefault();
    const valor = e.target.value.trim();
    if (!valor) return;
    tagsGuardados[campo].push(valor);
    e.target.value = '';
    pintarTags(campo);
  }
  function quitarTag(campo, idx) {
    tagsGuardados[campo].splice(idx, 1);
    pintarTags(campo);
  }
  function pintarTags(campo) {
    const wrap = document.getElementById(campo === 'habilidades' ? 'wrapHabilidades' : 'wrapIntereses');
    const input = wrap.querySelector('input');
    wrap.querySelectorAll('.tag-chip').forEach(el => el.remove());
    wrap.querySelectorAll('input[type=hidden]').forEach(el => el.remove());
    tagsGuardados[campo].forEach((val, idx) => {
      const chip = document.createElement('span');
      chip.className = 'tag-chip';
      chip.innerHTML = `${val} <button type="button" onclick="quitarTag('${campo}', ${idx})"><i class="mdi mdi-close"></i></button>`;
      wrap.insertBefore(chip, input);

      const hidden = document.createElement('input');
      hidden.type = 'hidden';
      hidden.name = `${campo}[]`;
      hidden.value = val;
      wrap.appendChild(hidden);
    });
    actualizarPreview();
  }

  // ===== VISTA PREVIA EN MINIATURA (se actualiza mientras escribes) =====
  function leerCampo(name) {
    const el = document.querySelector(`[name="${name}"]`);
    return el ? el.value.trim() : '';
  }

  function leerFilas(contenedorId, campos) {
    const filas = [];
    document.querySelectorAll(`#${contenedorId} .tarjeta-repetible`).forEach(tarjeta => {
      const fila = {};
      let tieneAlgo = false;
      campos.forEach(campo => {
        const input = tarjeta.querySelector(`[name$="[${campo}]"]`);
        fila[campo] = input ? input.value.trim() : '';
        if (fila[campo]) tieneAlgo = true;
      });
      if (tieneAlgo) filas.push(fila);
    });
    return filas;
  }

  function iniciales(nombre, apellidos) {
    const partes = `${nombre} ${apellidos}`.trim().split(' ').filter(Boolean);
    return partes.slice(0, 2).map(p => p[0].toUpperCase()).join('') || '?';
  }

  function actualizarPreview() {
    const nombre = leerCampo('nombre'), apellidos = leerCampo('apellidos'), titulo = leerCampo('titulo');
    const hoja = document.getElementById('previewHoja');

    if (!nombre && !apellidos && !leerCampo('telefono') && !leerCampo('correo')) {
      hoja.innerHTML = `<div class="preview-vacio"><i class="mdi mdi-file-account-outline"></i>Empieza a llenar el formulario y aquí vas viendo cómo se va armando tu CV</div>`;
      return;
    }

    const fotoImg = document.querySelector('#fotoPreview img');
    const fotoHtml = fotoImg ? `<img src="${fotoImg.src}">` : iniciales(nombre, apellidos);

    const formacion = leerFilas('listaFormacion', ['titulo', 'institucion', 'fecha']);
    const experiencia = leerFilas('listaExperiencia', ['puesto', 'lugar', 'fecha', 'descripcion']);
    const cursos = leerFilas('listaCursos', ['nombre', 'institucion', 'anio']);
    const idiomas = leerFilas('listaIdiomas', ['nombre', 'nivel']);
    const referencias = leerFilas('listaReferencias', ['nombre', 'relacion', 'telefono']);

    let html = `
      <div class="preview-encabezado">
        <div class="preview-foto">${fotoHtml}</div>
        <div class="preview-nombre">${nombre || 'Tu nombre'} ${apellidos}${titulo ? `<small>${titulo}</small>` : ''}${UBICACION_USUARIO ? `<small>📍 ${UBICACION_USUARIO}</small>` : ''}</div>
      </div>
      <div class="preview-contacto">
        ${leerCampo('telefono') ? `<span>${leerCampo('telefono')}</span>` : ''}
        ${leerCampo('correo') ? `<span>${leerCampo('correo')}</span>` : ''}
      </div>
      <div class="preview-cuerpo">`;

    if (leerCampo('resumen')) html += `<p class="preview-resumen">${leerCampo('resumen')}</p>`;

    const seccionItems = (titulo, items, render) => {
      if (!items.length) return '';
      return `<div class="preview-sec"><div class="preview-sec-titulo">${titulo}</div>${items.map(render).join('')}</div>`;
    };

    html += seccionItems('Formación', formacion, f => `<div class="preview-item"><div class="preview-item-t">${f.titulo}</div><div class="preview-item-s">${f.institucion}${f.fecha ? ' · ' + f.fecha : ''}</div></div>`);
    html += seccionItems('Experiencia', experiencia, e => `<div class="preview-item"><div class="preview-item-t">${e.puesto}</div><div class="preview-item-s">${e.lugar}${e.fecha ? ' · ' + e.fecha : ''}</div></div>`);
    html += seccionItems('Cursos', cursos, c => `<div class="preview-item"><div class="preview-item-t">${c.nombre}</div><div class="preview-item-s">${c.institucion}${c.anio ? ' · ' + c.anio : ''}</div></div>`);

    if (tagsGuardados.habilidades.length) {
      html += `<div class="preview-sec"><div class="preview-sec-titulo">Competencias</div><div class="preview-tags">${tagsGuardados.habilidades.map(h => `<span class="preview-tag">${h}</span>`).join('')}</div></div>`;
    }

    html += seccionItems('Idiomas', idiomas, i => `<div class="preview-item"><div class="preview-item-t">${i.nombre}</div><div class="preview-item-s">${i.nivel}</div></div>`);

    if (tagsGuardados.intereses.length) {
      html += `<div class="preview-sec"><div class="preview-sec-titulo">Intereses</div><div class="preview-tags">${tagsGuardados.intereses.map(h => `<span class="preview-tag">${h}</span>`).join('')}</div></div>`;
    }

    html += seccionItems('Referencias', referencias, r => `<div class="preview-item"><div class="preview-item-t">${r.nombre}</div><div class="preview-item-s">${r.relacion}${r.telefono ? ' · ' + r.telefono : ''}</div></div>`);

    html += `</div>`;
    hoja.innerHTML = html;
  }

  // Se actualiza con cualquier cambio en el formulario (escribir, agregar fila, quitar fila, etc.)
  document.getElementById('formCv').addEventListener('input', actualizarPreview);
  document.getElementById('formCv').addEventListener('change', actualizarPreview);
  document.getElementById('formCv').addEventListener('click', e => {
    if (e.target.closest('.btn-quitar') || e.target.closest('.btn-agregar')) {
      setTimeout(actualizarPreview, 0);
    }
  });

  // ===== Guardar =====
  function mostrarToast(msg, error = false) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className = 'toast' + (error ? ' error' : '');
    t.style.display = 'block';
    setTimeout(() => t.style.display = 'none', 3500);
  }

  function guardarCv() {
    const form = document.getElementById('formCv');
    const formData = new FormData(form);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const btn = document.getElementById('btnContinuar');
    btn.disabled = true;
    btn.textContent = 'Guardando...';
    const esPostulacion = !{{ ($servicio ?? null) ? 'true' : 'false' }};

    fetch('{{ ($servicio ?? null) ? "/usuario/misEmpleos/" . $servicio->id . "/cv/crear" : "/usuario/postulantes/" . $postulacion->id . "/cv/crear" }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken }, body: formData })
      .then(res => res.json().then(data => ({ ok: res.ok, data })))
      .then(({ ok, data }) => {
        if (!ok) { mostrarToast('Ocurrió un error al guardar. Revisa los campos.', true); btn.disabled = false; btn.textContent = 'Guardar mi CV'; return; }

        // Si esto es el CV de una postulación (se creó/subió desde el modal
        // de "Postularme" en otra pestaña), avisamos que ya puede cerrar
        // esta -- la otra pestaña consulta la base de datos directo, no
        // necesita ningún aviso especial de esta.
        if (esPostulacion) {
          document.body.innerHTML = '<div style="display:flex; align-items:center; justify-content:center; height:100vh; font-family:Nunito,sans-serif; text-align:center; padding:20px;"><div><i class="mdi mdi-check-decagram" style="font-size:48px; color:#c68f1f;"></i><p style="font-size:16px; color:#241a15; margin-top:12px;">¡CV guardado! Ya puedes cerrar esta pestaña.</p></div></div>';
          setTimeout(() => window.close(), 1200);
          return;
        }

        window.location.href = data.redirect;
      })
      .catch(() => { mostrarToast('Ocurrió un error de conexión.', true); btn.disabled = false; btn.textContent = 'Guardar mi CV'; });
  }

  // ===== Si ya existe un CV, precargar los datos =====
  @if ($curriculum)
    document.querySelector('[name=nombre]').value = @json($curriculum->nombre ?? '');
    document.querySelector('[name=apellidos]').value = @json($curriculum->apellidos ?? '');
    document.querySelector('[name=titulo]').value = @json($curriculum->titulo ?? '');
    document.querySelector('[name=telefono]').value = @json($curriculum->telefono ?? '');
    document.querySelector('[name=correo]').value = @json($curriculum->correo ?? '');
    document.querySelector('[name=resumen]').value = @json($curriculum->resumen ?? '');
    actualizarContador(document.querySelector('[name=resumen]'));
    document.querySelector('[name=direccion]').value = @json($curriculum->direccion ?? '');
    document.querySelector('[name=fecha_nacimiento]').value = @json($curriculum->fecha_nacimiento ? $curriculum->fecha_nacimiento->format('Y-m-d') : '');
    document.querySelector('[name=nacionalidad]').value = @json($curriculum->nacionalidad ?? '');
    if (@json($curriculum->estado_civil ?? '')) document.querySelector('[name=estado_civil]').value = @json($curriculum->estado_civil ?? '');
    if (@json($curriculum->tipo_documento ?? '')) document.querySelector('[name=tipo_documento]').value = @json($curriculum->tipo_documento ?? '');
    document.querySelector('[name=detalles_documento]').value = @json($curriculum->detalles_documento ?? '');
    @if ($curriculum->foto)
      document.getElementById('fotoPreview').innerHTML = '<img src="{{ $curriculum->foto }}">';
    @endif
    @if ($curriculum->archivo_subido)
      document.getElementById('archivoNombre').innerHTML = '<i class="mdi mdi-file-check-outline"></i> Ya tienes un archivo subido';
    @endif

    @foreach (($curriculum->formacion ?? []) as $f)
      agregarFormacion(@json($f));
    @endforeach
    @foreach (($curriculum->experiencia ?? []) as $e)
      agregarExperiencia(@json($e));
    @endforeach
    @foreach (($curriculum->cursos ?? []) as $c)
      agregarCurso(@json($c));
    @endforeach
    @foreach (($curriculum->idiomas ?? []) as $idm)
      agregarIdioma(@json($idm));
    @endforeach
    @foreach (($curriculum->referencias ?? []) as $ref)
      agregarReferencia(@json($ref));
    @endforeach
    @foreach (($curriculum->habilidades ?? []) as $h)
      tagsGuardados.habilidades.push(@json($h));
    @endforeach
    @foreach (($curriculum->intereses ?? []) as $int)
      tagsGuardados.intereses.push(@json($int));
    @endforeach
    pintarTags('habilidades');
    pintarTags('intereses');
  @endif

  actualizarPreview();
</script>

</body>
</html>
