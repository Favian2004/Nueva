<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>CV de {{ $curriculum->nombre }} {{ $curriculum->apellidos }} · SINTECZATE</title>
<link rel="icon" type="img/" href="{{ asset('assets/usuario/img/icono.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Fraunces:wght@500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
<style>
  :root {
    --vino: #6b1021;
    --vino-claro: #8f1d2f;
    --dorado: #c68f1f;
    --dorado-claro: #f3d68a;
    --papel: #fdf8f0;
    --papel-oscuro: #f3ead9;
    --texto: #241a15;
    --texto-suave: #6b5d52;
  }

  * { box-sizing: border-box; }

  body {
    margin: 0;
    background: #e9e2d3;
    font-family: 'Nunito', sans-serif;
    color: var(--texto);
    padding: 40px 16px;
  }

  .hoja {
    max-width: 720px;
    margin: 0 auto;
    background: var(--papel);
    border-radius: 4px;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(107, 16, 33, 0.18);
  }

  .encabezado {
    background: linear-gradient(135deg, var(--vino) 0%, var(--vino-claro) 100%);
    padding: 36px 40px 30px;
    display: flex;
    gap: 22px;
    align-items: center;
    position: relative;
    overflow: hidden;
  }

  .encabezado::before {
    content: '';
    position: absolute;
    top: 0; right: 0; bottom: 0;
    width: 130px;
    background-image: repeating-linear-gradient(135deg, transparent 0 18px, rgba(255,255,255,0.045) 18px 19px);
  }

  .foto {
    width: 84px;
    height: 84px;
    border-radius: 50%;
    background: var(--dorado-claro);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Fraunces', serif;
    font-size: 28px;
    font-weight: 600;
    color: var(--vino);
    flex-shrink: 0;
    border: 3px solid rgba(255,255,255,0.35);
    position: relative;
    z-index: 1;
    overflow: hidden;
  }

  .foto img { width: 100%; height: 100%; object-fit: cover; }

  .id-datos { color: #fff; position: relative; z-index: 1; }
  .id-datos h1 { font-family: 'Fraunces', serif; font-size: 30px; font-weight: 600; margin: 0 0 4px; letter-spacing: 0.2px; }
  .id-rol { font-size: 14.5px; opacity: 0.92; margin: 0 0 2px; }
  .id-ubicacion { font-size: 12.5px; opacity: 0.82; margin: 0 0 10px; display: flex; align-items: center; gap: 4px; }
  .id-ubicacion i { font-size: 13px; }

  .id-verificado {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,0.16); border: 1px solid rgba(255,255,255,0.3);
    padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;
  }
  .id-verificado i { font-size: 14px; color: #a8e6b0; }

  .contacto {
    background: var(--papel-oscuro); padding: 14px 40px; display: flex; gap: 26px;
    flex-wrap: wrap; font-size: 13px; color: var(--texto-suave); border-bottom: 1px solid #e4d8c2;
  }
  .contacto span { display: flex; align-items: center; gap: 6px; }
  .contacto i { color: var(--dorado); font-size: 15px; }

  .cuerpo { padding: 32px 40px 40px; }

  .resumen {
    font-size: 14.5px; line-height: 1.65; color: var(--texto); font-style: italic;
    border-left: 3px solid var(--dorado); padding-left: 16px; margin: 0 0 32px;
  }

  .seccion { margin-bottom: 28px; }
  .seccion:last-child { margin-bottom: 0; }
  .seccion-titulo { display: flex; align-items: baseline; gap: 10px; margin: 0 0 14px; }
  .seccion-titulo i { font-size: 17px; color: var(--dorado); }
  .seccion-titulo h2 { font-family: 'Fraunces', serif; font-size: 16.5px; font-weight: 600; color: var(--vino); margin: 0; }
  .seccion-titulo .linea { flex: 1; height: 1px; background: linear-gradient(90deg, #e4d8c2, transparent); }

  .item { margin-bottom: 16px; }
  .item:last-child { margin-bottom: 0; }
  .item-top { display: flex; justify-content: space-between; align-items: baseline; gap: 12px; flex-wrap: wrap; }
  .item-titulo { font-weight: 700; font-size: 14.5px; color: var(--texto); }
  .item-lugar { font-size: 13px; color: var(--texto-suave); margin-top: 1px; }
  .item-fecha { font-size: 12px; color: var(--texto-suave); white-space: nowrap; background: var(--papel-oscuro); padding: 2px 10px; border-radius: 20px; }
  .item-desc { font-size: 13px; color: var(--texto-suave); line-height: 1.55; margin-top: 6px; }

  .habilidades { display: flex; flex-wrap: wrap; gap: 8px; }
  .habilidad { font-size: 12.5px; font-weight: 700; color: var(--vino); background: #f7e9c9; border: 1px solid var(--dorado-claro); padding: 5px 13px; border-radius: 20px; }

  .idiomas { display: flex; flex-direction: column; gap: 10px; }
  .idioma-item { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
  .idioma-nombre { font-weight: 700; font-size: 13.5px; color: var(--texto); min-width: 90px; }
  .idioma-nivel { display: flex; align-items: center; gap: 5px; }
  .idioma-punto { width: 9px; height: 9px; border-radius: 50%; background: #e4d8c2; }
  .idioma-punto.llena { background: var(--dorado); }
  .idioma-texto { font-size: 12px; color: var(--texto-suave); margin-left: 6px; }

  .referencias { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
  .referencia { background: var(--papel-oscuro); border-radius: 10px; padding: 12px 14px; }
  .referencia .nombre { font-weight: 700; font-size: 13.5px; }
  .referencia .rel { font-size: 12px; color: var(--texto-suave); margin: 2px 0 6px; }
  .referencia .tel { font-size: 12.5px; color: var(--vino); font-weight: 600; }

  .info-extra { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 12px; }
  .info-extra-item { display: flex; flex-direction: column; gap: 2px; background: var(--papel-oscuro); border-radius: 10px; padding: 9px 13px; }
  .info-extra-label { font-size: 11.5px; color: var(--texto-suave); display: flex; align-items: center; gap: 5px; }
  .info-extra-label i { color: var(--dorado); font-size: 13px; }
  .info-extra-valor { font-size: 13.5px; font-weight: 700; color: var(--texto); }

  .info-extra-nota {
    display: flex; align-items: flex-start; gap: 8px; font-size: 12px; line-height: 1.5;
    color: var(--texto-suave); background: #faf1dc; border-left: 3px solid var(--dorado);
    border-radius: 6px; padding: 10px 12px; margin: 0;
  }
  .info-extra-nota i { color: var(--dorado); font-size: 15px; flex-shrink: 0; margin-top: 1px; }

  .barra-acciones { max-width: 720px; margin: 0 auto 18px; display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }

  .btn-descargar, .btn-editar {
    display: inline-flex; align-items: center; gap: 8px; border: none; padding: 11px 22px;
    border-radius: 10px; font-family: 'Nunito', sans-serif; font-weight: 700; font-size: 13.5px;
    cursor: pointer; text-decoration: none;
  }
  .btn-descargar { background: var(--vino); color: #fff; box-shadow: 0 6px 16px rgba(107,16,33,0.25); }
  .btn-editar { background: #fff; color: var(--vino); border: 1.5px solid var(--dorado); }

  .sin-cv {
    max-width: 500px; margin: 80px auto; text-align: center; background: var(--papel);
    border-radius: 16px; padding: 40px 30px; box-shadow: 0 20px 50px rgba(107,16,33,0.12);
  }
  .sin-cv i { font-size: 46px; color: var(--dorado); }
  .sin-cv h2 { font-family: 'Fraunces', serif; color: var(--vino); }
  .sin-cv p { color: var(--texto-suave); font-size: 14px; }
  .sin-cv a { display: inline-block; margin-top: 14px; background: var(--vino); color: #fff; text-decoration: none; padding: 12px 26px; border-radius: 10px; font-weight: 700; }

  @media (max-width: 640px) {
    .encabezado { padding: 28px 22px 24px; }
    .contacto, .cuerpo { padding-left: 22px; padding-right: 22px; }
    .referencias, .info-extra { grid-template-columns: 1fr; }
    .barra-acciones { padding: 0 4px; }
  }

  @media print {
    body { background: #fff; padding: 0; }
    .barra-acciones { display: none; }
    .hoja { box-shadow: none; }
  }
</style>
</head>
<body>

@if ($curriculum->archivo_subido && !$curriculum->tieneContenido)

  {{-- Solo subió un archivo, no usó el formulario guiado: mostramos el archivo directo --}}
  <div class="barra-acciones">
    <a href="{{ $curriculum->archivo_subido }}" target="_blank" class="btn-descargar"><i class="mdi mdi-file-pdf-box"></i> Ver / descargar CV</a>
    @if ($esPropio)
      <a href="{{ ($servicio ?? null) ? '/usuario/misEmpleos/' . $servicio->id . '/cv/crear' : '/usuario/postulantes/' . $postulacion->id . '/cv/crear' }}" class="btn-editar"><i class="mdi mdi-pencil-outline"></i> Reemplazar / crear con formato</a>
    @elseif ($postulacion ?? null)
      <a href="/usuario/postulantes" class="btn-editar"><i class="mdi mdi-arrow-left"></i> Volver a Postulantes</a>
    @endif
  </div>
  <div class="sin-cv">
    <i class="mdi mdi-file-pdf-box"></i>
    <h2>CV subido como archivo</h2>
    <p>Este usuario subió su CV ya hecho, en vez de usar el formulario. Dale clic arriba para verlo.</p>
  </div>

@else

  <div class="barra-acciones">
    <button class="btn-descargar" onclick="window.print()"><i class="mdi mdi-download"></i> Descargar CV</button>
    @if ($esPropio)
      <a href="{{ ($servicio ?? null) ? '/usuario/misEmpleos/' . $servicio->id . '/cv/crear' : '/usuario/postulantes/' . $postulacion->id . '/cv/crear' }}" class="btn-editar"><i class="mdi mdi-pencil-outline"></i> Editar mi CV</a>
    @elseif ($postulacion ?? null)
      <a href="/usuario/postulantes" class="btn-editar"><i class="mdi mdi-arrow-left"></i> Volver a Postulantes</a>
    @endif
  </div>

  <div class="hoja">

    <div class="encabezado">
      <div class="foto">
        @if ($curriculum->foto)
          <img src="{{ $curriculum->foto }}" alt="Foto">
        @else
          {{ collect(explode(' ', trim($curriculum->nombre . ' ' . $curriculum->apellidos)))->map(fn($p) => mb_strtoupper(mb_substr($p, 0, 1)))->take(2)->implode('') }}
        @endif
      </div>
      <div class="id-datos">
        <h1>{{ $curriculum->nombre }} {{ $curriculum->apellidos }}</h1>
        @if ($curriculum->titulo)
          <p class="id-rol">{{ $curriculum->titulo }}</p>
        @endif
        @if ($curriculum->usuario && $curriculum->usuario->localidad)
          <p class="id-ubicacion">
            <i class="mdi mdi-map-marker-outline"></i>
            {{ $curriculum->usuario->localidad->nombre }}{{ $curriculum->usuario->localidad->municipio ? ', ' . $curriculum->usuario->localidad->municipio->nombre : '' }}
          </p>
        @endif
        @if ($curriculum->usuario && $curriculum->usuario->verificacion_estado === 'aprobado')
          <span class="id-verificado"><i class="mdi mdi-check-decagram"></i> Identidad verificada</span>
        @endif
      </div>
    </div>

    <div class="contacto">
      @if ($curriculum->telefono)
        <span><i class="mdi mdi-phone"></i> {{ $curriculum->telefono }}</span>
      @endif
      @if ($curriculum->correo)
        <span><i class="mdi mdi-email-outline"></i> {{ $curriculum->correo }}</span>
      @endif
    </div>

    <div class="cuerpo">

      @if ($curriculum->resumen)
        <p class="resumen">{{ $curriculum->resumen }}</p>
      @endif

      @if (!empty($curriculum->formacion))
        <div class="seccion">
          <div class="seccion-titulo"><i class="mdi mdi-school-outline"></i><h2>Formación</h2><div class="linea"></div></div>
          @foreach ($curriculum->formacion as $f)
            <div class="item">
              <div class="item-top">
                <div>
                  <div class="item-titulo">{{ $f['titulo'] ?? '' }}</div>
                  @if (!empty($f['institucion']))<div class="item-lugar">{{ $f['institucion'] }}</div>@endif
                </div>
                @if (!empty($f['fecha']))<div class="item-fecha">{{ $f['fecha'] }}</div>@endif
              </div>
            </div>
          @endforeach
        </div>
      @endif

      @if (!empty($curriculum->experiencia))
        <div class="seccion">
          <div class="seccion-titulo"><i class="mdi mdi-briefcase-outline"></i><h2>Experiencia laboral</h2><div class="linea"></div></div>
          @foreach ($curriculum->experiencia as $e)
            <div class="item">
              <div class="item-top">
                <div>
                  <div class="item-titulo">{{ $e['puesto'] ?? '' }}</div>
                  @if (!empty($e['lugar']))<div class="item-lugar">{{ $e['lugar'] }}</div>@endif
                </div>
                @if (!empty($e['fecha']))<div class="item-fecha">{{ $e['fecha'] }}</div>@endif
              </div>
              @if (!empty($e['descripcion']))<p class="item-desc">{{ $e['descripcion'] }}</p>@endif
            </div>
          @endforeach
        </div>
      @endif

      @if (!empty($curriculum->cursos))
        <div class="seccion">
          <div class="seccion-titulo"><i class="mdi mdi-certificate-outline"></i><h2>Cursos y certificaciones</h2><div class="linea"></div></div>
          @foreach ($curriculum->cursos as $c)
            <div class="item">
              <div class="item-top">
                <div>
                  <div class="item-titulo">{{ $c['nombre'] ?? '' }}</div>
                  @if (!empty($c['institucion']))<div class="item-lugar">{{ $c['institucion'] }}</div>@endif
                </div>
                @if (!empty($c['anio']))<div class="item-fecha">{{ $c['anio'] }}</div>@endif
              </div>
            </div>
          @endforeach
        </div>
      @endif

      @if (!empty($curriculum->habilidades))
        <div class="seccion">
          <div class="seccion-titulo"><i class="mdi mdi-lightning-bolt-outline"></i><h2>Competencias</h2><div class="linea"></div></div>
          <div class="habilidades">
            @foreach ($curriculum->habilidades as $h)
              <span class="habilidad">{{ $h }}</span>
            @endforeach
          </div>
        </div>
      @endif

      @if (!empty($curriculum->idiomas))
        <div class="seccion">
          <div class="seccion-titulo"><i class="mdi mdi-translate"></i><h2>Idiomas</h2><div class="linea"></div></div>
          <div class="idiomas">
            @foreach ($curriculum->idiomas as $idm)
              @php
                $niveles = ['Básico' => 1, 'Intermedio' => 2, 'Avanzado' => 3, 'Nativo' => 4];
                $llenos = $niveles[$idm['nivel'] ?? ''] ?? 0;
              @endphp
              <div class="idioma-item">
                <span class="idioma-nombre">{{ $idm['nombre'] ?? '' }}</span>
                <span class="idioma-nivel">
                  @for ($p = 1; $p <= 4; $p++)
                    <span class="idioma-punto {{ $p <= $llenos ? 'llena' : '' }}"></span>
                  @endfor
                  @if (!empty($idm['nivel']))<span class="idioma-texto">{{ $idm['nivel'] }}</span>@endif
                </span>
              </div>
            @endforeach
          </div>
        </div>
      @endif

      @if (!empty($curriculum->intereses))
        <div class="seccion">
          <div class="seccion-titulo"><i class="mdi mdi-heart-outline"></i><h2>Intereses</h2><div class="linea"></div></div>
          <div class="habilidades">
            @foreach ($curriculum->intereses as $int)
              <span class="habilidad">{{ $int }}</span>
            @endforeach
          </div>
        </div>
      @endif

      @if (!empty($curriculum->referencias))
        <div class="seccion">
          <div class="seccion-titulo"><i class="mdi mdi-account-voice"></i><h2>Referencias</h2><div class="linea"></div></div>
          <div class="referencias">
            @foreach ($curriculum->referencias as $ref)
              <div class="referencia">
                <div class="nombre">{{ $ref['nombre'] ?? '' }}</div>
                @if (!empty($ref['relacion']))<div class="rel">{{ $ref['relacion'] }}</div>@endif
                @if (!empty($ref['telefono']))<div class="tel">{{ $ref['telefono'] }}</div>@endif
              </div>
            @endforeach
          </div>
        </div>
      @endif

      @php
        $tieneInfoExtra = $curriculum->direccion || $curriculum->fecha_nacimiento || $curriculum->nacionalidad || $curriculum->estado_civil || $curriculum->tipo_documento;
      @endphp

      @if ($tieneInfoExtra)
        <div class="seccion">
          <div class="seccion-titulo"><i class="mdi mdi-information-outline"></i><h2>Información adicional</h2><div class="linea"></div></div>
          <div class="info-extra">
            @if ($curriculum->direccion)
              <div class="info-extra-item"><span class="info-extra-label"><i class="mdi mdi-map-marker-outline"></i> Dirección</span><span class="info-extra-valor">{{ $curriculum->direccion }}</span></div>
            @endif
            @if ($curriculum->fecha_nacimiento)
              <div class="info-extra-item"><span class="info-extra-label"><i class="mdi mdi-cake-variant-outline"></i> Fecha de nacimiento</span><span class="info-extra-valor">{{ $curriculum->fecha_nacimiento->translatedFormat('d \d\e F \d\e Y') }} · {{ $curriculum->fecha_nacimiento->age }} años</span></div>
            @endif
            @if ($curriculum->nacionalidad)
              <div class="info-extra-item"><span class="info-extra-label"><i class="mdi mdi-flag-outline"></i> Nacionalidad</span><span class="info-extra-valor">{{ $curriculum->nacionalidad }}</span></div>
            @endif
            @if ($curriculum->estado_civil)
              <div class="info-extra-item"><span class="info-extra-label"><i class="mdi mdi-heart-outline"></i> Estado civil</span><span class="info-extra-valor">{{ $curriculum->estado_civil }}</span></div>
            @endif
            @if ($curriculum->tipo_documento)
              <div class="info-extra-item"><span class="info-extra-label"><i class="mdi mdi-card-account-details-outline"></i> Tipo de documento</span><span class="info-extra-valor">{{ $curriculum->tipo_documento }}</span></div>
            @endif
            @if ($curriculum->detalles_documento)
              <div class="info-extra-item"><span class="info-extra-label"><i class="mdi mdi-pound"></i> Detalles del documento</span><span class="info-extra-valor">{{ $curriculum->detalles_documento }}</span></div>
            @endif
          </div>
          <p class="info-extra-nota">
            <i class="mdi mdi-shield-alert-outline"></i>
            {{ $curriculum->usuario->nombre ?? 'Esta persona' }} decidió compartir estos datos voluntariamente en su CV.
          </p>
        </div>
      @endif

    </div>
  </div>

@endif

</body>
</html>
