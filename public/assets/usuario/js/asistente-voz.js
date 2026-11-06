"use strict";

/*
 * ASISTENTE DE VOZ "SINTEC" — botón flotante de micrófono para todas las pantallas.
 *
 * Qué hace:
 *  - Te escucha (Chrome / Edge) o te lee lo que escribas, y te contesta hablando.
 *  - Busca y filtra ("busco un plomero barato en Xaltipan"), te lleva a cada pantalla,
 *    te guía a publicar (servicio, vacante o anuncio) y funciona en modo Trabajador y Empleador.
 *  - A quien llega sin saber qué hacer le pregunta qué necesita y lo manda al lugar correcto.
 *  - Pide permiso del micrófono la primera vez, con una explicación clara.
 *  - Se oculta con un comando ("ocúltate") y regresa con Alt+V, con el puntito de la esquina
 *    o (si se activa en Ayuda) diciendo "Hola Sintec".
 *
 * Archivos (todos juntos, no se mezclan con el resto de tu código):
 *    js/asistente-voz.js      <- este (lo único que hay que cargar)
 *    js/asistente-voz-nlu.js  <- entiende lo que se dice (se carga solo)
 *    css/asistente-voz.css    <- diseño (se carga solo)
 *  Datos: GET /asistente/datos (localidades, categorías y quién eres).
 */
(function () {
  if (window.__asistenteVoz) return;
  window.__asistenteVoz = true;

  // ====================================================================
  //  0. CARGA DE ARCHIVOS HERMANOS (css + nlu)
  // ====================================================================
  var actual = document.currentScript && document.currentScript.src;
  var BASE_JS = actual ? actual.replace(/asistente-voz\.js(\?.*)?$/, '') : '/assets/usuario/js/';
  var CONSULTA = actual && /\?.*$/.test(actual) ? actual.replace(/^[^?]*/, '') : '';
  var BASE_CSS = BASE_JS.replace(/\/js\/$/, '/css/');
  var BASE_IMG = BASE_JS.replace(/\/js\/$/, '/img/');

  function cargarCss() {
    if (document.querySelector('link[data-asistente-voz]')) return;
    var l = document.createElement('link');
    l.rel = 'stylesheet';
    l.href = BASE_CSS + 'asistente-voz.css' + CONSULTA;
    l.setAttribute('data-asistente-voz', '1');
    document.head.appendChild(l);
  }

  function cargarNlu(listo) {
    if (window.AsistenteNLU) return listo();
    var s = document.createElement('script');
    s.src = BASE_JS + 'asistente-voz-nlu.js' + CONSULTA;
    s.onload = listo;
    s.onerror = function () { console.warn('[asistente-voz] No se pudo cargar asistente-voz-nlu.js'); };
    document.head.appendChild(s);
  }

  function arrancar() {
    cargarCss();
    cargarNlu(iniciar);
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', arrancar);
  else arrancar();

  // ====================================================================
  //  1. TODO LO DEMÁS (se ejecuta cuando ya está el "cerebro")
  // ====================================================================
  function iniciar() {
    var NLU = window.AsistenteNLU;
    var DESTINOS = NLU.DESTINOS;

    var SR = window.SpeechRecognition || window.webkitSpeechRecognition;
    var TTS = window.speechSynthesis;
    var SEGURO = window.isSecureContext !== false;

    // ---------- Preferencias (se recuerdan en este navegador) ----------
    var CFG_BASE = { voz: true, vozNombre: '', llamada: false, permiso: false, min: false, visto: false, pista: false, avisos: true };
    function leerCfg() {
      try { return Object.assign({}, CFG_BASE, JSON.parse(localStorage.getItem('av_cfg') || '{}')); } catch (e) { return Object.assign({}, CFG_BASE); }
    }
    var cfg = leerCfg();
    function guardarCfg() { try { localStorage.setItem('av_cfg', JSON.stringify(cfg)); } catch (e) { /* sin almacenamiento */ } }

    // ---------- Conversación (dura mientras la pestaña esté abierta) ----------
    function leerSes() {
      try { return JSON.parse(sessionStorage.getItem('av_ses') || '{}'); } catch (e) { return {}; }
    }
    var ses = Object.assign({ abierto: false, msgs: [], pend: null, fallos: 0, ultimo: '' }, leerSes());
    function guardarSes() {
      try {
        ses.msgs = ses.msgs.slice(-14);
        sessionStorage.setItem('av_ses', JSON.stringify(ses));
      } catch (e) { /* sin almacenamiento */ }
    }
    function leerPost() {
      try { return JSON.parse(sessionStorage.getItem('av_post') || 'null'); } catch (e) { return null; }
    }
    function guardarPost(p) {
      try { if (p) sessionStorage.setItem('av_post', JSON.stringify(p)); else sessionStorage.removeItem('av_post'); } catch (e) { /* nada */ }
    }

    // ---------- Datos del servidor ----------
    var datos = null;
    var datosPromesa = null;
    function datosGuardados() {
      try { return JSON.parse(sessionStorage.getItem('av_datos') || 'null'); } catch (e) { return null; }
    }
    function cargarDatos() {
      if (datosPromesa) return datosPromesa;
      var respaldo = datosGuardados();
      datosPromesa = fetch('/asistente/datos', { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
        .then(function (r) { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(function (d) {
          datos = d;
          try { sessionStorage.setItem('av_datos', JSON.stringify(d)); } catch (e) { /* nada */ }
          return d;
        })
        .catch(function () {
          datos = respaldo || {
            logueado: /^\/usuario/.test(location.pathname), persona: null, municipio: 'Zacapoaxtla',
            localidades: [], categorias: [], subcategorias: []
          };
          return datos;
        });
      return datosPromesa;
    }

    // ====================================================================
    //  2. UTILIDADES
    // ====================================================================
    function el(tag, clase, texto) {
      var e = document.createElement(tag);
      if (clase) e.className = clase;
      if (texto !== undefined && texto !== null) e.textContent = texto;
      return e;
    }
    function esperar(ms) { return new Promise(function (ok) { setTimeout(ok, ms); }); }
    function csrf() {
      var m = document.querySelector('meta[name="csrf-token"]');
      if (m && m.content) return m.content;
      var c = document.cookie.match(/(?:^|; )XSRF-TOKEN=([^;]+)/);
      return c ? decodeURIComponent(c[1]) : '';
    }
    function modoActual() {
      var r = null;
      try { r = localStorage.getItem('userRole'); } catch (e) { /* nada */ }
      return r === 'employer' ? 'employer' : 'worker';
    }
    function fijarModo(modo) {
      if (modo !== 'worker' && modo !== 'employer') return false;
      var cambio = modoActual() !== modo;
      try { localStorage.setItem('userRole', modo); } catch (e) { /* nada */ }
      return cambio;
    }
    function nombreModo(m) { return m === 'employer' ? 'Empleador' : 'Trabajador'; }
    function paginaActual() { return NLU.paginaDe(location.pathname); }
    function parametrosActuales() {
      var o = {};
      try { new URLSearchParams(location.search).forEach(function (v, k) { o[k] = v; }); } catch (e) { /* nada */ }
      return o;
    }
    function dinero(n) { return '$' + Number(n).toLocaleString('es-MX'); }
    function azar(lista) { return lista[Math.floor(Math.random() * lista.length)]; }

    // Texto pensado para leerse en voz alta (sin emojis, con "pesos", sin comillas raras).
    function paraHablar(t) {
      return String(t || '')
        .replace(/\*\*/g, '')
        .replace(/\$\s?([\d.,]+)/g, function (m, n) { return n.replace(/,/g, '') + ' pesos'; })
        .replace(/[«»"“”]/g, '')
        .replace(/[\u{1F300}-\u{1FAFF}\u{2600}-\u{27BF}\u{FE0F}]/gu, '')
        .replace(/SINTECZATE/g, 'Sintecsate')
        .replace(/\bAlt\+V\b/g, 'Alt más V')
        .replace(/\s+/g, ' ').trim();
    }

    // ====================================================================
    //  3. VOZ DE SALIDA (el asistente habla)
    // ====================================================================
    var vozElegida = null;
    var tonoVoz = 1;
    var refrescarSelectorVoz = null;

    // El navegador no dice si una voz es de hombre o de mujer: se reconoce por el nombre que traen
    // Windows ("Raul", "Sabina"), Edge ("Jorge", "Dalia"), Mac y iPhone ("Juan", "Paulina") y Android.
    var RE_HOMBRE = /\b(raul|jorge|pablo|alvaro|juan|diego|carlos|miguel|jaime|pedro|enrique|antonio|andres|alonso|cecilio|gerardo|liberto|luciano|pelayo|yago|arnau|dario|elias|saul|teo|nil|francisco|jose|luis|manuel|ricardo|sergio|tomas|male|masculin\w*|hombre)\b/;
    var RE_MUJER = /\b(dalia|sabina|paulina|monica|helena|laura|elvira|paloma|angelica|beatriz|candela|carlota|larissa|marina|nuria|renata|esperanza|lucia|sofia|female|femenin\w*|mujer)\b/;
    function nombreVoz(v) { return sinAcentos(String((v && v.name) || '').toLowerCase()); }
    function esHombre(v) { return RE_HOMBRE.test(nombreVoz(v)); }
    function esMujer(v) { return !esHombre(v) && RE_MUJER.test(nombreVoz(v)); }
    function idioma(v) { return String((v && v.lang) || '').replace('_', '-').toLowerCase(); }
    function vocesEspanol() {
      var voces = TTS && TTS.getVoices ? TTS.getVoices() : [];
      return Array.prototype.filter.call(voces || [], function (v) { return idioma(v).indexOf('es') === 0; });
    }
    function elegirVoz() {
      tonoVoz = 1;
      if (!TTS) return null;
      var voces = vocesEspanol();
      if (!voces.length) return null;

      // Si la persona escogió una voz en Ayuda, esa manda
      if (cfg.vozNombre) {
        for (var k = 0; k < voces.length; k++) if (voces[k].name === cfg.vozNombre) return voces[k];
      }

      // Si no: la mejor voz de hombre (primero México, luego Latinoamérica, al final España)
      var puntos = function (v) {
        var p = 0, l = idioma(v), n = nombreVoz(v);
        if (l === 'es-mx') p += 40; else if (l === 'es-us') p += 34;
        else if (/^es-(ar|bo|cl|co|cr|cu|do|ec|gt|hn|ni|pa|pe|pr|py|sv|uy|ve|419)$/.test(l)) p += 28;
        else p += 10;
        if (esHombre(v)) p += 100; else if (esMujer(v)) p -= 20;
        if (/natural|online/.test(n)) p += 10; else if (/google/.test(n)) p += 5;
        if (v.localService === false) p += 2;
        return p;
      };
      var mejorDe = function (lista) {
        var m = null, mp = -Infinity;
        lista.forEach(function (v) { var p = puntos(v); if (p > mp) { mp = p; m = v; } });
        return m;
      };
      var mejor = mejorDe(voces);
      // Voz de hombre: tono natural. Si el equipo no tiene ninguna, se usa una voz del sistema
      // (esas sí obedecen el tono) y se baja el tono para que suene más grave.
      if (mejor && !esHombre(mejor)) {
        var delSistema = voces.filter(function (v) { return v.localService !== false; });
        if (delSistema.length) mejor = mejorDe(delSistema);
        tonoVoz = .65;
      } else tonoVoz = .95;
      return mejor;
    }
    if (TTS && TTS.addEventListener) {
      TTS.addEventListener('voiceschanged', function () {
        vozElegida = elegirVoz();
        if (refrescarSelectorVoz) refrescarSelectorVoz();
      });
    }

    var hablando = false;
    var colaVoz = 0;

    function detenerVoz() {
      colaVoz++;
      if (TTS) { try { TTS.cancel(); } catch (e) { /* nada */ } }
      if (hablando) { hablando = false; estado(escuchando ? 'escuchando' : 'reposo'); }
    }

    // Devuelve una promesa que se cumple cuando termina de hablar.
    function hablar(texto) {
      var limpio = paraHablar(texto);
      if (!TTS || !cfg.voz || !limpio) return Promise.resolve(false);
      detenerVoz();
      var miTurno = colaVoz;
      // Se parte en frases: Chrome corta los textos largos a mitad.
      var frases = limpio.match(/[^.!?¿¡]+[.!?]*/g) || [limpio];
      var trozos = [], acum = '';
      frases.forEach(function (f) {
        if ((acum + f).length > 170 && acum) { trozos.push(acum); acum = f; } else acum += f;
      });
      if (acum.trim()) trozos.push(acum);
      if (!vozElegida) vozElegida = elegirVoz();

      return new Promise(function (listo) {
        var i = 0, bloqueada = false;
        hablando = true;
        detenerEscuchaSuave();
        estado('hablando');
        var guardian = setTimeout(function () { terminar(false); }, 25000 + limpio.length * 90);

        function terminar(ok) {
          clearTimeout(guardian);
          if (hablando && miTurno === colaVoz) { hablando = false; estado(escuchando ? 'escuchando' : 'reposo'); }
          listo(ok && !bloqueada);
        }
        function siguiente() {
          if (miTurno !== colaVoz) return terminar(false);
          if (i >= trozos.length) return terminar(true);
          var u = new SpeechSynthesisUtterance(trozos[i++].trim());
          u.lang = (vozElegida && vozElegida.lang) || 'es-MX';
          if (vozElegida) u.voice = vozElegida;
          u.rate = 1.03; u.pitch = tonoVoz;
          u.onend = siguiente;
          u.onerror = function (ev) {
            if (ev && (ev.error === 'not-allowed')) bloqueada = true;
            if (ev && (ev.error === 'interrupted' || ev.error === 'canceled')) return terminar(false);
            terminar(false);
          };
          try { TTS.speak(u); } catch (e) { terminar(false); }
        }
        // Chrome a veces deja la voz "pausada": se reanuda antes de empezar.
        try { TTS.resume(); } catch (e) { /* nada */ }
        siguiente();
      });
    }

    // ====================================================================
    //  4. VOZ DE ENTRADA (el micrófono)
    // ====================================================================
    var rec = null;
    var escuchando = false;
    var porVoz = false;           // el último mensaje se dijo (no se escribió): sirve para seguir escuchando
    var textoFinal = '';
    var alternativas = [];

    function estadoPermiso() {
      // 'granted' | 'prompt' | 'denied' | 'sinsoporte' | 'inseguro'
      if (!SR) return Promise.resolve('sinsoporte');
      if (!SEGURO) return Promise.resolve('inseguro');
      if (navigator.permissions && navigator.permissions.query) {
        return navigator.permissions.query({ name: 'microphone' }).then(function (p) {
          if (p.state === 'granted' && !cfg.permiso) { cfg.permiso = true; guardarCfg(); }
          return p.state;
        }).catch(function () { return cfg.permiso ? 'granted' : 'prompt'; });
      }
      return Promise.resolve(cfg.permiso ? 'granted' : 'prompt');
    }

    function pedirPermiso() {
      if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) return Promise.resolve('granted');
      return navigator.mediaDevices.getUserMedia({ audio: true }).then(function (stream) {
        stream.getTracks().forEach(function (t) { t.stop(); });
        cfg.permiso = true; guardarCfg();
        return 'granted';
      }).catch(function (e) {
        return (e && (e.name === 'NotAllowedError' || e.name === 'SecurityError')) ? 'denied' : 'error';
      });
    }

    var sinVozSeguidas = 0;
    function crearReconocedor() {
      var r = new SR();
      r.lang = 'es-MX';
      r.interimResults = true;
      r.continuous = false;
      r.maxAlternatives = 3;
      r.onstart = function () { escuchando = true; estado('escuchando'); mostrarEnVivo(''); };
      r.onresult = function (ev) {
        var interino = '', final = '';
        alternativas = [];
        for (var i = ev.resultIndex; i < ev.results.length; i++) {
          var res = ev.results[i];
          if (res.isFinal) {
            final += res[0].transcript;
            for (var k = 0; k < res.length; k++) alternativas.push(res[k].transcript);
          } else interino += res[0].transcript;
        }
        if (interino) mostrarEnVivo(interino);
        if (final) textoFinal = final;
      };
      r.onerror = function (ev) {
        var e = ev && ev.error;
        escuchando = false;
        if (e === 'no-speech') {
          ocultarEnVivo();
          sinVozSeguidas++;
          pintarMensaje('sis', sinVozSeguidas >= 2 ? 'Sigo sin oírte. Probemos tu micrófono para ver qué pasa.' : 'No te escuché. Toca el micrófono y habla cuando quieras.', [{ t: '🎙️ Probar mi micrófono', cmd: 'probar micrófono' }]);
        }
        else if (e === 'not-allowed' || e === 'service-not-allowed') { cfg.permiso = false; guardarCfg(); mostrarPermiso('denied'); }
        else if (e === 'audio-capture') { pintarMensaje('sis', 'No encuentro ningún micrófono conectado.', [{ t: '🎙️ Probar mi micrófono', cmd: 'probar micrófono' }]); }
        else if (e === 'network') { avisoSuave('Sin internet no puedo oírte (el reconocimiento de voz lo necesita). Puedes escribirme.'); }
        else if (e === 'aborted') { /* lo detuvimos nosotros */ }
        estado('reposo');
      };
      r.onend = function () {
        var dicho = textoFinal.trim(), alts = alternativas.slice();
        textoFinal = ''; alternativas = [];
        escuchando = false;
        ocultarEnVivo();
        if (estadoUI !== 'hablando') estado('reposo');
        if (dicho) { sinVozSeguidas = 0; porVoz = true; procesar(dicho, alts); }
        reanudarLlamada();
      };
      return r;
    }

    function empezarEscucha() {
      if (!SR) { mostrarPermiso('sinsoporte'); return; }
      if (escuchando) return;
      detenerVoz();
      pararLlamada();
      estadoPermiso().then(function (p) {
        if (p !== 'granted') { mostrarPermiso(p); return; }
        try {
          rec = crearReconocedor();
          textoFinal = '';
          rec.start();
        } catch (e) { escuchando = false; estado('reposo'); }
      });
    }

    function detenerEscucha() {
      if (rec && escuchando) { try { rec.stop(); } catch (e) { /* nada */ } }
    }
    // Para cuando va a hablar el asistente: se corta el micrófono sin procesar nada.
    function detenerEscuchaSuave() {
      if (rec && escuchando) { textoFinal = ''; try { rec.abort(); } catch (e) { /* nada */ } escuchando = false; ocultarEnVivo(); }
    }

    // ====================================================================
    //  5. «HOLA SINTEC» (opcional): escucha de fondo para llamar al asistente
    // ====================================================================
    var recLlamada = null, llamadaActiva = false, llamadaFallos = 0;
    var LLAMADA = /\b(?:hola|oye|ok|okay|okey|hey|ey|buenas)\s+(?:sin\s?te[ck]h?|cin\s?te[ck]h?|sinteck?|sintech|sintec|sin tec|asistente)\b[\s,.:;-]*(.*)$/i;

    function pararLlamada() {
      llamadaActiva = false;
      if (recLlamada) { var r = recLlamada; recLlamada = null; try { r.onend = null; r.abort(); } catch (e) { /* nada */ } }
    }

    function reanudarLlamada() {
      if (!cfg.llamada || !SR || !SEGURO || llamadaActiva || escuchando || hablando || document.hidden) return;
      if (!cfg.permiso) return;
      llamadaActiva = true;
      var r = new SR();
      recLlamada = r;
      r.lang = 'es-MX'; r.continuous = true; r.interimResults = true; r.maxAlternatives = 1;
      r.onresult = function (ev) {
        for (var i = ev.resultIndex; i < ev.results.length; i++) {
          var t = ev.results[i][0].transcript;
          var m = LLAMADA.exec(sinAcentos(t));
          if (m) {
            var resto = (m[1] || '').trim();
            llamadaFallos = 0;
            pararLlamada();
            abrirPanel(true);
            if (ev.results[i].isFinal && resto) { porVoz = true; procesar(resto, []); }
            else { decir('Dime, te escucho.', { sinHablar: false }).then(function () { empezarEscucha(); }); }
            return;
          }
        }
      };
      r.onerror = function (ev) {
        if (ev && (ev.error === 'not-allowed' || ev.error === 'service-not-allowed')) { cfg.llamada = false; guardarCfg(); pararLlamada(); }
      };
      r.onend = function () {
        llamadaActiva = false; recLlamada = null;
        if (!cfg.llamada) return;
        llamadaFallos++;
        setTimeout(reanudarLlamada, Math.min(8000, 300 * llamadaFallos));
      };
      try { r.start(); } catch (e) { llamadaActiva = false; }
    }
    function sinAcentos(t) { return NLU.sinAcentos(t); }

    document.addEventListener('visibilitychange', function () {
      if (document.hidden) { pararLlamada(); detenerEscuchaSuave(); } else reanudarLlamada();
    });

    // ====================================================================
    //  6. INTERFAZ
    // ====================================================================
    var raiz, fab, panel, msgs, enVivo, enVivoTexto, entrada, btnMicPanel, btnVoz, btnAyuda, vistaPermiso, vistaAyuda, pista, mini, pie;
    var estadoUI = 'reposo';

    var ICONOS = {
      // Robotito del asistente: cabeza blanca, pantalla vino y ojos que parpadean (los colores están en el CSS)
      robot: '<rect class="r-ant" x="11.25" y="2.6" width="1.5" height="3.4" rx=".75"/><circle class="r-bola" cx="12" cy="2.4" r="1.7"/>' +
        '<rect x="1.3" y="11" width="2.5" height="5.2" rx="1.25"/><rect x="20.2" y="11" width="2.5" height="5.2" rx="1.25"/>' +
        '<rect x="3.9" y="5.8" width="16.2" height="14.2" rx="5.3"/><rect class="r-pant" x="6.2" y="8.4" width="11.6" height="9" rx="3.5"/>' +
        '<ellipse class="r-ojo" cx="9.4" cy="12.2" rx="1.45" ry="1.75"/><ellipse class="r-ojo" cx="14.6" cy="12.2" rx="1.45" ry="1.75"/>' +
        '<path class="r-boca" d="M9.7 15.2a3.3 3.3 0 0 0 4.6 0"/>',
      mic: '<path d="M12 14a3.5 3.5 0 0 0 3.5-3.5v-5a3.5 3.5 0 0 0-7 0v5A3.5 3.5 0 0 0 12 14Zm6-3.5a1 1 0 1 0-2 0 4 4 0 0 1-8 0 1 1 0 1 0-2 0 6 6 0 0 0 5 5.91V19H9.5a1 1 0 1 0 0 2h5a1 1 0 1 0 0-2H13v-2.59A6 6 0 0 0 18 10.5Z"/>',
      enviar: '<path d="M3.4 20.4 21 12 3.4 3.6a.7.7 0 0 0-1 .8L4 10.5l9 1.5-9 1.5-1.6 6.1a.7.7 0 0 0 1 .8Z"/>',
      bocina: '<path d="M4 9.5v5h3.6l4.6 3.8a.7.7 0 0 0 1.1-.5V6.2a.7.7 0 0 0-1.1-.5L7.6 9.5H4Zm12.2-1.3a1 1 0 0 0-.7 1.7 3.2 3.2 0 0 1 0 4.2 1 1 0 1 0 1.5 1.3 5.2 5.2 0 0 0 0-6.8 1 1 0 0 0-.8-.4Zm2.7-2.4a1 1 0 0 0-.7 1.7 6.8 6.8 0 0 1 0 9 1 1 0 1 0 1.5 1.3 8.8 8.8 0 0 0 0-11.6 1 1 0 0 0-.8-.4Z"/>',
      mudo: '<path d="M4 9.5v5h3.6l4.6 3.8a.7.7 0 0 0 1.1-.5V6.2a.7.7 0 0 0-1.1-.5L7.6 9.5H4Zm13.1.2-1.4 1.4-1.4-1.4a1 1 0 0 0-1.4 1.4l1.4 1.4-1.4 1.4a1 1 0 1 0 1.4 1.4l1.4-1.4 1.4 1.4a1 1 0 0 0 1.4-1.4l-1.4-1.4 1.4-1.4a1 1 0 0 0-1.4-1.4Z"/>',
      ayuda: '<path d="M12 2.5a9.5 9.5 0 1 0 0 19 9.5 9.5 0 0 0 0-19Zm.9 14.6h-1.8v-1.8h1.8v1.8Zm1.9-6.4c-.4.6-.9.9-1.4 1.3-.4.3-.5.5-.5 1.1h-1.7c0-1 .2-1.5.8-2 .5-.4 1-.7 1.2-1.1.3-.5 0-1.2-.9-1.2-.7 0-1.1.4-1.3 1l-1.6-.7c.4-1.2 1.4-2 2.9-2 2.4 0 3.7 1.9 2.5 3.6Z"/>',
      bajar: '<path d="M6.7 9.3a1 1 0 0 1 1.4 0L12 13.2l3.9-3.9a1 1 0 1 1 1.4 1.4l-4.6 4.6a1 1 0 0 1-1.4 0L6.7 10.7a1 1 0 0 1 0-1.4Z"/>',
      cerrar: '<path d="M6.7 5.3a1 1 0 0 0-1.4 1.4L10.6 12l-5.3 5.3a1 1 0 1 0 1.4 1.4L12 13.4l5.3 5.3a1 1 0 0 0 1.4-1.4L13.4 12l5.3-5.3a1 1 0 0 0-1.4-1.4L12 10.6 6.7 5.3Z"/>',
      parar: '<rect x="6.5" y="6.5" width="11" height="11" rx="2.5"/>',
      volver: '<path d="M10.3 5.3a1 1 0 0 1 0 1.4L6.4 10.6H19a1 1 0 1 1 0 2H6.4l3.9 3.9a1 1 0 1 1-1.4 1.4l-5.6-5.6a1 1 0 0 1 0-1.4l5.6-5.6a1 1 0 0 1 1.4 0Z"/>',
      candado: '<path d="M7 10V8a5 5 0 0 1 10 0v2h.5A1.5 1.5 0 0 1 19 11.5v8a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 5 19.5v-8A1.5 1.5 0 0 1 6.5 10H7Zm2 0h6V8a3 3 0 0 0-6 0v2Z"/>'
    };
    function icono(nombre, clase) {
      var s = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
      s.setAttribute('viewBox', '0 0 24 24');
      s.setAttribute('aria-hidden', 'true');
      s.setAttribute('class', 'av-ico' + (clase ? ' ' + clase : ''));
      s.innerHTML = ICONOS[nombre] || '';
      return s;
    }
    // ---------- El personaje de Sintec: la cara del logo con ojos que se mueven ----------
    // La imagen no trae ojos: se dibujan aquí (SVG) para que parpadeen y miren a los lados.
    // Si la imagen no carga, se usa el robotito de antes.
    var contadorOjos = 0;
    function ojo(lado) {
      var izq = lado === 'i';
      var forma = izq
        ? 'M2 28C20 8 40 1 53 1C72 1 84 12 89 23C70 32 55 38 42 38C28 38 12 34 2 28Z'
        : 'M0 37C6 14 20 2 38 1C54 0 66 5 72 11C66 26 50 38 34 39C20 39 8 39 0 37Z';
      var parpado = izq
        ? 'M-4 31L2 28C20 8 40 1 53 1C72 1 84 12 89 23'
        : 'M0 37C6 14 20 2 38 1C54 0 66 5 72 11L77 8';
      var cx = izq ? 45 : 28, cy = izq ? 21 : 22;
      var id = 'av-ojo-clip-' + (++contadorOjos);
      var s = el('span', 'av-ojo av-ojo-' + lado);
      s.innerHTML =
        '<svg viewBox="0 0 ' + (izq ? 90 : 72) + ' 40" aria-hidden="true" focusable="false">' +
          '<defs><clipPath id="' + id + '"><path d="' + forma + '"/></clipPath></defs>' +
          '<g clip-path="url(#' + id + ')">' +
            '<path d="' + forma + '" fill="#fff"/>' +
            '<g class="av-iris">' +
              '<circle cx="' + cx + '" cy="' + cy + '" r="17" fill="#12294a"/>' +
              '<circle cx="' + cx + '" cy="' + (cy + 3) + '" r="11" fill="#1d5a96"/>' +
              '<circle cx="' + cx + '" cy="' + cy + '" r="7" fill="#06142c"/>' +
              '<ellipse cx="' + (cx + 9) + '" cy="' + (cy - 9) + '" rx="4.6" ry="4.3" fill="#fff"/>' +
            '</g>' +
          '</g>' +
          '<path d="' + parpado + '" fill="none" stroke="#0a1730" stroke-width="5.5" stroke-linecap="round" stroke-linejoin="round"/>' +
        '</svg>';
      return s;
    }
    function personaje(clase) {
      var c = el('span', 'av-cara' + (clase ? ' ' + clase : ''));
      var img = document.createElement('img');
      img.className = 'av-cara-img';
      img.alt = '';
      img.draggable = false;
      img.src = BASE_IMG + 'sintec-cara.webp' + CONSULTA;
      img.addEventListener('error', function () {
        var r = icono('robot', 'av-ico-mic av-ico-robot');
        var f = c.closest && c.closest('.av-fab');
        if (f) f.classList.remove('av-fab-cara');
        if (c.parentNode) c.parentNode.replaceChild(r, c);
      });
      c.appendChild(img);
      c.appendChild(ojo('i'));
      c.appendChild(ojo('d'));
      return c;
    }

    // Hacia dónde miran los ojos: x e y van de -1 a 1 (0 = de frente). Los ojos siguen el cursor
    // en computadora; si el cursor no se mueve, miran de un lado a otro de vez en cuando.
    var mirada = { x: 0, y: 0 };
    var ultimoPuntero = 0, miradaTimer = null, punteroRaf = 0, punteroX = 0, punteroY = 0;
    var MIRADAS = [[0, 0], [-1, 0], [1, 0], [.7, -.8], [-.7, -.6], [.8, .5], [-.9, .4], [0, -1], [0, 0], [1, -.3]];
    function sinMovimiento() { return !!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches); }
    function mirar(x, y) {
      x = Math.max(-1, Math.min(1, x)); y = Math.max(-1, Math.min(1, y));
      if (Math.abs(x - mirada.x) < .03 && Math.abs(y - mirada.y) < .03) return;
      mirada.x = x; mirada.y = y;
      if (raiz) { raiz.style.setProperty('--av-gx', x.toFixed(2)); raiz.style.setProperty('--av-gy', y.toFixed(2)); }
    }
    function seguirPuntero(ev) {
      if (!raiz || ev.pointerType === 'touch' || sinMovimiento()) return;
      punteroX = ev.clientX; punteroY = ev.clientY; ultimoPuntero = Date.now();
      if (punteroRaf) return;
      punteroRaf = requestAnimationFrame(function () {
        punteroRaf = 0;
        if (estadoUI === 'pensando') return;
        var ref = raiz.classList.contains('av-minimizado') ? mini : fab;
        var r = ref && ref.getBoundingClientRect();
        if (!r || !r.width) return;
        var dx = punteroX - (r.left + r.width / 2), dy = punteroY - (r.top + r.height / 2);
        var d = Math.sqrt(dx * dx + dy * dy) || 1;
        var fuerza = Math.min(1, d / 140);
        mirar(dx / d * fuerza, dy / d * fuerza);
      });
    }
    function planearMirada() {
      clearTimeout(miradaTimer);
      miradaTimer = setTimeout(function () {
        if (!document.hidden && raiz && !sinMovimiento() && estadoUI === 'reposo' && Date.now() - ultimoPuntero > 4000) {
          var m = MIRADAS[Math.floor(Math.random() * MIRADAS.length)];
          mirar(m[0], m[1]);
        }
        planearMirada();
      }, 1800 + Math.random() * 2800);
    }

    function boton(clase, nombreIcono, etiqueta, alClic) {
      var b = el('button', clase);
      b.type = 'button';
      b.setAttribute('aria-label', etiqueta);
      b.title = etiqueta;
      if (nombreIcono) b.appendChild(icono(nombreIcono));
      if (alClic) b.addEventListener('click', alClic);
      return b;
    }

    function construir() {
      if (document.getElementById('av-root')) return;
      raiz = el('div', 'av-root');
      raiz.id = 'av-root';
      raiz.setAttribute('data-estado', 'reposo');

      // Botón flotante
      fab = el('button', 'av-fab av-fab-cara');
      fab.type = 'button';
      fab.setAttribute('aria-label', 'Hablar con el asistente de voz');
      fab.title = 'Asistente de voz (Alt+V)';
      fab.appendChild(el('span', 'av-aro'));
      fab.appendChild(el('span', 'av-aro av-aro-2'));
      var centro = el('span', 'av-fab-centro');
      centro.appendChild(personaje());
      var barras = el('span', 'av-barras');
      for (var i = 0; i < 5; i++) barras.appendChild(el('i'));
      centro.appendChild(barras);
      fab.appendChild(centro);
      fab.addEventListener('click', alTocarFab);

      // Globito de bienvenida
      pista = el('div', 'av-pista');
      pista.setAttribute('aria-live', 'off');
      pista.hidden = true;
      var pt = el('span', 'av-pista-texto', '👋 ¿Necesitas ayuda? ¡Háblame!');
      var pc = boton('av-pista-x', 'cerrar', 'Cerrar aviso', function (e) { e.stopPropagation(); ocultarPista(true); });
      pista.appendChild(pt); pista.appendChild(pc);
      pista.addEventListener('click', function () { ocultarPista(false); abrirPanel(true); mostrarSaludoInicial(); });

      // Puntito para cuando está oculto
      mini = el('button', 'av-mini');
      mini.type = 'button';
      mini.setAttribute('aria-label', 'Llamar al asistente de voz');
      mini.title = 'Llamar al asistente (Alt+V)';
      mini.appendChild(personaje());
      mini.addEventListener('click', function () { cfg.min = false; guardarCfg(); aplicarMinimizado(); abrirPanel(true); });

      // Panel
      panel = el('section', 'av-panel');
      panel.setAttribute('role', 'dialog');
      panel.setAttribute('aria-label', 'Asistente de voz Sintec');
      panel.hidden = true;

      var cab = el('header', 'av-cab');
      var avatar = el('span', 'av-avatar'); avatar.appendChild(personaje());
      var tit = el('div', 'av-cab-tit');
      tit.appendChild(el('strong', '', 'Sintec'));
      tit.appendChild(el('small', '', 'Tu asistente de voz'));
      var botones = el('div', 'av-cab-btns');
      btnVoz = boton('av-btn-cab', cfg.voz ? 'bocina' : 'mudo', cfg.voz ? 'Silenciar la voz' : 'Activar la voz', alternarVoz);
      btnAyuda = boton('av-btn-cab', 'ayuda', 'Ayuda: qué puedo decir', function () { alternarAyuda(); });
      var btnMin = boton('av-btn-cab', 'bajar', 'Ocultar asistente', function () { ocultarAsistente(); });
      var btnCerrar = boton('av-btn-cab av-solo-movil', 'cerrar', 'Cerrar', function () { cerrarPanel(); });
      botones.appendChild(btnVoz); botones.appendChild(btnAyuda); botones.appendChild(btnMin); botones.appendChild(btnCerrar);
      cab.appendChild(avatar); cab.appendChild(tit); cab.appendChild(botones);

      var cuerpo = el('div', 'av-cuerpo');
      msgs = el('div', 'av-msgs');
      msgs.setAttribute('aria-live', 'polite');
      vistaPermiso = el('div', 'av-vista av-vista-permiso'); vistaPermiso.hidden = true;
      vistaAyuda = el('div', 'av-vista av-vista-ayuda'); vistaAyuda.hidden = true;
      cuerpo.appendChild(msgs); cuerpo.appendChild(vistaPermiso); cuerpo.appendChild(vistaAyuda);

      enVivo = el('div', 'av-envivo'); enVivo.hidden = true;
      var ondas = el('span', 'av-ondas'); for (var j = 0; j < 4; j++) ondas.appendChild(el('i'));
      enVivoTexto = el('span', 'av-envivo-texto', 'Escuchando…');
      enVivo.appendChild(ondas); enVivo.appendChild(enVivoTexto);

      var forma = el('form', 'av-forma');
      forma.setAttribute('autocomplete', 'off');
      entrada = el('input', 'av-entrada');
      entrada.type = 'text';
      entrada.placeholder = 'Escribe o toca el micrófono…';
      entrada.setAttribute('aria-label', 'Escribe lo que necesitas');
      entrada.maxLength = 220;
      entrada.setAttribute('enterkeyhint', 'send');
      btnMicPanel = boton('av-btn-mic', 'mic', 'Hablar', function () { alTocarMicPanel(); });
      var btnEnv = boton('av-btn-env', 'enviar', 'Enviar', null);
      btnEnv.type = 'submit';
      forma.appendChild(btnMicPanel); forma.appendChild(entrada); forma.appendChild(btnEnv);
      forma.addEventListener('submit', function (e) {
        e.preventDefault();
        var t = entrada.value.trim();
        if (!t) return;
        entrada.value = '';
        porVoz = false;
        procesar(t, []);
      });

      pie = el('div', 'av-pie');
      pie.appendChild(document.createTextNode('Di '));
      pie.appendChild(el('b', '', '«ayuda»'));
      pie.appendChild(document.createTextNode(' para ver todo lo que entiendo'));

      panel.appendChild(cab); panel.appendChild(cuerpo); panel.appendChild(enVivo); panel.appendChild(forma); panel.appendChild(pie);

      raiz.appendChild(pista); raiz.appendChild(fab); raiz.appendChild(mini); raiz.appendChild(panel);
      document.body.appendChild(raiz);

      // Los ojos del personaje siguen el cursor (en compu) y, en reposo, miran de un lado a otro
      document.addEventListener('pointermove', seguirPuntero, { passive: true });
      planearMirada();

      // Atajos
      document.addEventListener('keydown', function (e) {
        if (e.altKey && (e.key === 'v' || e.key === 'V')) {
          e.preventDefault();
          if (cfg.min) { cfg.min = false; guardarCfg(); aplicarMinimizado(); }
          if (panel.hidden) { abrirPanel(true); alTocarMicPanel(true); } else alTocarMicPanel();
        } else if (e.key === 'Escape' && !panel.hidden) {
          if (escuchando) detenerEscucha(); else if (hablando) detenerVoz(); else cerrarPanel();
        }
      });
    }

    function estado(e) {
      estadoUI = e;
      if (!raiz) return;
      raiz.setAttribute('data-estado', e);
      // Los ojos: de frente al escuchar o hablar, hacia arriba al pensar
      if (e === 'escuchando' || e === 'hablando') mirar(0, .1);
      else if (e === 'pensando') mirar(.8, -1);
      var enMarcha = e === 'escuchando';
      if (btnMicPanel) {
        btnMicPanel.classList.toggle('av-activo', enMarcha);
        btnMicPanel.setAttribute('aria-label', enMarcha ? 'Dejar de escuchar' : 'Hablar');
        btnMicPanel.title = btnMicPanel.getAttribute('aria-label');
        btnMicPanel.replaceChild(icono(enMarcha ? 'parar' : 'mic'), btnMicPanel.firstChild);
      }
      if (fab) fab.setAttribute('aria-pressed', enMarcha ? 'true' : 'false');
    }

    function mostrarEnVivo(t) {
      if (!enVivo) return;
      enVivo.hidden = false;
      enVivoTexto.textContent = t ? t : 'Escuchando… habla ahora';
      scrollMsgs();
    }
    function ocultarEnVivo() { if (enVivo) enVivo.hidden = true; }

    function scrollMsgs() {
      if (!msgs) return;
      requestAnimationFrame(function () { msgs.scrollTop = msgs.scrollHeight; });
    }

    // Texto con **negritas** sin usar innerHTML.
    function ponerTexto(nodo, texto) {
      String(texto).split(/(\*\*[^*]+\*\*)/).forEach(function (p) {
        if (!p) return;
        if (/^\*\*[^*]+\*\*$/.test(p)) nodo.appendChild(el('strong', '', p.slice(2, -2)));
        else nodo.appendChild(document.createTextNode(p));
      });
    }

    function pintarMensaje(rol, texto, chips, guardar) {
      var fila = el('div', 'av-fila av-' + rol);
      var caja0 = el('div', 'av-caja');
      var burbuja = el('div', 'av-burbuja');
      ponerTexto(burbuja, texto);
      caja0.appendChild(burbuja);
      if (rol === 'bot' && TTS) {
        var rep = boton('av-rep', 'bocina', 'Escuchar este mensaje', function () { cfg.voz ? hablar(texto) : hablarForzado(texto); });
        caja0.appendChild(rep);
      }
      fila.appendChild(caja0);
      if (chips && chips.length) {
        var caja = el('div', 'av-chips');
        chips.forEach(function (c) {
          var b = el('button', 'av-chip', c.t);
          b.type = 'button';
          b.addEventListener('click', function () {
            Array.prototype.forEach.call(caja.children, function (x) { x.disabled = true; });
            porVoz = false;
            if (c.id) { addMsg('yo', c.t.replace(/^[^\wÁÉÍÓÚáéíóúÑñ¿¡]+/u, '')); ejecutarOpcion(c.id); }
            else if (c.cmd) procesar(c.cmd, [], c.t);
          });
          caja.appendChild(b);
        });
        fila.appendChild(caja);
      }
      msgs.appendChild(fila);
      while (msgs.children.length > 40) msgs.removeChild(msgs.firstChild);
      scrollMsgs();
      return fila;
    }

    function addMsg(rol, texto, chips) {
      ultimaAccion = Date.now();
      pintarMensaje(rol, texto, chips);
      ses.msgs.push({ r: rol, t: texto });
      guardarSes();
    }

    function hablarForzado(texto) {
      var antes = cfg.voz; cfg.voz = true;
      var p = hablar(texto);
      cfg.voz = antes;
      return p;
    }

    // El asistente dice algo (escrito + hablado). Devuelve una promesa cuando termina de hablar.
    function decir(texto, op) {
      op = op || {};
      addMsg('bot', texto, op.chips);
      ses.ultimo = op.hablar || texto;
      guardarSes();
      if (op.sinHablar) return Promise.resolve(false);
      return hablar(op.hablar || texto);
    }

    function avisoSuave(texto) {
      // Aviso corto que no cuenta como respuesta (no se repite, no se guarda).
      pintarMensaje('sis', texto);
    }

    // ---------- Panel: abrir / cerrar ----------
    function abrirPanel(guardarAbierto) {
      if (!panel) return;
      ultimaAccion = Date.now();
      cfg.min = false; aplicarMinimizado();
      panel.hidden = false;
      requestAnimationFrame(function () { raiz.classList.add('av-abierto'); });
      ocultarPista(false);
      if (guardarAbierto !== false) { ses.abierto = true; guardarSes(); }
      scrollMsgs();
    }
    function cerrarPanel() {
      detenerEscucha(); detenerVoz();
      raiz.classList.remove('av-abierto');
      setTimeout(function () { if (!raiz.classList.contains('av-abierto')) panel.hidden = true; }, 220);
      ses.abierto = false; guardarSes();
      reanudarLlamada();
    }
    function aplicarMinimizado() {
      if (!raiz) return;
      raiz.classList.toggle('av-minimizado', !!cfg.min);
    }
    function ocultarAsistente() {
      cerrarPanel();
      cfg.min = true; guardarCfg();
      aplicarMinimizado();
    }

    function alTocarFab() {
      if (panel.hidden) { abrirPanel(true); mostrarSaludoInicial(); alTocarMicPanel(true); }
      else if (hablando) { detenerVoz(); empezarEscucha(); }
      else if (escuchando) detenerEscucha();
      else empezarEscucha();
    }
    function alTocarMicPanel(soloIniciar) {
      if (hablando) { detenerVoz(); empezarEscucha(); return; }
      if (escuchando) { if (!soloIniciar) detenerEscucha(); return; }
      empezarEscucha();
    }
    function alternarVoz() {
      cfg.voz = !cfg.voz; guardarCfg();
      if (!cfg.voz) detenerVoz();
      btnVoz.replaceChild(icono(cfg.voz ? 'bocina' : 'mudo'), btnVoz.firstChild);
      btnVoz.setAttribute('aria-label', cfg.voz ? 'Silenciar la voz' : 'Activar la voz');
      btnVoz.title = btnVoz.getAttribute('aria-label');
      refrescarAjustes();
      avisoSuave(cfg.voz ? 'Voz activada' : 'Voz silenciada: te contesto por escrito');
    }

    // Globito del robot: aparece por ratos ("¿Necesitas ayuda?") con frases distintas.
    // No sale si el panel está abierto, si lo estás usando, si está oculto o si lo apagaste.
    var AVISOS_VISITA = ['👋 ¿Necesitas ayuda? ¡Háblame!', '¿Buscas un servicio o un trabajo? Te ayudo.', '¿Quieres publicar algo? Dime y te llevo.', '¿No sabes por dónde empezar? Toca y te guío.'];
    var AVISOS_USUARIO = ['👋 ¿Necesitas ayuda? ¡Háblame!', 'Dime qué buscas y lo encuentro por ti.', '¿Quieres publicar algo? Te llevo directo.', '¿Buscas un servicio o un trabajo? Pídemelo.', 'Puedo abrir tus vacantes, tu perfil y más.'];
    var indiceAviso = 0, nAvisos = 0, tPlan = null, ultimaAccion = 0;

    function puedeAvisar() {
      return !!pista && !!panel && cfg.avisos !== false && !ses.callado && panel.hidden && !cfg.min && !document.hidden &&
        !escuchando && !hablando && pista.hidden && estadoUI === 'reposo' && Date.now() - ultimaAccion > 60000;
    }
    function mostrarPista() {
      if (!puedeAvisar()) return false;
      var t = pista.querySelector('.av-pista-texto');
      var lista = (datos && datos.logueado) ? AVISOS_USUARIO : AVISOS_VISITA;
      if (t) t.textContent = lista[indiceAviso++ % lista.length];
      pista.hidden = false;
      requestAnimationFrame(function () { pista.classList.add('av-pista-ver'); });
      fab.classList.add('av-saluda');
      setTimeout(function () { fab.classList.remove('av-saluda'); }, 1700);
      clearTimeout(tAviso);
      tAviso = setTimeout(function () { ocultarPista(false); }, 8000);
      return true;
    }
    function planearAvisos(primera) {
      clearTimeout(tPlan);
      if (cfg.avisos === false || ses.callado || nAvisos >= 8) return;
      var espera = primera ? ((datos && datos.logueado) ? 4500 : 2500) : 45000 + Math.floor(Math.random() * 30000);
      tPlan = setTimeout(function () { if (mostrarPista()) nAvisos++; planearAvisos(false); }, espera);
    }
    function ocultarPista(recordar) {
      if (!pista) return;
      pista.classList.remove('av-pista-ver');
      clearTimeout(tAviso);
      setTimeout(function () {
        if (pista.classList.contains('av-pista-ver')) return; // se volvió a mostrar
        pista.hidden = true;
        if (pista.classList.contains('av-pista-resumen')) {
          pista.classList.remove('av-pista-resumen');
          var t = pista.querySelector('.av-pista-texto'); if (t) t.textContent = TEXTO_PISTA;
        }
      }, 260);
      if (recordar) { ses.callado = true; guardarSes(); }
    }

    // ---------- Vista de permiso del micrófono ----------
    function ocultarVistas() { vistaPermiso.hidden = true; vistaAyuda.hidden = true; msgs.hidden = false; }

    function mostrarPermiso(tipo) {
      abrirPanel(true);
      vistaAyuda.hidden = true;
      vistaPermiso.innerHTML = '';
      vistaPermiso.hidden = false;
      msgs.hidden = true;

      var tarjeta = el('div', 'av-permiso');
      var gran = el('div', 'av-permiso-ico'); gran.appendChild(icono(tipo === 'denied' ? 'candado' : 'mic'));
      tarjeta.appendChild(gran);
      var h = el('h3'); var p = el('p'); var pasos = null;
      var acciones = el('div', 'av-permiso-acc');

      if (tipo === 'prompt') {
        h.textContent = 'Activa tu micrófono';
        ponerTexto(p, 'Para entenderte necesito escuchar tu voz. Al tocar **«Permitir micrófono»**, tu navegador te pedirá permiso (solo la primera vez). Lo que digas se convierte en texto con el servicio de voz de tu navegador y no se guarda en SINTECZATE.');
        var ok = el('button', 'av-boton av-boton-principal', 'Permitir micrófono'); ok.type = 'button';
        ok.addEventListener('click', function () {
          ok.disabled = true; ok.textContent = 'Esperando tu permiso…';
          pedirPermiso().then(function (r) {
            if (r === 'granted') { ocultarVistas(); decir('¡Listo! Ya te escucho. ' + saludoCorto(), { chips: chipsInicio() }).then(function () { empezarEscucha(); }); }
            else mostrarPermiso(r === 'denied' ? 'denied' : 'error');
          });
        });
        var no = el('button', 'av-boton', 'Mejor escribo'); no.type = 'button';
        no.addEventListener('click', function () { ocultarVistas(); mostrarSaludoInicial(true); entrada.focus(); });
        acciones.appendChild(ok); acciones.appendChild(no);
      } else if (tipo === 'denied') {
        h.textContent = 'El micrófono está bloqueado';
        p.textContent = 'Tu navegador tiene bloqueado el micrófono para esta página. Para activarlo:';
        pasos = el('ol', 'av-pasos');
        ['Toca el candado (🔒) a la izquierda de la dirección de la página.', 'Busca «Micrófono» y cámbialo a «Permitir».', 'Toca «Volver a intentar» (o recarga la página).'].forEach(function (t) { pasos.appendChild(el('li', '', t)); });
        var re = el('button', 'av-boton av-boton-principal', 'Volver a intentar'); re.type = 'button';
        re.addEventListener('click', function () {
          pedirPermiso().then(function (r) { if (r === 'granted') { ocultarVistas(); empezarEscucha(); } else mostrarPermiso('denied'); });
        });
        var es = el('button', 'av-boton', 'Escribir en su lugar'); es.type = 'button';
        es.addEventListener('click', function () { ocultarVistas(); entrada.focus(); });
        acciones.appendChild(re); acciones.appendChild(es);
      } else if (tipo === 'inseguro') {
        h.textContent = 'El micrófono necesita una conexión segura';
        ponerTexto(p, 'Tu navegador solo permite usar el micrófono en páginas **https** o en **localhost / 127.0.0.1**. Mientras tanto puedes escribirme lo que necesites.');
        var e2 = el('button', 'av-boton av-boton-principal', 'Escribir'); e2.type = 'button';
        e2.addEventListener('click', function () { ocultarVistas(); mostrarSaludoInicial(true); entrada.focus(); });
        acciones.appendChild(e2);
      } else if (tipo === 'sinsoporte') {
        h.textContent = 'Este navegador no puede escucharte';
        ponerTexto(p, 'Para hablarme usa **Google Chrome** o **Microsoft Edge**. Mientras tanto puedes escribirme y yo te contesto en voz alta.');
        var e3 = el('button', 'av-boton av-boton-principal', 'Escribir'); e3.type = 'button';
        e3.addEventListener('click', function () { ocultarVistas(); mostrarSaludoInicial(true); entrada.focus(); });
        acciones.appendChild(e3);
      } else {
        h.textContent = 'No pude usar el micrófono';
        p.textContent = 'Revisa que tu micrófono esté conectado y que ninguna otra aplicación lo esté usando.';
        var e4 = el('button', 'av-boton av-boton-principal', 'Volver a intentar'); e4.type = 'button';
        e4.addEventListener('click', function () { ocultarVistas(); empezarEscucha(); });
        acciones.appendChild(e4);
      }
      tarjeta.appendChild(h); tarjeta.appendChild(p);
      if (pasos) tarjeta.appendChild(pasos);
      tarjeta.appendChild(acciones);
      vistaPermiso.appendChild(tarjeta);
    }

    // ---------- Ayuda ----------
    function alternarAyuda(forzar) {
      var ver = forzar !== undefined ? forzar : vistaAyuda.hidden;
      vistaPermiso.hidden = true;
      if (ver) { construirAyuda(); vistaAyuda.hidden = false; msgs.hidden = true; abrirPanel(true); vistaAyuda.scrollTop = 0; }
      else { vistaAyuda.hidden = true; msgs.hidden = false; scrollMsgs(); }
      btnAyuda.classList.toggle('av-activo', ver);
    }

    var ajusteVoz, ajusteLlamada, ajusteAvisos;

    // Lista para escoger la voz (por si el equipo tiene varias en español) y probarla
    function selectorVoz() {
      var f = el('div', 'av-ajuste av-ajuste-voz');
      var t = el('span', 'av-ajuste-t');
      t.appendChild(el('strong', '', 'Voz del asistente'));
      var detalle = el('small', '');
      t.appendChild(detalle);
      var sel = el('select', 'av-select');
      sel.setAttribute('aria-label', 'Voz del asistente');
      f.appendChild(t); f.appendChild(sel);

      function pintar() {
        sel.innerHTML = '';
        var lista = vocesEspanol();
        var auto = el('option', '', 'Automática (voz de hombre)'); auto.value = ''; sel.appendChild(auto);
        lista.forEach(function (v) {
          var o = el('option', '', v.name + (esHombre(v) ? ' · hombre' : (esMujer(v) ? ' · mujer' : '')));
          o.value = v.name; sel.appendChild(o);
        });
        sel.value = cfg.vozNombre || '';
        if (sel.value !== (cfg.vozNombre || '')) sel.value = '';
        sel.disabled = !lista.length;
        if (!vozElegida) vozElegida = elegirVoz();
        if (!TTS) detalle.textContent = 'Este navegador no puede hablar.';
        else if (!lista.length) detalle.textContent = 'Este navegador no tiene voces en español.';
        else detalle.textContent = 'Ahora uso: ' + (vozElegida ? vozElegida.name : '—') +
          (!cfg.vozNombre && vozElegida && !esHombre(vozElegida)
            ? '. No encontré voz de hombre en español en este equipo, así que le bajo el tono. Puedes instalar una en Windows (Configuración › Hora e idioma › Voz › Agregar voces › Español de México) o usar Microsoft Edge, que trae a «Jorge».'
            : '.');
      }
      sel.addEventListener('change', function () {
        cfg.vozNombre = sel.value; guardarCfg();
        vozElegida = elegirVoz(); pintar();
        hablarForzado('Hola, soy Sintec. ¿En qué te ayudo?');
      });
      refrescarSelectorVoz = pintar;
      pintar();
      return f;
    }
    function interruptor(texto, detalle, valor, alCambiar) {
      var f = el('label', 'av-ajuste');
      var t = el('span', 'av-ajuste-t');
      t.appendChild(el('strong', '', texto)); if (detalle) t.appendChild(el('small', '', detalle));
      var i = el('input'); i.type = 'checkbox'; i.checked = !!valor;
      var s = el('span', 'av-switch');
      i.addEventListener('change', function () { alCambiar(i.checked, i); });
      f.appendChild(t); f.appendChild(i); f.appendChild(s);
      f._input = i;
      return f;
    }
    function refrescarAjustes() {
      if (ajusteVoz) ajusteVoz._input.checked = !!cfg.voz;
      if (ajusteLlamada) ajusteLlamada._input.checked = !!cfg.llamada;
      if (ajusteAvisos) ajusteAvisos._input.checked = cfg.avisos !== false;
    }

    function construirAyuda() {
      vistaAyuda.innerHTML = '';
      var pag = paginaActual();
      var aqui = NLU.PAGINAS[pag] || NLU.PAGINAS[pag === 'detalle_servicio' ? 'ver_servicios' : ''] || null;
      var logueado = !!(datos && datos.logueado);

      // El logo completo de Sintec (si no carga, simplemente no se muestra)
      var logo = el('div', 'av-ayuda-logo');
      var logoImg = document.createElement('img');
      logoImg.alt = 'Sintec, tu asistente de voz';
      logoImg.draggable = false;
      logoImg.src = BASE_IMG + 'sintec-logo.webp' + CONSULTA;
      logoImg.addEventListener('error', function () { logo.remove(); });
      logo.appendChild(logoImg);
      vistaAyuda.appendChild(logo);

      var intro = el('div', 'av-ayuda-intro');
      intro.appendChild(el('h3', '', 'Qué puedo hacer por ti'));
      intro.appendChild(el('p', '', 'Toca cualquier frase para probarla, o dila con tu voz. Entiendo muchas formas de decir lo mismo.'));
      vistaAyuda.appendChild(intro);

      function grupo(titulo, ico, frases, abierto) {
        var d = el('details', 'av-grupo'); if (abierto) d.open = true;
        var s = el('summary'); s.appendChild(el('span', 'av-grupo-ico', ico)); s.appendChild(el('span', '', titulo));
        d.appendChild(s);
        var caja = el('div', 'av-chips av-chips-ayuda');
        frases.forEach(function (f) {
          var b = el('button', 'av-chip', f); b.type = 'button';
          b.addEventListener('click', function () { alternarAyuda(false); porVoz = false; procesar(f, []); });
          caja.appendChild(b);
        });
        d.appendChild(caja);
        vistaAyuda.appendChild(d);
      }

      if (aqui && aqui.aqui) grupo('En esta pantalla: ' + aqui.titulo, '📍', aqui.aqui, true);
      if (!logueado) grupo('Si es tu primera vez', '👋', ['No sé por dónde empezar', 'Quiero crear mi cuenta', 'Busco un plomero', 'Busco trabajo', 'Quiero ofrecer mis servicios', 'Necesito contratar personal'], true);

      var emojis = ['🔧', '💼', '🎚️', '📢', '🧭', '👤', '🎙️'];
      NLU.EJEMPLOS.forEach(function (g, i) { grupo(g.grupo, emojis[i] || '•', g.items, !aqui && !logueado ? false : false); });

      var ajustes = el('div', 'av-ajustes');
      ajustes.appendChild(el('h4', '', 'Ajustes'));
      ajusteVoz = interruptor('Que el asistente hable', 'Si lo apagas, solo te contesta por escrito.', cfg.voz, function (v) {
        cfg.voz = v; guardarCfg(); if (!v) detenerVoz();
        btnVoz.replaceChild(icono(cfg.voz ? 'bocina' : 'mudo'), btnVoz.firstChild);
      });
      ajustes.appendChild(ajusteVoz);
      ajustes.appendChild(selectorVoz());
      ajusteAvisos = interruptor('Que Sintec me avise «¿Necesitas ayuda?»', 'Aparece un globito de vez en cuando junto al botón. Apágalo si te estorba.', cfg.avisos !== false, function (v) {
        cfg.avisos = v; guardarCfg();
        if (v) { nAvisos = 0; planearAvisos(false); } else { clearTimeout(tPlan); if (pista) ocultarPista(false); }
      });
      ajustes.appendChild(ajusteAvisos);
      if (SR && SEGURO) {
        ajusteLlamada = interruptor('Llamarme diciendo «Hola Sintec»', 'Deja el micrófono escuchando de fondo mientras esta pestaña esté abierta (Chrome muestra un punto de grabación).', cfg.llamada, function (v) {
          if (v) {
            estadoPermiso().then(function (p) {
              if (p === 'granted') { cfg.llamada = true; guardarCfg(); reanudarLlamada(); avisoSuave('Listo: ahora puedes decir «Hola Sintec» para llamarme.'); }
              else {
                pedirPermiso().then(function (r) {
                  if (r === 'granted') { cfg.llamada = true; guardarCfg(); reanudarLlamada(); avisoSuave('Listo: ahora puedes decir «Hola Sintec» para llamarme.'); }
                  else { cfg.llamada = false; guardarCfg(); refrescarAjustes(); mostrarPermiso('denied'); }
                });
              }
            });
          } else { cfg.llamada = false; guardarCfg(); pararLlamada(); }
        });
        ajustes.appendChild(ajusteLlamada);
      }
      var atajo = el('p', 'av-nota');
      atajo.appendChild(document.createTextNode('Atajo: '));
      atajo.appendChild(el('kbd', '', 'Alt'));
      atajo.appendChild(document.createTextNode(' + '));
      atajo.appendChild(el('kbd', '', 'V'));
      atajo.appendChild(document.createTextNode(' abre el asistente y empieza a escucharte. '));
      atajo.appendChild(el('kbd', '', 'Esc'));
      atajo.appendChild(document.createTextNode(' lo cierra. Para hablarme usa Chrome o Edge.'));
      ajustes.appendChild(atajo);
      vistaAyuda.appendChild(ajustes);

      var volver = el('button', 'av-boton av-boton-principal av-volver', 'Volver a la conversación'); volver.type = 'button';
      volver.addEventListener('click', function () { alternarAyuda(false); });
      vistaAyuda.appendChild(volver);
    }

    // ====================================================================
    //  7. SALUDOS Y SUGERENCIAS
    // ====================================================================
    function nombrePersona() { return datos && datos.persona && datos.persona.nombre ? datos.persona.nombre : ''; }
    function estaLogueado() { return !!(datos && datos.logueado); }

    function saludoCorto() {
      return estaLogueado()
        ? 'Dime qué necesitas, por ejemplo «busco un plomero» o «quiero publicar una vacante».'
        : 'Te ayudo a encontrar a quien haga un trabajo, a buscar empleo o a publicar. ¿Qué necesitas?';
    }

    function chipsInicio() {
      if (!estaLogueado()) {
        return [
          { t: '🔧 Necesito a alguien que haga un trabajo', id: 'o_servicio' },
          { t: '💼 Busco trabajo', id: 'o_trabajo' },
          { t: '🧰 Quiero ofrecer mis servicios', id: 'o_ofrecer' },
          { t: '🏢 Quiero contratar personal', id: 'o_contratar' },
          { t: '📢 Quiero anunciar mi negocio', id: 'o_anunciar' }
        ];
      }
      var pag = paginaActual();
      var m = NLU.PAGINAS[pag];
      if (m && m.aqui) return m.aqui.slice(0, 4).map(function (f) { return { t: f, cmd: f }; });
      return [
        { t: 'Busco un plomero', cmd: 'busco un plomero' },
        { t: 'Busco trabajo', cmd: 'busco trabajo' },
        { t: 'Publicar un servicio', cmd: 'quiero ofrecer mis servicios' },
        { t: 'Ver todo lo que sé hacer', cmd: 'ayuda' }
      ];
    }

    var saludoDado = false;
    function mostrarSaludoInicial(forzar) {
      if (saludoDado && !forzar) return;
      if (ses.msgs.length && !forzar) { saludoDado = true; return; }
      saludoDado = true;
      cargarDatos().then(function () {
        var n = nombrePersona();
        var hola = n ? '¡Hola ' + n + '! Soy Sintec, tu asistente de voz.' : '¡Hola! Soy Sintec, tu asistente de voz.';
        decir(hola + ' ' + saludoCorto() + ' Si quieres ver todo lo que entiendo, di «ayuda».', { chips: chipsInicio(), sinHablar: true });
      });
    }

    // ====================================================================
    //  8. EL CORAZÓN: entender y ejecutar
    // ====================================================================
    // En la página principal hay dos listas (servicios y vacantes): se mira solo la que se buscó.
    function alcanceActual(tipo) {
      if (paginaActual() !== 'home') return null;
      var p = parametrosActuales();
      var vac = tipo ? tipo === 'vacantes' : !!(p.q_vacante || p.localidad_vacante_id);
      return vac ? '#listVacantes' : '#listProducts';
    }

    function tarjetas(tipo) {
      var lista = [];
      var vistos = {};
      var sel = alcanceActual(tipo);
      var zona = sel ? document.querySelector(sel) : document;
      if (!zona) return lista;
      var re = /(\/usuario\/ver_servicio\/\d+|\/usuario\/ver_vacante\/\d+|^\/servicio\/\d+|^\/vacante\/\d+)/;
      Array.prototype.forEach.call(zona.querySelectorAll('a[href]'), function (a) {
        var href = a.getAttribute('href') || '';
        var ruta = href.replace(/^https?:\/\/[^/]+/, '').split('?')[0];
        if (!re.test(ruta) || vistos[ruta]) return;
        if (a.closest('nav, header, aside, footer, .navbar, #av-root')) return;
        var tarjeta = a.closest('.product, .product-row, .vacante-card, .card, .box') || a.parentElement;
        var titulo = '';
        var h = tarjeta && tarjeta.querySelector('h1,h2,h3,h4,h5,h6,.job-title');
        if (h) titulo = (h.childNodes[0] && h.childNodes[0].nodeType === 3 ? h.childNodes[0].textContent : h.textContent).replace(/\s+/g, ' ').trim();
        if (!titulo) return;
        var precio = tarjeta.querySelector('.ve-precio strong, .currentPrice, .salary-badge, .badge-salario');
        vistos[ruta] = 1;
        lista.push({ titulo: titulo, precio: precio ? precio.textContent.replace(/\s+/g, ' ').trim() : '', href: ruta });
      });
      return lista;
    }

    function contarEnPantalla(tipo) {
      var c = document.querySelector('.ve-count strong, .bt-count strong');
      if (c) { var n = parseInt(c.textContent.replace(/\D/g, ''), 10); if (!isNaN(n)) return n; }
      return tarjetas(tipo).length;
    }

    function contexto() {
      var d = datos || {};
      var p = d.persona || {};
      var pag = paginaActual();
      return {
        logueado: !!d.logueado, modo: modoActual(), pagina: pag, params: parametrosActuales(),
        localidades: d.localidades || [], categorias: d.categorias || [], subcategorias: d.subcategorias || [],
        municipio: d.municipio, miLocalidadId: p.localidad_id || null, miLocalidad: p.localidad || null,
        hayResultados: tarjetas().length > 0, pendiente: ses.pend
      };
    }

    function sugerirDeNuevo(lista) { return lista; }

    var ultimaIntencion = null;

    function procesar(texto, alts, etiqueta) {
      texto = String(texto || '').trim();
      if (!texto) return;
      detenerEscucha(); detenerVoz();
      addMsg('yo', etiqueta ? etiqueta.replace(/^[^\wÁÉÍÓÚáéíóúÑñ¿¡]+/u, '') : texto);
      estado('pensando');
      cargarDatos().then(function () {
        var ctx = contexto();
        var intent = NLU.interpretar(texto, ctx);
        // Si no se entendió, se prueban las otras opciones que mandó el reconocedor de voz.
        if (intent.intent === 'desconocido' && alts && alts.length > 1) {
          for (var i = 1; i < alts.length; i++) {
            var otro = NLU.interpretar(alts[i], ctx);
            if (otro.intent !== 'desconocido') { intent = otro; break; }
          }
        }
        ses.pend = null;
        ultimaIntencion = intent;
        ejecutar(intent, ctx).then(function () {
          guardarSes();
          // Si el asistente hizo una pregunta y la persona estaba hablando, se queda escuchando.
          if (ses.pend && porVoz && cfg.permiso && !escuchando && !document.hidden) empezarEscucha();
          else if (estadoUI === 'pensando') estado('reposo');
        });
      });
    }

    // ----- utilidades de acción -----
    function ir(url, decirTexto, post, opciones) {
      opciones = opciones || {};
      var actualRuta = location.pathname + location.search;
      var destino = url.split('#')[0];
      var mismo = destino === actualRuta; // exacta: si solo cambian los filtros (la parte ?...), sí se recarga
      guardarPost(post || null);
      guardarSes();
      var promesa = decirTexto ? decir(decirTexto, { chips: opciones.chips }) : Promise.resolve();
      return Promise.race([promesa, esperar(3200)]).then(function () {
        detenerVoz();
        if (mismo) {
          var h = url.indexOf('#') >= 0 ? url.slice(url.indexOf('#')) : '';
          if (post) resumenPosterior(post);
          if (h) { var t = document.querySelector(h); if (t) t.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
        } else {
          location.assign(url);
        }
      });
    }

    function necesitaCuenta(accion, tipoPend, extra) {
      // Visitante que pide algo que requiere cuenta: se le explica y se le ofrece crearla.
      ses.pend = { tipo: 'confirmar', accion: accion, datos: extra || null };
      return decir(tipoPend, { chips: [{ t: 'Sí, llévame', cmd: 'sí' }, { t: 'Ahora no', cmd: 'no' }] });
    }

    function revisarCuenta(destinoInfo) {
      // Devuelve null si todo bien; o una promesa (ya se explicó el problema) si no se puede continuar.
      var d = datos || {};
      var p = d.persona;
      if (d.logueado && p && p.admin) {
        return decir('Estás con una cuenta de administrador. Esa sección es para cuentas de usuario; usa el panel de administración.', {
          chips: [{ t: 'Ir al panel de administración', cmd: 'ir al panel de administración' }]
        });
      }
      if (d.logueado && p && p.verificado === false) {
        return ir('/email/verify', 'Antes de seguir tienes que confirmar tu correo. Te llevo a la pantalla para hacerlo.');
      }
      return null;
    }

    function irADestino(clave, intent, ctx, mensajeExtra) {
      var d = DESTINOS[clave];
      if (!d) return decir('No encuentro esa pantalla.');
      if (!d.publico && !ctx.logueado) {
        return necesitaCuenta('irAcceso', 'Para entrar a ' + d.nombre + ' primero necesitas iniciar sesión (o crear tu cuenta gratis). ¿Te llevo?');
      }
      if (!d.publico) {
        var prob = revisarCuenta(d);
        if (prob) return prob;
      }
      var cambio = d.modo ? fijarModo(d.modo) : false;
      var nota = cambio ? ' Cambié a modo ' + nombreModo(d.modo) + ' para mostrártela.' : '';
      var texto = 'Abriendo ' + d.nombre + '. ' + (d.que || '') + nota + (mensajeExtra ? ' ' + mensajeExtra : '');
      var post = d.despues ? { despues: d.despues } : null;
      return ir(d.url, texto.trim(), post);
    }

    // ----- ejecutar -----
    function ejecutar(intent, ctx) {
      switch (intent.intent) {
        case 'nada': return Promise.resolve();
        case 'llamado': return decir('Dime, te escucho.', { sinHablar: !porVoz }).then(function () { if (porVoz) empezarEscucha(); });
        case 'saludo': {
          var n = nombrePersona();
          var base = intent.estado ? '¡Muy bien, gracias! ' : (n ? '¡Hola ' + n + '! ' : '¡Hola! ');
          return decir(base + 'Dime qué necesitas.', { chips: chipsInicio() });
        }
        case 'gracias': return decir(azar(['¡Con gusto!', '¡De nada!', 'Para eso estoy.']) + ' Aquí sigo si necesitas algo más.');
        case 'adios': return decir('¡Hasta luego! Cuando me necesites, toca el puntito o presiona Alt+V.').then(function () { ocultarAsistente(); });
        case 'ocultar': return ocultar();
        case 'callar': detenerVoz(); return Promise.resolve();
        case 'silenciar': {
          cfg.voz = false; guardarCfg(); detenerVoz(); refrescarAjustes();
          btnVoz.replaceChild(icono('mudo'), btnVoz.firstChild);
          return decir('Listo, ya no hablo; te contesto por escrito. Di «activa la voz» cuando quieras que vuelva a hablar.', { sinHablar: true });
        }
        case 'activarVoz': {
          cfg.voz = true; guardarCfg(); refrescarAjustes();
          btnVoz.replaceChild(icono('bocina'), btnVoz.firstChild);
          return decir('Voz activada. ¿Qué necesitas?');
        }
        case 'probarMic': return probarMicrofono();
        case 'repetir': return ses.ultimo ? decir(ses.ultimo, { sinHablar: false }) : decir('Todavía no te he dicho nada. ¿Qué necesitas?');
        case 'ayuda': alternarAyuda(true); return decir('Aquí tienes todo lo que puedo hacer. Toca una frase para probarla.', { sinHablar: false });
        case 'identidad': return decir('Soy Sintec, el asistente de voz de SINTECZATE. Te ayudo a buscar servicios y vacantes, a publicar y a moverte por la página, solo con tu voz.');
        case 'cancelar': return decir('Listo, lo dejamos así. ¿Algo más?');
        case 'si': return confirmar(true, intent, ctx);
        case 'no': return confirmar(false, intent, ctx);
        case 'elegir': return ejecutarOpcion(intent.id);
        case 'atras': return decir('Regresando.').then(function () { history.back(); });
        case 'recargar': return decir('Actualizando la página.').then(function () { location.reload(); });
        case 'desplazar': {
          window.scrollBy({ top: intent.dir === 'abajo' ? Math.round(window.innerHeight * 0.8) : -Math.round(window.innerHeight * 0.8), behavior: 'smooth' });
          return Promise.resolve();
        }
        case 'cerrarSesion': return pedirCerrarSesion(ctx);
        case 'salir': return preguntarSalir();
        case 'abrirNotificaciones': return abrirNotificaciones(ctx);
        case 'cambiarModo': return cambiarModo(intent, ctx);
        case 'queEsto': return explicarPantalla(ctx);
        case 'orientar': return orientar(intent, ctx);
        case 'ir': return irADestino(intent.destino, intent, ctx);
        case 'buscar': return buscar(intent, ctx);
        case 'publicar': return publicar(intent, ctx);
        case 'abrirResultado': return abrirResultado(intent);
        case 'leerResultados': return leerResultados();
        case 'contarResultados': return contarYDecir();
        case 'quitarFiltros': return quitarFiltros(ctx);
        case 'desconocido': return noEntendi(intent, ctx);
        default: return noEntendi(intent, ctx);
      }
    }

    // ----- prueba del micrófono: barra de nivel en vivo + nombre del micrófono que usa el navegador -----
    var probandoMic = false;
    function probarMicrofono() {
      if (!SEGURO) return decir('Para usar el micrófono la página tiene que abrirse en https o en localhost.');
      var md = navigator.mediaDevices;
      var AC = window.AudioContext || window.webkitAudioContext;
      if (!md || !md.getUserMedia || !AC) return decir('Este navegador no me deja probar el micrófono. Prueba con Chrome o Edge.');
      if (probandoMic) return Promise.resolve();
      probandoMic = true;
      detenerEscuchaSuave(); pararLlamada(); detenerVoz();

      var fila = pintarMensaje('bot', 'Vamos a probar tu micrófono. Di «uno, dos, tres» en voz normal y mira la barra. Dura unos 6 segundos.', null);
      var burbuja = fila.querySelector('.av-burbuja');
      var nivel = el('div', 'av-nivel'), barra = el('i');
      nivel.appendChild(barra); burbuja.appendChild(nivel);
      var etiqueta = el('div', 'av-nivel-txt', 'Abriendo el micrófono…');
      burbuja.appendChild(etiqueta);
      scrollMsgs();

      function limpiar() { probandoMic = false; }
      function ayudaMic() {
        return 'Revisa esto:\n1) En Windows: Configuración → Sistema → Sonido → Entrada. Habla y mira si se mueve la barra «Probar el micrófono»; si no, elige otro micrófono.\n' +
          '2) Windows → Privacidad y seguridad → Micrófono: activa el acceso para aplicaciones de escritorio.\n' +
          '3) En el navegador, toca el candado junto a la dirección y deja el micrófono en «Permitir».';
      }

      return md.getUserMedia({ audio: true }).then(function (stream) {
        cfg.permiso = true; guardarCfg();
        var pista = stream.getAudioTracks && stream.getAudioTracks()[0];
        var nombre = pista && pista.label ? pista.label : '';
        etiqueta.textContent = nombre ? 'Usando: ' + nombre : 'Habla ahora…';
        var ac = new AC(), analizador = ac.createAnalyser(), datos;
        try { ac.createMediaStreamSource(stream).connect(analizador); } catch (e) { /* nada */ }
        analizador.fftSize = 1024; datos = new Uint8Array(analizador.fftSize);
        var maximo = 0, suave = 0, fin = Date.now() + 6000;

        return new Promise(function (listo) {
          (function paso() {
            analizador.getByteTimeDomainData(datos);
            var pico = 0;
            for (var i = 0; i < datos.length; i++) { var v = Math.abs(datos[i] - 128) / 128; if (v > pico) pico = v; }
            if (pico > maximo) maximo = pico;
            suave = Math.max(pico, suave * 0.93); // la barra baja despacio, así se ve mejor
            barra.style.width = Math.min(100, Math.round(suave * 260)) + '%';
            if (Date.now() < fin) requestAnimationFrame(paso); else listo();
          })();
        }).then(function () {
          try { stream.getTracks().forEach(function (t) { t.stop(); }); ac.close(); } catch (e) { /* nada */ }
          barra.style.width = '0%';
          limpiar();
          var donde = nombre ? ' («' + nombre + '»)' : '';
          if (maximo < 0.03) {
            return decir('No detecté nada de sonido en tu micrófono' + donde + '. ' + ayudaMic() + '\nCuando lo cambies, vuelve a probar.', {
              hablar: 'No detecté sonido en tu micrófono. Te dejé por escrito los pasos para revisarlo.',
              chips: [{ t: '🎙️ Volver a probar', cmd: 'probar micrófono' }]
            });
          }
          if (maximo < 0.09) {
            return decir('Sí te detecto, pero muy bajito' + donde + '. Acércate más al micrófono o sube el volumen de entrada en Windows (Configuración → Sistema → Sonido → Entrada). Luego vuelve a probar.', {
              hablar: 'Te detecto, pero muy bajito. Acércate al micrófono o sube el volumen de entrada.',
              chips: [{ t: '🎙️ Volver a probar', cmd: 'probar micrófono' }]
            });
          }
          sinVozSeguidas = 0;
          return decir('¡Tu micrófono funciona bien!' + donde + ' Para hablarme: toca el botón, espera a ver «Escuchando…», di tu frase completa y espera 1 o 2 segundos.', {
            hablar: 'Tu micrófono funciona bien. Toca el botón, espera a que diga escuchando, y habla.'
          });
        });
      }).catch(function (e) {
        limpiar();
        if (e && (e.name === 'NotAllowedError' || e.name === 'SecurityError')) { cfg.permiso = false; guardarCfg(); mostrarPermiso('denied'); return; }
        if (e && (e.name === 'NotFoundError' || e.name === 'OverconstrainedError')) {
          return decir('Windows no encuentra ningún micrófono. Conecta uno o revisa en Configuración → Sistema → Sonido → Entrada que haya un dispositivo activo.');
        }
        return decir('No pude abrir el micrófono (' + ((e && e.name) || 'error') + '). Cierra otras aplicaciones que lo estén usando (Zoom, Teams, otra pestaña) y vuelve a probar.', { chips: [{ t: '🎙️ Volver a probar', cmd: 'probar micrófono' }] });
      });
    }

    function ocultar() {
      var primera = !cfg.visto;
      cfg.visto = true; guardarCfg();
      var texto = 'Me oculto. Para llamarme toca el puntito de la esquina o presiona Alt+V' + (SR && SEGURO && !cfg.llamada ? ', o activa «Hola Sintec» en Ayuda para que vuelva cuando me llames' : '') + '.';
      return decir(primera ? texto : 'Me oculto. Toca el puntito o presiona Alt+V para llamarme.').then(function () { ocultarAsistente(); });
    }

    function confirmar(si, intent, ctx) {
      var p = intent.pend;
      if (!p) return decir(si ? 'Muy bien. ¿Qué necesitas?' : 'De acuerdo.');
      if (!si) return decir('De acuerdo, no lo hago. ¿Algo más?');
      switch (p.accion) {
        case 'irAcceso': return irADestino('acceso', null, ctx);
        case 'irRegistro': return irADestino('registro', null, ctx);
        case 'cerrarSesion': return cerrarSesion();
        case 'buscarSugerencia': return buscar({ intent: 'buscar', tipo: 'servicios', params: { q: p.datos.q }, quitar: [], refinar: false, etiquetas: {} }, ctx);
        case 'quitarFiltros': return quitarFiltros(ctx, true);
        case 'ocultar': return ocultar();
        case 'luego_vacante': return publicar({ tipo: 'vacante' }, ctx);
        case 'luego_servicio': return publicar({ tipo: 'servicio' }, ctx);
        default: return decir('Listo.');
      }
    }

    // ----- cuenta -----
    function pedirCerrarSesion(ctx) {
      if (!ctx.logueado) return decir('Todavía no has iniciado sesión.');
      ses.pend = { tipo: 'confirmar', accion: 'cerrarSesion' };
      return decir('¿Seguro que quieres cerrar tu sesión?', { chips: [{ t: 'Sí, cerrar sesión', cmd: 'sí' }, { t: 'No', cmd: 'no' }] });
    }
    function cerrarSesion() {
      return decir('Cerrando tu sesión. ¡Hasta pronto!').then(function () {
        return fetch('/logout', { method: 'POST', credentials: 'same-origin', headers: { 'X-CSRF-TOKEN': csrf(), 'X-XSRF-TOKEN': csrf(), 'Accept': 'application/json' } });
      }).catch(function () { /* nada */ }).then(function () {
        try { sessionStorage.removeItem('av_ses'); sessionStorage.removeItem('av_datos'); } catch (e) { /* nada */ }
        location.href = '/';
      });
    }
    function preguntarSalir() {
      ses.pend = { tipo: 'elegir', opciones: OPCIONES.salir };
      return decir('¿Quieres cerrar tu sesión o solo que me oculte?', { chips: [{ t: 'Cerrar sesión', id: 's_sesion' }, { t: 'Solo ocultarte', id: 's_ocultar' }] });
    }
    function abrirNotificaciones(ctx) {
      if (!ctx.logueado) return decir('Las notificaciones aparecen cuando inicias sesión.');
      var campana = document.querySelector('.ntf-btn');
      if (campana) {
        cerrarPanel();
        setTimeout(function () { campana.click(); campana.scrollIntoView({ block: 'nearest' }); }, 250);
        return Promise.resolve();
      }
      return decir('La campana de notificaciones está en el menú de arriba. Si no la ves, entra a tu inicio.', { chips: [{ t: 'Ir a mi inicio', cmd: 'ir al inicio' }] });
    }
    function cambiarModo(intent, ctx) {
      if (!ctx.logueado) return necesitaCuenta('irAcceso', 'Los modos Trabajador y Empleador se usan con tu cuenta. ¿Quieres iniciar sesión?');
      var prob = revisarCuenta();
      if (prob) return prob;
      var modo = intent.modo;
      fijarModo(modo);
      var d = modo === 'employer' ? DESTINOS.inicio_empleador : DESTINOS.inicio_trabajador;
      var que = modo === 'employer'
        ? 'Como empleador publicas vacantes, ves postulantes y buscas trabajo para ti.'
        : 'Como trabajador ofreces tus servicios, ves solicitudes y buscas profesionales.';
      return ir(d.url, 'Cambiando a modo ' + nombreModo(modo) + '. ' + que);
    }

    // ----- explicaciones -----
    var TEXTO_PAGINAS = {
      detalle_servicio: 'Aquí ves el detalle de un servicio: descripción, precio, reseñas y el botón para contactar a la persona.',
      detalle_vacante: 'Aquí ves el detalle de una vacante: lo que piden, el pago, las fechas y el botón «Postularme».',
      detalle_servicio_publico: 'Aquí ves el detalle de un servicio. Para contactar a la persona necesitas iniciar sesión.',
      detalle_vacante_publico: 'Aquí ves el detalle de una vacante. Para postularte necesitas iniciar sesión.',
      cv: 'Aquí armas o revisas un currículum. Llena tus datos paso a paso y guárdalo.',
      otra: 'Estás en una pantalla de SINTECZATE.'
    };
    function explicarPantalla(ctx) {
      var pag = ctx.pagina;
      var clave = pag === 'home' ? 'inicio_publico' : pag;
      var d = DESTINOS[clave];
      var texto;
      if (d) texto = 'Estás en «' + d.pantalla + '». ' + d.que;
      else texto = TEXTO_PAGINAS[pag] || TEXTO_PAGINAS.otra;
      var m = NLU.PAGINAS[pag];
      var chips = m && m.aqui ? m.aqui.slice(0, 3).map(function (f) { return { t: f, cmd: f }; }) : chipsInicio();
      if (m && m.aqui) texto += ' Aquí puedes decir, por ejemplo: «' + m.aqui[0] + '».';
      return decir(texto, { chips: chips });
    }

    // ----- orientar: quien no sabe qué hacer -----
    var OPCIONES = {
      orientar: [
        { id: 'o_servicio', claves: 'un profesional|profesionales|quien me haga|que haga un trabajo|que me haga|servicios' },
        { id: 'o_trabajo', claves: 'vacantes|busco trabajo|buscar trabajo|empleo|chamba|trabajo' },
        { id: 'o_ofrecer', claves: 'ofrecer|mis servicios|ofrezco|que me contraten|trabajar' },
        { id: 'o_contratar', claves: 'contratar|personal|empleados|trabajadores|gente' },
        { id: 'o_anunciar', claves: 'anunciar|anuncio|publicidad|negocio|promocionar' }
      ],
      buscar: [
        { id: 'b_servicio', claves: 'profesional|profesionales|servicio|servicios|quien haga|alguien' },
        { id: 'b_trabajo', claves: 'empleo|trabajo|vacante|vacantes|chamba' }
      ],
      publicar: [
        { id: 'p_vacante', claves: 'vacante|vacantes|contratar|personal|empleados|trabajadores' },
        { id: 'p_servicio', claves: 'servicio|servicios|ofrecer|oficio|mi trabajo|trabajar' },
        { id: 'p_anuncio', claves: 'anuncio|anuncios|publicidad|negocio|promocionar|anunciar' }
      ],
      contratar: [
        { id: 'c_vacante', claves: 'vacante|publicar|publicarla' },
        { id: 'c_ver', claves: 'ver|profesionales|buscar|servicios|lista' }
      ],
      salir: [
        { id: 's_sesion', claves: 'sesion|cerrar|cuenta' },
        { id: 's_ocultar', claves: 'ocultar|ocultate|asistente|esconder|solo' }
      ]
    };

    function orientar(intent, ctx) {
      if (intent.motivo === 'buscar') {
        ses.pend = { tipo: 'elegir', opciones: OPCIONES.buscar };
        return decir('¿Qué quieres buscar: un profesional que haga un trabajo, o un empleo?', {
          chips: [{ t: '🔧 Un profesional', id: 'b_servicio' }, { t: '💼 Un empleo', id: 'b_trabajo' }]
        });
      }
      ses.pend = { tipo: 'elegir', opciones: OPCIONES.orientar };
      var intro = ctx.logueado
        ? 'Te ayudo a empezar. ¿Qué quieres hacer?'
        : 'Con gusto te ayudo a empezar. En SINTECZATE puedes encontrar a alguien que haga un trabajo, buscar empleo, ofrecer tus servicios, contratar personal o anunciar tu negocio. ¿Cuál necesitas?';
      return decir(intro, { chips: chipsInicioFijos() });
    }
    function chipsInicioFijos() {
      return [
        { t: '🔧 Necesito a alguien que haga un trabajo', id: 'o_servicio' },
        { t: '💼 Busco trabajo', id: 'o_trabajo' },
        { t: '🧰 Quiero ofrecer mis servicios', id: 'o_ofrecer' },
        { t: '🏢 Quiero contratar personal', id: 'o_contratar' },
        { t: '📢 Quiero anunciar mi negocio', id: 'o_anunciar' }
      ];
    }

    function ejecutarOpcion(id) {
      ses.pend = null;
      var ctx = contexto();
      switch (id) {
        case 'o_servicio':
        case 'b_servicio':
          ses.pend = { tipo: 'preguntar', para: 'servicios' };
          return decir('¿Qué necesitas? Dime el trabajo, por ejemplo: plomero, electricista, albañil o pintor. También puedes agregar la localidad o cuánto quieres pagar.', {
            chips: [{ t: 'Plomero', cmd: 'plomero' }, { t: 'Electricista', cmd: 'electricista' }, { t: 'Albañil', cmd: 'albañil' }, { t: 'Ver todos', cmd: 'ver todos los servicios' }]
          }).then(seguirEscuchando);
        case 'o_trabajo':
        case 'b_trabajo':
          ses.pend = { tipo: 'preguntar', para: 'vacantes' };
          return decir('¿De qué te gustaría trabajar? Di por ejemplo «ayudante», «mesero» o «albañil». O di «todas» para ver todas las vacantes.', {
            chips: [{ t: 'Ayudante', cmd: 'ayudante' }, { t: 'Mesero', cmd: 'mesero' }, { t: 'Ver todas', cmd: 'ver las vacantes' }]
          }).then(seguirEscuchando);
        case 'o_ofrecer':
        case 'p_servicio': return publicar({ tipo: 'servicio', como: true }, ctx);
        case 'o_contratar':
        case 'p_vacante':
        case 'c_vacante': return publicar({ tipo: 'vacante', como: true }, ctx);
        case 'o_anunciar':
        case 'p_anuncio': return publicar({ tipo: 'anuncio', como: true }, ctx);
        case 'c_ver': return buscar({ intent: 'buscar', tipo: 'servicios', params: {}, quitar: [], refinar: false, etiquetas: {}, sinDetalle: true }, ctx);
        case 's_sesion': return pedirCerrarSesion(ctx);
        case 's_ocultar': return ocultar();
        default: return decir('No encontré esa opción.');
      }
    }
    function seguirEscuchando() {
      if (porVoz && cfg.permiso && !escuchando) empezarEscucha();
    }

    // ----- publicar -----
    function publicar(intent, ctx) {
      var tipo = intent.tipo;
      if (!tipo) {
        ses.pend = { tipo: 'elegir', opciones: OPCIONES.publicar };
        return decir('¿Qué quieres publicar? Una **vacante** (si buscas contratar a alguien), un **servicio** (si ofreces lo que sabes hacer) o un **anuncio** (publicidad para tu negocio).', {
          chips: [{ t: '🏢 Una vacante', id: 'p_vacante' }, { t: '🧰 Mi servicio', id: 'p_servicio' }, { t: '📢 Un anuncio', id: 'p_anuncio' }],
          hablar: '¿Qué quieres publicar? Una vacante, si buscas contratar a alguien; un servicio, si ofreces lo que sabes hacer; o un anuncio, que es publicidad para tu negocio.'
        });
      }
      if (intent.porContratar && tipo === 'vacante') {
        ses.pend = { tipo: 'elegir', opciones: OPCIONES.contratar };
        return decir('Para contratar gente puedes publicar una vacante y los interesados se postulan, o ver los profesionales que ya ofrecen sus servicios. ¿Qué prefieres?', {
          chips: [{ t: '📝 Publicar una vacante', id: 'c_vacante' }, { t: '🔎 Ver profesionales', id: 'c_ver' }]
        });
      }
      var clave = tipo === 'vacante' ? 'publicar_vacante' : tipo === 'servicio' ? 'publicar_servicio' : 'anunciar';
      var d = DESTINOS[clave];
      var explica = {
        servicio: 'Para ofrecer tus servicios te llevo a «Publicar empleo». Ahí pones qué haces, tu precio y dónde trabajas, y los clientes te contactan.',
        vacante: 'Para contratar personal te llevo a «Publicar vacante». Ahí describes el trabajo, el pago y cuántas personas necesitas, y los interesados se postulan.',
        anuncio: 'Para anunciar tu negocio con publicidad te llevo a «Anunciar». Ahí eliges tu espacio y mandas tu solicitud.'
      }[tipo];

      if (tipo !== 'anuncio' && !ctx.logueado) {
        try { sessionStorage.setItem('av_luego', JSON.stringify({ tipo: tipo, t: Date.now() })); } catch (e) { /* nada */ }
        ses.pend = { tipo: 'confirmar', accion: 'irRegistro' };
        return decir('Para ' + (tipo === 'vacante' ? 'publicar una vacante' : 'ofrecer tus servicios') + ' necesitas una cuenta (es gratis). ¿Te llevo a crearla? Después te llevo directo a ' + d.nombre + '.', {
          chips: [{ t: 'Sí, crear mi cuenta', cmd: 'sí' }, { t: 'Ya tengo cuenta', cmd: 'ya tengo cuenta' }, { t: 'Ahora no', cmd: 'no' }]
        });
      }
      if (tipo !== 'anuncio') {
        var prob = revisarCuenta(d);
        if (prob) return prob;
      }
      var cambio = d.modo ? fijarModo(d.modo) : false;
      var nota = cambio ? ' (Cambié a modo ' + nombreModo(d.modo) + '.)' : '';
      return ir(d.url, explica + nota);
    }

    // ----- buscar -----
    function buscar(intent, ctx) {
      var logueado = ctx.logueado;
      if (logueado) {
        var prob = revisarCuenta();
        if (prob) return prob;
      }
      var tipo = intent.tipo;
      var params = NLU.fusionarParams(ctx.params, intent);
      var intentFinal = { tipo: tipo, params: params, etiquetas: intent.etiquetas };
      var notas = [];

      // Cerca de mí sin localidad en el perfil / visitante
      if (intent.sinLocalidadParaCerca || intent.miLocalidadSinDato) {
        if (logueado) notas.push('Aún no has elegido tu localidad en tu perfil; hazlo para ver primero lo más cercano.');
        else { delete params.orden; notas.push('Para saber qué te queda cerca, dime tu localidad (por ejemplo «en Xaltipan»).'); }
      }
      if (!logueado) {
        var extras = ['precio_min', 'precio_max', 'salario_min', 'salario_max', 'orden', 'contrato', 'tipo_pago', 'experiencia'];
        var usados = extras.filter(function (k) { return params[k] !== undefined; });
        if (usados.length) {
          extras.forEach(function (k) { delete params[k]; });
          notas.push('Los filtros de precio y orden se activan cuando inicias sesión (es gratis).');
        }
      }
      var cambioModo = false;
      if (logueado) cambioModo = fijarModo(tipo === 'vacantes' ? 'employer' : 'worker');

      var url = NLU.urlBusqueda(intentFinal, { logueado: logueado });
      var descr = NLU.describir(intentFinal, { localidades: ctx.localidades });
      var cosa = tipo === 'vacantes' ? 'vacantes' : 'servicios';
      var antes = descr ? (tipo === 'vacantes' ? 'Buscando vacantes de ' : 'Buscando ') + descr : (tipo === 'vacantes' ? 'Abriendo todas las vacantes' : 'Abriendo todos los servicios');
      if (descr && tipo === 'vacantes' && !params.q) antes = 'Buscando vacantes ' + descr;
      if (cambioModo) notas.push('Cambié a modo ' + nombreModo(tipo === 'vacantes' ? 'employer' : 'worker') + ' porque aquí se ven ' + (tipo === 'vacantes' ? 'las vacantes.' : 'los profesionales.'));
      if (intent.refinar && descr) antes = 'Listo, ajusto la búsqueda: ' + descr;
      if (intent.quitarTodo || (intent.quitar && intent.quitar.length && !descr)) antes = 'Quitando ese filtro';

      var post = {
        tipo: tipo, descr: descr, contratar: !!intent.contratar, alt: intent.alternativas || [], intento: 0,
        params: params, logueado: logueado, notas: notas.slice(0, 1)
      };
      return ir(url, antes + '.' + (notas.length ? ' ' + notas.join(' ') : ''), post);
    }

    function quitarFiltros(ctx, soloSugerido) {
      var pag = ctx.pagina;
      var ruta = pag === 'ver_servicios' ? '/usuario/verEmpleos' : pag === 'buscar_vacantes' ? '/usuario/buscar-talento' : null;
      if (!ruta && pag === 'home') ruta = '/';
      if (!ruta) return decir('Aquí no hay filtros puestos. Dime qué quieres buscar, por ejemplo «busco un plomero».');
      return ir(ruta, 'Listo, quité todos los filtros.', { tipo: pag === 'buscar_vacantes' ? 'vacantes' : 'servicios', descr: '', alt: [], intento: 9, params: {}, logueado: ctx.logueado });
    }

    function noEntendi(intent, ctx) {
      ses.fallos = (ses.fallos || 0) + 1;
      if (intent.sugerencia) {
        ses.pend = { tipo: 'confirmar', accion: 'buscarSugerencia', datos: { q: intent.sugerencia } };
        return decir('No estoy seguro de haberte entendido. ¿Quieres que busque «' + intent.sugerencia + '»?', { chips: [{ t: 'Sí, buscar', cmd: 'sí' }, { t: 'No', cmd: 'no' }] });
      }
      if (ses.fallos >= 2) {
        ses.fallos = 0;
        return decir('Perdón, no te entendí. Prueba con algo como «busco un plomero», «busco trabajo», «quiero publicar» o «ir a mi perfil». Si prefieres, te muestro todo lo que sé hacer.', {
          chips: [{ t: 'Ver ayuda', cmd: 'ayuda' }, { t: 'No sé por dónde empezar', cmd: 'no sé por dónde empezar' }]
        });
      }
      return decir('No te entendí bien. Puedes decir, por ejemplo, «busco un plomero» o «ir a mi perfil». Di «ayuda» para ver más.');
    }

    // ----- resultados en pantalla -----
    function abrirResultado(intent) {
      var lista = tarjetas();
      if (!lista.length) return decir('No veo resultados en esta pantalla. Prueba primero con una búsqueda, por ejemplo «busco un plomero».');
      var i = intent.n === -1 ? lista.length - 1 : intent.n;
      var t = lista[i];
      if (!t) return decir('Solo veo ' + lista.length + ' resultado' + (lista.length === 1 ? '' : 's') + '.');
      var extra = intent.postular ? ' Ahí toca el botón para ' + (/vacante/.test(t.href) ? 'postularte.' : 'contactarlo.') : '';
      return ir(t.href, 'Abriendo «' + t.titulo + '».' + extra);
    }
    function leerResultados() {
      var lista = tarjetas();
      if (!lista.length) return decir('No veo resultados en esta pantalla. Dime qué quieres buscar.');
      var primeros = lista.slice(0, 3).map(function (t, i) {
        return (['El primero', 'El segundo', 'El tercero'][i]) + ': ' + t.titulo + (t.precio ? ', ' + t.precio : '') + '.';
      });
      var n = contarEnPantalla();
      return decir('Hay ' + n + ' resultado' + (n === 1 ? '' : 's') + '. ' + primeros.join(' ') + ' Di «abre el primero» para verlo.', {
        chips: [{ t: 'Abre el primero', cmd: 'abre el primero' }].concat(lista.length > 1 ? [{ t: 'Abre el segundo', cmd: 'abre el segundo' }] : [])
      });
    }
    function contarYDecir() {
      var n = contarEnPantalla();
      return decir(n ? 'Veo ' + n + ' resultado' + (n === 1 ? '' : 's') + ' en esta pantalla.' : 'No veo resultados en esta pantalla.');
    }

    // ====================================================================
    //  9. DESPUÉS DE CARGAR UNA PÁGINA: resumen de lo que se encontró
    // ====================================================================
    function resumenPosterior(post) {
      guardarPost(null);
      if (!post) return;
      if (post.despues && post.despues.clic) {
        var objetivo = document.querySelector(post.despues.clic);
        if (objetivo) setTimeout(function () { objetivo.click(); }, 200);
        return;
      }
      var n = contarEnPantalla(post.tipo);
      var cosa = post.tipo === 'vacantes' ? ['vacante', 'vacantes'] : ['servicio', 'servicios'];
      // En la página principal hay dos listas: se va a la que se buscó (vacantes o servicios), no a la primera que aparezca.
      var zona = document.querySelector(alcanceActual(post.tipo) || '#veResultados, #btResultados');
      if (zona) setTimeout(function () { zona.scrollIntoView({ behavior: 'smooth', block: 'start' }); }, 250);
      var movil = esMovil();
      if (!movil || n === 0) abrirPanelSiHacia();

      if (n === 0) {
        var alt = (post.alt || []).filter(Boolean);
        if (alt.length && post.intento < 2 && post.params && post.params.q) {
          var siguiente = alt[0];
          var p2 = Object.assign({}, post.params, { q: siguiente });
          var url = NLU.urlBusqueda({ tipo: post.tipo, params: p2 }, { logueado: post.logueado });
          var original = post.descr0 || post.descr || post.params.q;
          // Si lo que se prueba es solo el pedazo inicial de la palabra (plom de plomero), no se dice tal cual.
          var amplia = NLU.sinAcentos(String(post.params.q)).indexOf(NLU.sinAcentos(String(siguiente))) === 0;
          var aviso = amplia
            ? 'No encontré «' + post.params.q + '» tal cual. Pruebo con una búsqueda más amplia.'
            : 'No encontré «' + post.params.q + '» tal cual. Pruebo con «' + siguiente + '».';
          return ir(url, aviso, Object.assign({}, post, { params: p2, alt: alt.slice(1), intento: post.intento + 1, descr: amplia ? post.descr : (post.descr && post.descr.indexOf(post.params.q) >= 0 ? post.descr.replace(post.params.q, siguiente) : siguiente), descr0: original }));
        }
        ses.pend = { tipo: 'confirmar', accion: 'quitarFiltros' };
        var hayFiltros = post.params && Object.keys(post.params).length > 0;
        var buscado = post.descr0 || post.descr;
        return decir('No encontré ' + cosa[1] + (buscado ? ' de «' + buscado + '»' : '') + ' por ahora.' + (hayFiltros ? ' ¿Quito los filtros para ver todo lo que hay?' : ' Puedes volver a intentar más tarde.'), {
          chips: hayFiltros ? [{ t: 'Sí, quitar filtros', cmd: 'sí' }, { t: 'No', cmd: 'no' }] : []
        });
      }
      var texto = 'Encontré ' + n + ' ' + (n === 1 ? cosa[0] : cosa[1]) + (post.descr ? ' de ' + post.descr : '') + '.';
      var chips = [{ t: '🔊 Léeme los resultados', cmd: 'léeme los resultados' }, { t: 'Abre el primero', cmd: 'abre el primero' }];
      if (post.contratar) {
        texto += ' Si no encuentras a la persona ideal, también puedes publicar una vacante y que ellos se postulen: di «publicar vacante».';
        chips.push({ t: '📝 Publicar vacante', cmd: 'quiero publicar una vacante' });
      } else texto += ' Di «léeme los resultados» o «abre el primero».';
      chips.push({ t: 'Quitar filtros', cmd: 'limpiar filtros' });
      decir(texto, { chips: chips });
      if (movil) {
        if (!panel.hidden) ocultarPanelSolo();
        avisoResultado(texto.split(' Di «')[0].split(' Si no encuentras')[0]);
      }
    }
    function abrirPanelSiHacia() {
      if (ses.abierto) { abrirPanel(true); }
    }

    // En el celular el panel ocupa casi toda la pantalla y taparía los resultados:
    // al terminar una búsqueda se esconde y el resumen queda en un globito junto al botón.
    var TEXTO_PISTA = '👋 ¿Necesitas ayuda? ¡Háblame!';
    var tAviso = null;
    function esMovil() { return !!(window.matchMedia && window.matchMedia('(max-width: 600px)').matches); }
    function ocultarPanelSolo() {
      raiz.classList.remove('av-abierto');
      setTimeout(function () { if (!raiz.classList.contains('av-abierto')) panel.hidden = true; }, 220);
      ses.abierto = false; guardarSes();
    }
    function avisoResultado(texto) {
      if (!pista || cfg.min) return;
      var t = pista.querySelector('.av-pista-texto');
      if (t) t.textContent = texto.length > 150 ? texto.slice(0, 147) + '…' : texto;
      pista.classList.add('av-pista-resumen');
      pista.hidden = false;
      requestAnimationFrame(function () { pista.classList.add('av-pista-ver'); });
      clearTimeout(tAviso);
      tAviso = setTimeout(function () { ocultarPista(false); }, 11000);
    }

    // ====================================================================
    //  10. ARRANQUE
    // ====================================================================
    construir();
    aplicarMinimizado();
    estado('reposo');

    // Si la página tiene una barra fija abajo (el menú del celular de la página principal),
    // el botón se sube para quedar arriba de ella y no taparla.
    function ajustarAbajo() {
      var alto = 0;
      var barra = document.querySelector('.menu-movil');
      if (barra) {
        var cs = window.getComputedStyle(barra);
        if (cs.display !== 'none' && cs.position === 'fixed') alto = barra.offsetHeight;
      }
      raiz.style.setProperty('--av-abajo', alto + 'px');
    }
    ajustarAbajo();
    window.addEventListener('resize', ajustarAbajo);
    window.addEventListener('orientationchange', ajustarAbajo);

    // Conversación guardada (para que no se pierda al cambiar de pantalla)
    ses.pend = null;
    (function restaurar() {
      (ses.msgs || []).forEach(function (m) { pintarMensaje(m.r, m.t, null); });
      if (ses.abierto) { panel.hidden = false; raiz.classList.add('av-abierto'); scrollMsgs(); saludoDado = true; }
    })();

    cargarDatos().then(function (d) {
      // Si antes de registrarse quería publicar algo, se lo recordamos al volver con sesión
      var luego = null;
      try { luego = JSON.parse(sessionStorage.getItem('av_luego') || 'null'); } catch (e) { /* nada */ }
      var post = leerPost();

      if (post) { setTimeout(function () { resumenPosterior(post); }, 350); }
      else if (luego && d.logueado && Date.now() - luego.t < 30 * 60 * 1000 && paginaActual() !== 'acceso') {
        try { sessionStorage.removeItem('av_luego'); } catch (e) { /* nada */ }
        abrirPanel(true);
        var opcion = luego.tipo === 'vacante' ? 'publicar una vacante' : 'ofrecer tus servicios';
        ses.pend = { tipo: 'confirmar', accion: 'luego_' + luego.tipo };
        decir('¡Bienvenido! Antes querías ' + opcion + '. ¿Te llevo ahora?', { chips: [{ t: 'Sí, llévame', cmd: 'sí' }, { t: 'Ahora no', cmd: 'no' }] });
      }
      if (!cfg.min) planearAvisos(!post && !luego && !ses.abierto);
      reanudarLlamada();
    });

    // Para quien quiera abrirlo desde otro botón: AsistenteVoz.abrir()
    window.AsistenteVoz = {
      abrir: function () { cfg.min = false; aplicarMinimizado(); abrirPanel(true); mostrarSaludoInicial(); },
      cerrar: cerrarPanel,
      ejecutar: function (t) { abrirPanel(true); porVoz = false; procesar(t, []); },
      ayuda: function () { alternarAyuda(true); }
    };
  }
})();
