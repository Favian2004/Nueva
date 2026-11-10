{{-- Formulario "Solicitud de servicio" (compartido: ver servicio y Mis solicitudes).
     Se abre con: abrirSolicitudServicio({ servicioId, titulo, previa?, reenvio?, despues? }) --}}
@once
@php
  $yoForm = auth()->user()->loadMissing('localidad.municipio');
  $lugarForm = $yoForm->localidad
    ? $yoForm->localidad->nombre . ($yoForm->localidad->municipio ? ', ' . $yoForm->localidad->municipio->nombre : '')
    : '';
@endphp
<style>
    .sol-fondo { position: fixed; inset: 0; z-index: 1500; display: none; align-items: center; justify-content: center; padding: 16px; background: rgba(15,23,42,.6); }
    .sol-fondo.abierto { display: flex; }
    .sol-caja { width: 100%; max-width: 520px; max-height: 92vh; overflow-y: auto; background: #fff; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,.35); }
    .sol-cab { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; padding: 18px 20px 12px; border-bottom: 1px solid #f1f1f1; }
    .sol-cab h3 { margin: 0; font-size: 1.15rem; font-weight: 800; color: #1f2937; }
    .sol-cab p { margin: 2px 0 0; font-size: .85rem; color: #6b7280; }
    .sol-x { flex: 0 0 34px; width: 34px; height: 34px; border: 0; border-radius: 50%; background: #f3f4f6; color: #374151; font-size: 1.1rem; cursor: pointer; }
    .sol-cuerpo { padding: 16px 20px 20px; }
    .sol-campo { margin-bottom: 14px; }
    .sol-campo label { display: block; margin-bottom: 5px; font-size: .82rem; font-weight: 700; color: #374151; }
    .sol-campo input[type=text], .sol-campo input[type=date], .sol-campo select, .sol-campo textarea { width: 100%; padding: 10px 12px; border: 1.5px solid #e5e7eb; border-radius: 10px; font: inherit; font-size: .92rem; background: #fff; box-sizing: border-box; }
    .sol-campo input[readonly] { background: #f9fafb; color: #6b7280; }
    .sol-campo textarea { min-height: 96px; resize: vertical; }
    .sol-campo input:focus, .sol-campo select:focus, .sol-campo textarea:focus { outline: none; border-color: #ff7a18; box-shadow: 0 0 0 3px rgba(255,122,24,.15); }
    .sol-ayuda { margin-top: 4px; font-size: .76rem; color: #9ca3af; }
    .sol-contador { text-align: right; }
    .sol-error { display: none; margin-bottom: 12px; padding: 9px 12px; border-radius: 10px; background: #fef2f2; color: #b91c1c; font-size: .85rem; }
    .sol-aviso { margin-bottom: 14px; padding: 10px 12px; border-radius: 10px; background: #fff8f1; color: #92400e; font-size: .8rem; line-height: 1.45; }
    .sol-aviso a { color: #c2410c; font-weight: 700; }
    .sol-fila2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .sol-botones { display: flex; gap: 10px; margin-top: 6px; }
    .sol-botones button { flex: 1; padding: 11px; border: 0; border-radius: 40px; font-weight: 700; font-size: .95rem; cursor: pointer; }
    .sol-enviar { background: linear-gradient(45deg, #ff7a18, #ffb347); color: #fff; }
    .sol-enviar[disabled] { opacity: .6; cursor: default; }
    .sol-cancelar { background: #f3f4f6; color: #374151; }
    @media (max-width: 480px) { .sol-fila2 { grid-template-columns: 1fr; } .sol-fondo { align-items: flex-end; padding: 0; } .sol-caja { border-radius: 20px 20px 0 0; max-height: 94vh; } }
</style>

<div class="sol-fondo" id="solFondo" role="dialog" aria-modal="true" aria-labelledby="solTitulo">
  <form class="sol-caja" id="solForm" novalidate>
    <div class="sol-cab">
      <div>
        <h3 id="solTitulo">Solicitud de servicio</h3>
        <p id="solServicioTitulo"></p>
      </div>
      <button type="button" class="sol-x" id="solX" aria-label="Cerrar">&#10005;</button>
    </div>
    <div class="sol-cuerpo">
      <div class="sol-aviso" id="solAvisoReenvio" style="display:none; background:#eff6ff; color:#1e40af;"><i class="mdi mdi-pencil-outline"></i> Estos son los datos de tu solicitud anterior. Corrige lo que quieras y envíala de nuevo.</div>
      <div class="sol-aviso"><i class="mdi mdi-shield-lock-outline"></i> Cuéntale al trabajador qué necesitas. Tu teléfono <strong>no se comparte</strong> hasta que él acepte tu solicitud.</div>
      <div class="sol-error" id="solError"></div>

      <div class="sol-fila2">
        <div class="sol-campo">
          <label>Nombre</label>
          <input type="text" value="{{ $yoForm->nombre }}" readonly>
        </div>
        <div class="sol-campo">
          <label>Localidad</label>
          <input type="text" value="{{ $lugarForm ?: 'Sin localidad en tu perfil' }}" readonly>
        </div>
      </div>

      <div class="sol-campo">
        <label for="solNecesidad">¿Qué necesitas?</label>
        <textarea id="solNecesidad" maxlength="500" placeholder="Ej. Necesito limpiar y sembrar un terreno pequeño de unos 200 m²."></textarea>
        <div class="sol-ayuda sol-contador"><span id="solContador">0</span>/500</div>
      </div>

      <div class="sol-fila2">
        <div class="sol-campo">
          <label for="solFecha">¿Para cuándo?</label>
          <select id="solFecha">
            @foreach (\App\Models\Contratacion::FECHAS as $clave => $texto)
              <option value="{{ $clave }}">{{ $texto }}</option>
            @endforeach
          </select>
        </div>
        <div class="sol-campo" id="solFechaEspecificaBox" style="display:none;">
          <label for="solFechaEspecifica">Fecha</label>
          <input type="date" id="solFechaEspecifica" min="{{ now()->format('Y-m-d') }}">
        </div>
      </div>

      <div class="sol-fila2">
        <div class="sol-campo">
          <label for="solPresupuesto">Presupuesto</label>
          <select id="solPresupuesto">
            @foreach (\App\Models\Contratacion::PRESUPUESTOS as $clave => $texto)
              <option value="{{ $clave }}">{{ $texto }}</option>
            @endforeach
          </select>
        </div>
        <div class="sol-campo">
          <label for="solContacto">Contacto preferido</label>
          <select id="solContacto">
            @foreach (\App\Models\Contratacion::CONTACTOS as $clave => $texto)
              <option value="{{ $clave }}">{{ $texto }}</option>
            @endforeach
          </select>
        </div>
      </div>

      @unless ($yoForm->telefono)
        <div class="sol-aviso"><i class="mdi mdi-phone-alert-outline"></i> No tienes teléfono en tu perfil. <a href="/usuario/profile" target="_blank">Agrégalo</a> para que el trabajador pueda contactarte cuando te acepte.</div>
      @endunless

      <div class="sol-botones">
        <button type="button" class="sol-cancelar" id="solCancelar">Cancelar</button>
        <button type="submit" class="sol-enviar" id="solEnviar">Enviar solicitud</button>
      </div>
    </div>
  </form>
</div>

<script>
(function () {
  const $ = (id) => document.getElementById(id);
  const fondo = $('solFondo');
  let actual = null; // { servicioId, despues }

  const cerrar = () => { fondo.classList.remove('abierto'); document.body.style.overflow = ''; };
  const mostrarError = (msg) => { const e = $('solError'); e.textContent = msg; e.style.display = msg ? 'block' : 'none'; if (msg) e.scrollIntoView({ block: 'nearest' }); };
  const actualizarFecha = () => { $('solFechaEspecificaBox').style.display = $('solFecha').value === 'fecha' ? '' : 'none'; };
  const actualizarContador = () => { $('solContador').textContent = $('solNecesidad').value.length; };

  window.abrirSolicitudServicio = function (opts) {
    const p = opts.previa || {};
    actual = { servicioId: opts.servicioId, despues: opts.despues || null };
    $('solServicioTitulo').textContent = opts.titulo || '';
    $('solTitulo').textContent = opts.reenvio ? 'Volver a enviar solicitud' : 'Solicitud de servicio';
    $('solEnviar').textContent = opts.reenvio ? 'Enviar de nuevo' : 'Enviar solicitud';
    $('solAvisoReenvio').style.display = opts.previa ? 'block' : 'none';
    $('solNecesidad').value = p.necesidad || '';
    $('solFecha').value = p.fecha || 'lo_antes_posible';
    $('solFechaEspecifica').value = p.fechaEspecifica || '';
    $('solPresupuesto').value = p.presupuesto || 'cotizacion';
    $('solContacto').value = p.contacto || 'whatsapp';
    mostrarError('');
    actualizarFecha();
    actualizarContador();
    $('solEnviar').disabled = false;
    fondo.classList.add('abierto');
    document.body.style.overflow = 'hidden';
    setTimeout(() => $('solNecesidad').focus(), 50);
  };

  $('solX').addEventListener('click', cerrar);
  $('solCancelar').addEventListener('click', cerrar);
  fondo.addEventListener('click', (e) => { if (e.target === fondo) cerrar(); });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && fondo.classList.contains('abierto')) cerrar(); });
  $('solNecesidad').addEventListener('input', actualizarContador);
  $('solFecha').addEventListener('change', actualizarFecha);

  $('solForm').addEventListener('submit', function (ev) {
    ev.preventDefault();
    if (!actual) return;
    mostrarError('');
    const necesidad = $('solNecesidad').value.trim();
    if (necesidad.length < 10) { mostrarError('Describe un poco más lo que necesitas (mínimo 10 letras).'); $('solNecesidad').focus(); return; }
    if ($('solFecha').value === 'fecha' && !$('solFechaEspecifica').value) { mostrarError('Elige la fecha en que lo necesitas.'); return; }

    const boton = $('solEnviar');
    const textoBoton = boton.textContent;
    boton.disabled = true; boton.textContent = 'Enviando…';
    const csrf = document.querySelector('meta[name="csrf-token"]');
    fetch('/usuario/ver_servicio/' + actual.servicioId + '/solicitar', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf ? csrf.content : '' },
      body: JSON.stringify({
        necesidad: necesidad,
        fecha_necesaria: $('solFecha').value,
        fecha_especifica: $('solFecha').value === 'fecha' ? $('solFechaEspecifica').value : null,
        presupuesto: $('solPresupuesto').value,
        contacto_preferido: $('solContacto').value,
      }),
    })
      .then(res => res.json().then(data => ({ ok: res.ok, data })))
      .then(({ ok, data }) => {
        if (!ok) {
          const primero = data.errors ? Object.values(data.errors)[0][0] : null;
          mostrarError(primero || data.error || data.message || 'Ocurrió un error al solicitar el servicio.');
          boton.disabled = false; boton.textContent = textoBoton;
          return;
        }
        cerrar();
        alert('✅ ¡Solicitud enviada! El trabajador la revisará y, si te acepta, podrás ver su contacto.');
        if (actual.despues) { window.location.href = actual.despues; } else { window.location.reload(); }
      })
      .catch(() => { mostrarError('Ocurrió un error de conexión. Intenta de nuevo.'); boton.disabled = false; boton.textContent = textoBoton; });
  });
})();
</script>
@endonce
