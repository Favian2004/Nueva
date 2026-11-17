"use strict";

// ====== AVISO DE SESIÓN POR INACTIVIDAD (dashboard del usuario) ======
// Cuando la persona lleva mucho rato sin hacer nada, pregunta si sigue ahí.
// Si no contesta, cierra la sesión y la manda a iniciar sesión con un mensaje claro.
// El servidor es quien decide de verdad (middleware InactividadSesion); esto solo avisa antes.
(function () {
  if (window.__sesionInactividad) return;
  window.__sesionInactividad = true;

  var URL_ESTADO = '/usuario/sesion/estado';
  var URL_RENOVAR = '/usuario/sesion/renovar';
  var DESTINO = '/acceso?sesion=inactividad';
  var CLAVE = 'sinteczate_toque_sesion';

  var vidaMs = 0;        // cuánto aguanta la sesión sin actividad
  var avisoMs = 0;       // cuánto antes del final se muestra el aviso
  var pingCadaMs = 0;    // cada cuánto se renueva la sesión mientras la persona sí está usando la página
  var ultimoToque = Date.now(); // última vez que el servidor supo de actividad real
  var pingEnCurso = false;
  var cerrando = false;
  var caja = null, cuenta = null;

  function guardarToque(t) {
    ultimoToque = t;
    try { localStorage.setItem(CLAVE, String(t)); } catch (e) {}
  }

  // Si hay otra pestaña abierta que renovó la sesión, se toma su hora.
  function toqueActual() {
    try {
      var v = parseInt(localStorage.getItem(CLAVE) || '0', 10);
      if (v > ultimoToque && v <= Date.now()) ultimoToque = v;
    } catch (e) {}
    return ultimoToque;
  }

  function renovar() {
    return fetch(URL_RENOVAR, {
      credentials: 'same-origin',
      cache: 'no-store',
      headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    }).then(function (r) {
      if (r.status === 204) { guardarToque(Date.now()); return true; }
      return false;            // 401 / redirección: la sesión ya no existe
    }).catch(function () {
      return null;             // sin internet: no se cierra nada por eso
    });
  }

  function csrf() {
    var m = document.querySelector('meta[name="csrf-token"]');
    if (m && m.content) return m.content;
    var i = document.querySelector('input[name="_token"]');
    return i ? i.value : '';
  }

  function irALogin() {
    if (cerrando) return;
    cerrando = true;
    var fin = function () { window.location.href = DESTINO; };
    try {
      fetch('/logout', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
      }).then(fin, fin);
    } catch (e) { fin(); }
  }

  function crearAviso() {
    if (caja) return;
    var estilo = document.createElement('style');
    estilo.textContent =
      '#sesionAviso{position:fixed;inset:0;z-index:2147483000;display:none;align-items:center;justify-content:center;padding:16px;background:rgba(20,6,10,.62);font-family:inherit}' +
      '#sesionAviso.visible{display:flex}' +
      '#sesionAviso .sa-caja{background:#fff;border-radius:18px;max-width:380px;width:100%;padding:26px 22px 20px;text-align:center;box-shadow:0 18px 48px rgba(0,0,0,.35)}' +
      '#sesionAviso .sa-icono{width:54px;height:54px;border-radius:50%;margin:0 auto 12px;display:flex;align-items:center;justify-content:center;background:#fff4dc;color:#d97706;font-size:28px}' +
      '#sesionAviso h3{margin:0 0 6px;font-size:19px;font-weight:800;color:#3b0612}' +
      '#sesionAviso p{margin:0 0 6px;font-size:14px;color:#555;line-height:1.45}' +
      '#sesionAviso .sa-tiempo{font-size:30px;font-weight:800;color:#b12d25;margin:8px 0 14px;font-variant-numeric:tabular-nums}' +
      '#sesionAviso .sa-botones{display:flex;gap:10px;flex-wrap:wrap}' +
      '#sesionAviso button{flex:1 1 140px;border:0;border-radius:12px;padding:12px 14px;font-size:14.5px;font-weight:700;cursor:pointer}' +
      '#sesionAviso .sa-seguir{background:linear-gradient(45deg,#ff7a18,#ffb347);color:#fff;box-shadow:0 4px 12px rgba(255,122,24,.35)}' +
      '#sesionAviso .sa-salir{background:#f1f1f1;color:#444}';
    document.head.appendChild(estilo);

    caja = document.createElement('div');
    caja.id = 'sesionAviso';
    caja.setAttribute('role', 'alertdialog');
    caja.setAttribute('aria-modal', 'true');
    caja.setAttribute('aria-labelledby', 'saTitulo');
    caja.innerHTML =
      '<div class="sa-caja">' +
        '<div class="sa-icono" aria-hidden="true">&#9201;</div>' +
        '<h3 id="saTitulo">¿Sigues ahí?</h3>' +
        '<p>Por seguridad, tu sesión se cerrará por inactividad en:</p>' +
        '<div class="sa-tiempo" id="saTiempo" aria-live="polite">--:--</div>' +
        '<div class="sa-botones">' +
          '<button type="button" class="sa-seguir" id="saSeguir">Seguir conectado</button>' +
          '<button type="button" class="sa-salir" id="saSalir">Cerrar sesión</button>' +
        '</div>' +
      '</div>';
    document.body.appendChild(caja);
    cuenta = caja.querySelector('#saTiempo');

    caja.querySelector('#saSeguir').addEventListener('click', function () {
      renovar().then(function (ok) {
        if (ok === false) { irALogin(); return; }
        if (ok === true) ocultarAviso();
      });
    });
    caja.querySelector('#saSalir').addEventListener('click', irALogin);
  }

  function avisoVisible() { return !!caja && caja.classList.contains('visible'); }
  function mostrarAviso() { crearAviso(); caja.classList.add('visible'); }
  function ocultarAviso() { if (caja) caja.classList.remove('visible'); }

  function formato(ms) {
    var s = Math.max(0, Math.ceil(ms / 1000));
    var m = Math.floor(s / 60);
    s = s % 60;
    return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
  }

  function tic() {
    if (cerrando) return;
    var inactivo = Date.now() - toqueActual();

    if (inactivo >= vidaMs) {
      // Venció. Antes de cerrar, se confirma con el servidor (por si otra pestaña la renovó).
      renovar().then(function (ok) {
        if (ok === true) { ocultarAviso(); return; }
        if (ok === false) irALogin();
      });
      return;
    }

    if (inactivo >= vidaMs - avisoMs) {
      mostrarAviso();
      if (cuenta) cuenta.textContent = formato(vidaMs - inactivo);
    } else if (avisoVisible()) {
      ocultarAviso(); // otra pestaña la renovó
    }
  }

  // Mientras la persona usa la página, la sesión se renueva cada cierto tiempo.
  function actividad() {
    if (cerrando || avisoVisible() || pingEnCurso) return;
    if (Date.now() - toqueActual() < pingCadaMs) return;
    pingEnCurso = true;
    renovar().then(function (ok) {
      if (ok === false) irALogin();
    }).then(function () { pingEnCurso = false; });
  }

  function iniciar(minutos) {
    vidaMs = Math.max(1, minutos) * 60000;
    avisoMs = Math.min(10 * 60000, Math.floor(vidaMs / 2));
    pingCadaMs = Math.min(5 * 60000, Math.floor(vidaMs / 4));
    guardarToque(Date.now()); // cargar esta página ya fue una petición con sesión

    ['click', 'keydown', 'scroll', 'touchstart', 'mousemove'].forEach(function (ev) {
      document.addEventListener(ev, actividad, { passive: true });
    });
    document.addEventListener('visibilitychange', function () { if (!document.hidden) tic(); });
    setInterval(tic, 1000);
  }

  fetch(URL_ESTADO, {
    credentials: 'same-origin',
    cache: 'no-store',
    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
  }).then(function (r) {
    return r.ok ? r.json() : null;
  }).then(function (d) {
    if (d && d.minutos) iniciar(parseInt(d.minutos, 10));
  }).catch(function () {});
})();
