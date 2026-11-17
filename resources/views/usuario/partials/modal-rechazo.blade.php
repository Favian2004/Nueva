{{-- Ventana para rechazar a un postulante y explicarle (opcional) por qué.
     La persona rechazada recibe ese motivo en su campana y lo ve en "Enviadas".
     Uso: abrirRechazo(idPostulacion, 'Nombre Apellido') --}}
<style>
  #rechazoFondo { position: fixed; inset: 0; z-index: 2147481000; display: none; align-items: center; justify-content: center; padding: 16px; background: rgba(20, 6, 10, .55); font-family: inherit; }
  #rechazoFondo.visible { display: flex; }
  #rechazoFondo .rz-caja { background: #fff; border-radius: 18px; max-width: 460px; width: 100%; max-height: 92vh; overflow: auto; padding: 24px 22px 18px; box-shadow: 0 18px 48px rgba(0, 0, 0, .35); }
  #rechazoFondo .rz-icono { width: 52px; height: 52px; border-radius: 50%; margin: 0 auto 10px; display: flex; align-items: center; justify-content: center; background: #fdeaea; color: #b12d25; font-size: 26px; }
  #rechazoFondo h3 { margin: 0 0 4px; text-align: center; font-size: 19px; font-weight: 800; color: #3b0612; }
  #rechazoFondo .rz-sub { margin: 0 0 14px; text-align: center; font-size: 13.5px; color: #555; line-height: 1.45; }
  #rechazoFondo label { display: block; font-size: 13px; font-weight: 700; color: #3b0612; margin-bottom: 6px; }
  #rechazoFondo .rz-chips { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 10px; }
  #rechazoFondo .rz-chip { border: 1px solid #e5d5d8; background: #fff8f8; color: #6b1021; border-radius: 20px; padding: 5px 11px; font-size: 12px; font-weight: 600; cursor: pointer; font-family: inherit; }
  #rechazoFondo .rz-chip:hover { background: #fdeaea; }
  #rechazoFondo textarea { width: 100%; min-height: 96px; resize: vertical; border: 1.5px solid #e2e2e2; border-radius: 12px; padding: 10px 12px; font-size: 14px; font-family: inherit; line-height: 1.45; box-sizing: border-box; }
  #rechazoFondo textarea:focus { outline: none; border-color: #ff7a18; box-shadow: 0 0 0 3px rgba(255, 122, 24, .18); }
  #rechazoFondo .rz-pie { display: flex; justify-content: space-between; gap: 8px; margin-top: 4px; font-size: 11.5px; color: #8a8a8a; }
  #rechazoFondo .rz-error { display: none; margin-top: 10px; background: #fdeaea; color: #b12d25; border-radius: 10px; padding: 8px 12px; font-size: 13px; font-weight: 600; }
  #rechazoFondo .rz-botones { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 14px; }
  #rechazoFondo .rz-botones button { flex: 1 1 130px; border: 0; border-radius: 12px; padding: 12px 14px; font-size: 14.5px; font-weight: 700; cursor: pointer; font-family: inherit; }
  #rechazoFondo .rz-cancelar { background: #f1f1f1; color: #444; }
  #rechazoFondo .rz-enviar { background: linear-gradient(45deg, #b12d25, #d9453b); color: #fff; box-shadow: 0 4px 12px rgba(177, 45, 37, .35); }
  #rechazoFondo .rz-enviar[disabled] { opacity: .65; cursor: wait; }
</style>

<div id="rechazoFondo" role="dialog" aria-modal="true" aria-labelledby="rzTitulo">
  <div class="rz-caja">
    <div class="rz-icono" aria-hidden="true"><i class="mdi mdi-account-cancel-outline"></i></div>
    <h3 id="rzTitulo">Rechazar postulante</h3>
    <p class="rz-sub" id="rzSub">Puedes explicarle por qué. Ella o él lo verá en su aviso y en "Enviadas".</p>

    <label for="rzMotivo">Motivo (opcional)</label>
    <div class="rz-chips" id="rzChips">
      <button type="button" class="rz-chip">Ya cubrimos la vacante</button>
      <button type="button" class="rz-chip">No cumple con la experiencia que pedimos</button>
      <button type="button" class="rz-chip">Falta documentación (CV o solicitud)</button>
      <button type="button" class="rz-chip">Elegimos a otra persona</button>
    </div>
    <textarea id="rzMotivo" maxlength="300" placeholder="Escribe un mensaje breve y respetuoso…"></textarea>
    <div class="rz-pie"><span>Si lo dejas vacío, solo se le avisa que no fue seleccionada.</span><span id="rzContador">0/300</span></div>
    <div class="rz-error" id="rzError" role="alert"></div>

    <div class="rz-botones">
      <button type="button" class="rz-cancelar" id="rzCancelar">Cancelar</button>
      <button type="button" class="rz-enviar" id="rzEnviar">Rechazar postulante</button>
    </div>
  </div>
</div>

<script>
  (function () {
    var fondo = document.getElementById('rechazoFondo');
    var campo = document.getElementById('rzMotivo');
    var contador = document.getElementById('rzContador');
    var caja = document.getElementById('rzError');
    var boton = document.getElementById('rzEnviar');
    var idActual = null;

    function token() {
      var m = document.querySelector('meta[name="csrf-token"]');
      return m ? m.content : '';
    }
    function contar() { contador.textContent = campo.value.length + '/300'; }
    function error(txt) { caja.textContent = txt; caja.style.display = txt ? 'block' : 'none'; }
    function cerrar() { fondo.classList.remove('visible'); idActual = null; }

    window.abrirRechazo = function (id, nombre) {
      idActual = id;
      var primero = String(nombre || '').trim().split(/\s+/)[0] || 'esta persona';
      document.getElementById('rzTitulo').textContent = 'Rechazar a ' + primero;
      campo.value = '';
      contar();
      error('');
      boton.disabled = false;
      boton.textContent = 'Rechazar postulante';
      fondo.classList.add('visible');
      setTimeout(function () { campo.focus({ preventScroll: true }); }, 30);
    };

    document.getElementById('rzChips').addEventListener('click', function (e) {
      var chip = e.target.closest('.rz-chip');
      if (!chip) return;
      campo.value = chip.textContent.trim();
      contar();
      error('');
      campo.focus({ preventScroll: true });
    });
    campo.addEventListener('input', function () { contar(); error(''); });
    document.getElementById('rzCancelar').addEventListener('click', cerrar);
    fondo.addEventListener('click', function (e) { if (e.target === fondo) cerrar(); });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && fondo.classList.contains('visible')) cerrar();
    });

    boton.addEventListener('click', function () {
      if (idActual === null) return;
      var motivo = campo.value.trim();
      if (motivo !== '' && motivo.length < 5) {
        error('Si escribes un motivo, que tenga al menos 5 letras.');
        campo.focus({ preventScroll: true });
        return;
      }

      var cuerpo = { estado: 'rechazado' };
      if (motivo !== '') cuerpo.motivo = motivo;

      boton.disabled = true;
      boton.textContent = 'Enviando…';
      error('');

      fetch('/usuario/postulantes/' + idActual, {
        method: 'PATCH',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': token() },
        body: JSON.stringify(cuerpo)
      })
        .then(function (res) { return res.json().catch(function () { return {}; }).then(function (d) { return { ok: res.ok, d: d }; }); })
        .then(function (r) {
          if (!r.ok) {
            var primero = r.d && r.d.errors ? Object.values(r.d.errors)[0][0] : null;
            error(primero || (r.d && (r.d.error || r.d.message)) || 'Ocurrió un error al rechazar al postulante.');
            boton.disabled = false;
            boton.textContent = 'Rechazar postulante';
            return;
          }
          location.reload();
        })
        .catch(function () {
          error('Ocurrió un error de conexión. Intenta de nuevo.');
          boton.disabled = false;
          boton.textContent = 'Rechazar postulante';
        });
    });
  })();
</script>
