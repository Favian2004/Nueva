"use strict";

// ====== VENTANAS BONITAS (reemplazan alert() y confirm() del navegador) ======
// Se carga solo desde main.js, así que cubre todas las pantallas del dashboard sin tocarlas.
//
//  - alert('texto')   -> tarjeta con botón "Entendido". Si la página se recarga o cambia justo
//                        después (location.reload / redirección), el aviso se muestra al cargar la siguiente.
//  - confirm('texto') -> tarjeta con "Cancelar" / "Aceptar". Un confirm() normal espera la respuesta,
//                        pero una ventana propia no puede detener el código. Por eso: se cancela el clic
//                        en ese momento, se pregunta, y si la persona acepta se vuelve a hacer el mismo
//                        clic (o envío del formulario) y esa segunda vez confirm() responde "sí".
//                        Si no se puede saber de qué clic viene (ej. un confirm() dentro de un temporizador),
//                        se usa la ventana normal del navegador como respaldo.
(function () {
  if (window.__dialogosBonitos) return;
  window.__dialogosBonitos = true;

  var nativoAlert = window.alert.bind(window);
  var nativoConfirm = window.confirm.bind(window);
  var CLAVE_PENDIENTES = 'sinteczate_alertas_pendientes';

  var cola = [];          // ventanas esperando su turno
  var actual = null;      // la que se está mostrando
  var aprobado = null;    // {msg, t}: confirm() que la persona ya aceptó y se está repitiendo
  var raiz = null;

  // ---------- estilos y contenedor ----------
  function preparar() {
    if (raiz) return true;
    if (!document.body) return false;

    var st = document.createElement('style');
    st.textContent =
      '#dlgRaiz{position:fixed;inset:0;z-index:2147482000;display:none;align-items:center;justify-content:center;padding:16px;background:rgba(20,6,10,.55);font-family:inherit}' +
      '#dlgRaiz.visible{display:flex;animation:dlgFondo .15s ease-out}' +
      '#dlgRaiz .dlg-caja{background:#fff;border-radius:18px;max-width:400px;width:100%;padding:24px 22px 18px;text-align:center;box-shadow:0 18px 48px rgba(0,0,0,.35);animation:dlgCaja .18s ease-out}' +
      '#dlgRaiz .dlg-icono{width:52px;height:52px;border-radius:50%;margin:0 auto 12px;display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:800;background:#fff4dc;color:#d97706}' +
      '#dlgRaiz .dlg-icono.peligro{background:#fdeaea;color:#b12d25}' +
      '#dlgRaiz .dlg-icono.exito{background:#dcfce7;color:#16a34a}' +
      '#dlgRaiz .dlg-icono.info{background:#e8f1ff;color:#2563eb}' +
      '#dlgRaiz .dlg-texto{margin:0 0 16px;font-size:15px;line-height:1.5;color:#3b0612;white-space:pre-line;word-break:break-word;max-height:55vh;overflow:auto}' +
      '#dlgRaiz .dlg-botones{display:flex;gap:10px;flex-wrap:wrap}' +
      '#dlgRaiz button{flex:1 1 120px;border:0;border-radius:12px;padding:12px 14px;font-size:14.5px;font-weight:700;cursor:pointer;font-family:inherit}' +
      '#dlgRaiz .dlg-si{background:linear-gradient(45deg,#ff7a18,#ffb347);color:#fff;box-shadow:0 4px 12px rgba(255,122,24,.35)}' +
      '#dlgRaiz .dlg-si.peligro{background:linear-gradient(45deg,#b12d25,#d9453b);box-shadow:0 4px 12px rgba(177,45,37,.35)}' +
      '#dlgRaiz .dlg-no{background:#f1f1f1;color:#444}' +
      '#dlgRaiz button:focus-visible{outline:3px solid #6b1021;outline-offset:2px}' +
      '@keyframes dlgFondo{from{opacity:0}to{opacity:1}}' +
      '@keyframes dlgCaja{from{transform:scale(.94);opacity:0}to{transform:scale(1);opacity:1}}' +
      '@media (prefers-reduced-motion:reduce){#dlgRaiz.visible,#dlgRaiz .dlg-caja{animation:none}}';
    document.head.appendChild(st);

    raiz = document.createElement('div');
    raiz.id = 'dlgRaiz';
    raiz.setAttribute('role', 'alertdialog');
    raiz.setAttribute('aria-modal', 'true');
    document.body.appendChild(raiz);

    raiz.addEventListener('click', function (e) {
      if (e.target === raiz && actual) cerrar(actual.tipo === 'alert');
    });
    document.addEventListener('keydown', function (e) {
      if (!actual) return;
      if (e.key === 'Escape') { e.preventDefault(); cerrar(false); }
    }, true);
    return true;
  }

  // ---------- qué icono y qué texto de botón le tocan ----------
  function clasificar(tipo, msg) {
    var m = (msg || '').toLowerCase();
    if (tipo === 'confirm') {
      var peligro = /elimin|borrar|cerrar sesi|rechaz|no se puede deshacer/.test(m);
      var boton = 'Aceptar';
      if (/^\W*elimin/.test(m)) boton = 'Sí, eliminar';
      else if (/cerrar sesi/.test(m)) boton = 'Cerrar sesión';
      return { icono: peligro ? '!' : '?', clase: peligro ? 'peligro' : '', boton: boton };
    }
    if (/error|ocurri|no se pudo|no pudo|falta|revisa|inv[aá]lid|debes|obligatori|incorrect|intenta de nuevo/.test(m)) {
      return { icono: '!', clase: 'peligro', boton: 'Entendido' };
    }
    if (/guardad|correct|enviad|listo|gracias|éxito|exito|actualizad|publicad|copiad|eliminad|agregad|se envi/.test(m)) {
      return { icono: '✓', clase: 'exito', boton: 'Entendido' };
    }
    return { icono: 'i', clase: 'info', boton: 'Entendido' };
  }

  function escapar(t) {
    return String(t).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }

  // ---------- mostrar / cerrar ----------
  function siguiente() {
    if (actual || !cola.length) return;
    if (!preparar()) { document.addEventListener('DOMContentLoaded', siguiente, { once: true }); return; }

    actual = cola.shift();
    var c = clasificar(actual.tipo, actual.msg);
    raiz.innerHTML =
      '<div class="dlg-caja">' +
        '<div class="dlg-icono ' + c.clase + '" aria-hidden="true">' + c.icono + '</div>' +
        '<p class="dlg-texto">' + escapar(actual.msg) + '</p>' +
        '<div class="dlg-botones">' +
          (actual.tipo === 'confirm' ? '<button type="button" class="dlg-no">Cancelar</button>' : '') +
          '<button type="button" class="dlg-si ' + c.clase + '">' + c.boton + '</button>' +
        '</div>' +
      '</div>';
    raiz.classList.add('visible');

    var si = raiz.querySelector('.dlg-si');
    var no = raiz.querySelector('.dlg-no');
    si.addEventListener('click', function () { cerrar(true); });
    if (no) no.addEventListener('click', function () { cerrar(false); });
    // En preguntas peligrosas el foco empieza en "Cancelar" para no confirmar sin querer.
    (no && c.clase === 'peligro' ? no : si).focus();
  }

  function cerrar(acepto) {
    if (!actual) return;
    var d = actual;
    actual = null;
    raiz.classList.remove('visible');
    raiz.innerHTML = '';
    if (d.tipo === 'confirm' && acepto && d.alAceptar) d.alAceptar();
    siguiente();
  }

  // ---------- alertas que sobreviven a una recarga ----------
  function leerPendientes() {
    try { return JSON.parse(sessionStorage.getItem(CLAVE_PENDIENTES) || '[]') || []; } catch (e) { return []; }
  }
  function guardarPendientes(lista) {
    try {
      if (lista.length) sessionStorage.setItem(CLAVE_PENDIENTES, JSON.stringify(lista));
      else sessionStorage.removeItem(CLAVE_PENDIENTES);
    } catch (e) {}
  }

  function guardarAlSalir() {
    var lista = [];
    if (actual && actual.tipo === 'alert') lista.push({ msg: actual.msg, t: Date.now() });
    cola.forEach(function (d) { if (d.tipo === 'alert') lista.push({ msg: d.msg, t: Date.now() }); });
    guardarPendientes(lista);
  }
  window.addEventListener('pagehide', guardarAlSalir);

  function mostrarPendientes() {
    var lista = leerPendientes();
    guardarPendientes([]);
    var ahora = Date.now();
    lista.forEach(function (p) {
      if (p && p.msg && ahora - p.t < 30000) cola.push({ tipo: 'alert', msg: String(p.msg) });
    });
    siguiente();
  }

  // ---------- alert() ----------
  window.alert = function (msg) {
    cola.push({ tipo: 'alert', msg: msg == null ? '' : String(msg) });
    siguiente();
  };

  // ---------- confirm() ----------
  window.confirm = function (msg) {
    msg = msg == null ? '' : String(msg);

    // Segunda vuelta: la persona ya dijo que sí a esta misma pregunta.
    if (aprobado && aprobado.msg === msg && Date.now() - aprobado.t < 4000) {
      aprobado = null;
      return true;
    }

    var ev = window.event;
    var tgt = ev && ev.target;
    var tipo = ev && ev.type;
    var puedeRepetir = tgt && tgt.isConnected && (tipo === 'click' || tipo === 'submit');

    if (!puedeRepetir || !preparar()) return nativoConfirm(msg);

    // Se frena lo que iba a pasar con este clic/envío y se pregunta.
    try { ev.preventDefault(); } catch (e) {}
    var enviador = ev.submitter || null;

    cola.push({
      tipo: 'confirm',
      msg: msg,
      alAceptar: function () {
        aprobado = { msg: msg, t: Date.now() };
        setTimeout(function () {
          if (!tgt.isConnected) { aprobado = null; return; }
          if (tipo === 'submit' && tgt.requestSubmit) tgt.requestSubmit(enviador && enviador.isConnected ? enviador : undefined);
          else if (typeof tgt.click === 'function') tgt.click();
          else tgt.dispatchEvent(new MouseEvent('click', { bubbles: true, cancelable: true }));
          // Si nada volvió a preguntar, se limpia para que no quede "aprobado" colgado.
          setTimeout(function () { aprobado = null; }, 300);
        }, 0);
      }
    });
    siguiente();
    return false;
  };

  window.__dialogosNativos = { alert: nativoAlert, confirm: nativoConfirm };

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', mostrarPendientes);
  else mostrarPendientes();
})();
