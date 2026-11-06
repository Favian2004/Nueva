/* =====================================================================
   VALIDACIONES DE FORMULARIOS (Publicar vacante / Publicar empleo)

   Revisa lo que se escribe ANTES de mandarlo al servidor y le avisa a la
   persona, debajo de cada campo, qué está mal. Además evita que se puedan
   escribir caracteres que no van (letras en un teléfono, por ejemplo).

   Ojo: esto es solo una ayuda en pantalla. La revisión que de verdad
   cuenta es la del servidor (app/Http/Requests/Guardar*Request.php), que
   repite estas mismas reglas.

   Uso:
     Validaciones.filtroTexto(input)            // quita < > { } etc. mientras se escribe
     Validaciones.soloDigitos(input, 10)        // solo números, máximo 10
     Validaciones.decimal(input, {...})         // monto: 250 o 250.50
     Validaciones.entero(input)                 // solo enteros
     Validaciones.contador(input)               // "12/100" debajo del campo
     Validaciones.validar(form, reglas)         // true si todo está bien
   ===================================================================== */
(function (global) {
  'use strict';

  var Validaciones = {};

  // ---------- estilos (se inyectan solos) ----------
  function inyectarEstilos() {
    if (document.getElementById('vf-estilos')) return;
    var css = [
      '.vf-error{margin-top:5px;font-size:12.5px;line-height:1.35;color:#c62828;font-weight:600}',
      '.vf-invalid{border-color:#e53935 !important;box-shadow:0 0 0 3px rgba(229,57,53,.12) !important}',
      '.vf-contador{margin-top:3px;font-size:11.5px;line-height:1.2;text-align:right;color:#8a8d91}',
      '.vf-contador.vf-lleno{color:#c62828;font-weight:700}'
    ].join('\n');
    var st = document.createElement('style');
    st.id = 'vf-estilos';
    st.textContent = css;
    document.head.appendChild(st);
  }
  inyectarEstilos();

  // ---------- utilidades ----------
  function grupoDe(el) {
    return (el.closest && el.closest('.form-group')) || el.parentNode;
  }

  // Cambia el valor de un campo sin mandar el cursor al final.
  function poner(input, nuevo) {
    var antes = input.value;
    if (nuevo === antes) return;
    var pos = null;
    try { pos = input.selectionStart; } catch (e) { /* algunos tipos no lo permiten */ }
    input.value = nuevo;
    if (pos !== null) {
      var quitados = antes.length - nuevo.length;
      var p = Math.max(0, pos - Math.max(0, quitados));
      try { input.setSelectionRange(p, p); } catch (e) { /* nada */ }
    }
  }

  function limpiarError(el) {
    if (!el) return;
    el.classList.remove('vf-invalid');
    el.removeAttribute('aria-invalid');
    var g = grupoDe(el);
    if (g && g.querySelectorAll) {
      Array.prototype.forEach.call(g.querySelectorAll('.vf-error'), function (n) { n.remove(); });
    }
  }

  function mostrarError(el, msg) {
    limpiarError(el);
    // Si ya tenía un aviso anterior, se suelta su "vigilante" (que lo quitaría de golpe).
    if (el._vfQuitar) {
      el.removeEventListener('input', el._vfQuitar);
      el.removeEventListener('change', el._vfQuitar);
    }
    el.classList.add('vf-invalid');
    el.setAttribute('aria-invalid', 'true');
    var d = document.createElement('div');
    d.className = 'vf-error';
    d.setAttribute('role', 'alert');
    d.textContent = msg;
    grupoDe(el).appendChild(d);
    // En cuanto la persona toca el campo otra vez, se quita el aviso.
    var quitar = function () {
      limpiarError(el);
      el.removeEventListener('input', quitar);
      el.removeEventListener('change', quitar);
    };
    el._vfQuitar = quitar;
    el.addEventListener('input', quitar);
    el.addEventListener('change', quitar);
  }

  Validaciones.limpiarError = limpiarError;
  Validaciones.mostrarError = mostrarError;

  // ---------- filtros mientras se escribe ----------

  // Texto de una línea: solo letras, números y signos comunes.
  var NO_PERMITIDOS = /[^\p{L}\p{N}\s.,;:¡!¿?()\[\]\/&#+%$@'"°ºª*=_\-–—]/gu;
  Validaciones.filtroTexto = function (input) {
    input.addEventListener('input', function () {
      poner(input, input.value.replace(NO_PERMITIDOS, '').replace(/\s{2,}/g, ' ').replace(/^\s+/, ''));
    });
  };

  // Texto largo (descripción): todo menos < y >
  Validaciones.filtroTextoLargo = function (input) {
    input.addEventListener('input', function () {
      poner(input, input.value.replace(/[<>]/g, ''));
    });
  };

  // Solo números (teléfonos): quita lo que no sea dígito y corta a "max".
  Validaciones.soloDigitos = function (input, max) {
    var limpiar = function () {
      poner(input, input.value.replace(/\D+/g, '').slice(0, max || 10));
    };
    input.addEventListener('input', limpiar);
    input.addEventListener('blur', limpiar);
    input.addEventListener('paste', function () { setTimeout(limpiar, 0); });
    limpiar();
  };

  // Monto: 250 o 250.50 (hasta N enteros y 2 decimales).
  Validaciones.decimal = function (input, opciones) {
    var o = opciones || {};
    var maxEnt = o.maxEnteros || 7;
    var dec = o.decimales === undefined ? 2 : o.decimales;
    var limpiar = function () {
      var v = input.value.replace(/,/g, '.').replace(/[^\d.]/g, '');
      var i = v.indexOf('.');
      if (i !== -1) v = v.slice(0, i + 1) + v.slice(i + 1).replace(/\./g, '');
      var partes = v.split('.');
      partes[0] = partes[0].replace(/^0+(?=\d)/, '').slice(0, maxEnt);
      if (partes.length > 1) partes[1] = partes[1].slice(0, dec);
      poner(input, partes.join('.'));
    };
    input.addEventListener('input', limpiar);
    input.addEventListener('blur', limpiar);
    input.addEventListener('paste', function () { setTimeout(limpiar, 0); });
  };

  // Solo enteros (número de trabajadores): sin e, +, -, ni punto.
  Validaciones.entero = function (input, max) {
    input.addEventListener('keydown', function (e) {
      if (['e', 'E', '+', '-', '.', ','].indexOf(e.key) !== -1) e.preventDefault();
    });
    input.addEventListener('input', function () {
      var v = input.value.replace(/\D+/g, '');
      if (max && Number(v) > max) v = String(max);
      if (v !== input.value) input.value = v;
    });
  };

  // "12/100" debajo del campo (usa el maxlength del propio campo).
  Validaciones.contador = function (input) {
    var max = parseInt(input.getAttribute('maxlength'), 10);
    if (!max) return;
    var c = document.createElement('div');
    c.className = 'vf-contador';
    c.setAttribute('aria-hidden', 'true');
    // Va justo después del campo (antes de cualquier ayuda o aviso).
    input.parentNode.insertBefore(c, input.nextSibling);
    var pintar = function () {
      c.textContent = input.value.length + '/' + max;
      c.classList.toggle('vf-lleno', input.value.length >= max);
    };
    input.addEventListener('input', pintar);
    pintar();
  };

  // Imagen: solo JPG/PNG/WEBP y máximo 4 MB. Devuelve el mensaje o null.
  var TIPOS_IMAGEN = ['image/jpeg', 'image/png', 'image/webp'];
  function mensajeImagen(input) {
    var f = input.files && input.files[0];
    if (!f) return null;
    if (TIPOS_IMAGEN.indexOf(f.type) === -1) return 'La imagen debe ser JPG, PNG o WEBP.';
    if (f.size > 4 * 1024 * 1024) return 'La imagen no puede pesar más de 4 MB.';
    return null;
  }
  Validaciones.vigilarImagen = function (input) {
    input.addEventListener('change', function () {
      var m = mensajeImagen(input);
      if (m) {
        mostrarError(input, m);
        input.value = '';
      }
    });
  };

  // ---------- reglas ----------

  function fechaReal(v) {
    var m = /^(\d{4})-(\d{2})-(\d{2})$/.exec(v);
    if (!m) return false;
    var d = new Date(Number(m[1]), Number(m[2]) - 1, Number(m[3]));
    return d.getFullYear() === Number(m[1]) && d.getMonth() === Number(m[2]) - 1 && d.getDate() === Number(m[3]);
  }
  Validaciones.fechaReal = fechaReal;

  // Regla para un campo de fecha: a partir de "minimo" (mañana), hasta
  // "maximo". Si la fecha es la que ya estaba guardada ("original") no se
  // vuelve a exigir (así se puede editar una vacante vieja sin cambiarla).
  Validaciones.reglaFecha = function (cfg) {
    return function (v) {
      if (!fechaReal(v)) return 'La fecha de «' + cfg.nombre + '» no es válida.';
      if (cfg.original && v === cfg.original) return null;
      if (v < cfg.minimo) return '«' + cfg.nombre + '» tiene que ser a partir de mañana.';
      if (v > cfg.maximo) return '«' + cfg.nombre + '» no puede ser tan lejana (máximo 2 años).';
      return null;
    };
  };

  function revisar(el, r, form) {
    var v = (el.value || '').trim();
    var n = r.nombre || el.name;

    if (!v) return r.requerido ? (r.tipo === 'seleccion' ? 'Elige una opción en «' + n + '».' : 'Falta llenar «' + n + '».') : null;

    switch (r.tipo) {
      case 'texto':
      case 'largo':
        if (r.min && v.length < r.min) return '«' + n + '» necesita al menos ' + r.min + ' caracteres.';
        if (r.max && v.length > r.max) return '«' + n + '» no puede tener más de ' + r.max + ' caracteres.';
        if (r.tipo === 'texto' && /[^\p{L}\p{N}\s.,;:¡!¿?()\[\]\/&#+%$@'"°ºª*=_\-–—]/u.test(v)) {
          return '«' + n + '» tiene caracteres que no se permiten. Usa solo letras, números y signos comunes.';
        }
        if (r.tipo === 'largo' && /[<>]/.test(v)) return '«' + n + '» no puede llevar los signos < ni >.';
        if (!/\p{L}/u.test(v)) return '«' + n + '» debe llevar letras, no solo números o símbolos.';
        break;
      case 'telefono':
        if (!/^\d{10}$/.test(v)) return '«' + n + '» debe tener exactamente 10 dígitos (solo números, sin espacios ni lada).';
        break;
      case 'entero':
        if (!/^\d+$/.test(v)) return '«' + n + '» debe ser un número entero.';
        if (Number(v) < r.min || Number(v) > r.max) return '«' + n + '» debe estar entre ' + r.min + ' y ' + r.max + '.';
        break;
      case 'decimal':
        if (!/^\d+(\.\d{1,2})?$/.test(v)) return 'Escribe un monto válido en «' + n + '», por ejemplo 250 o 250.50.';
        if (Number(v) < r.min || Number(v) > r.max) return '«' + n + '» debe estar entre ' + r.min + ' y ' + r.max + '.';
        break;
      case 'imagen':
        return mensajeImagen(el);
      default:
        break;
    }

    return r.custom ? r.custom(v, el, form) : null;
  }

  // Revisa todas las reglas. Marca en rojo los campos con error, lleva a
  // la persona al primero y devuelve true solo si todo está bien.
  Validaciones.validar = function (form, reglas) {
    var primero = null;

    reglas.forEach(function (r) {
      var el = r.el || (form.elements && form.elements[r.campo]) || document.getElementById(r.campo);
      if (!el || el.disabled || el.type === 'hidden' && !r.mostrarEn) return;
      if (el.type === 'file' && r.tipo !== 'imagen') return;

      var msg = revisar(el, r, form);
      var destino = r.mostrarEn ? document.getElementById(r.mostrarEn) : el;
      if (msg) {
        mostrarError(destino, msg);
        if (!primero) primero = destino;
      } else {
        limpiarError(destino);
      }
    });

    if (primero) {
      try { primero.scrollIntoView({ behavior: 'smooth', block: 'center' }); } catch (e) { primero.scrollIntoView(); }
      try { primero.focus({ preventScroll: true }); } catch (e) { /* nada */ }
    }
    return !primero;
  };

  // Muestra debajo de cada campo los errores que mandó el servidor.
  // "alias" cambia a qué campo se pega un error (p. ej. duracion -> duracion_num).
  Validaciones.errorServidor = function (form, errores, alias) {
    if (!errores) return;
    var primero = null;
    Object.keys(errores).forEach(function (clave) {
      var nombre = (alias && alias[clave]) || clave.split('.')[0];
      var el = (form.elements && form.elements[nombre]) || document.getElementById(nombre);
      if (!el || el.type === 'hidden') return;
      var lista = Array.isArray(errores[clave]) ? errores[clave] : [errores[clave]];
      mostrarError(el, lista[0]);
      if (!primero) primero = el;
    });
    if (primero) {
      try { primero.scrollIntoView({ behavior: 'smooth', block: 'center' }); } catch (e) { primero.scrollIntoView(); }
    }
  };

  // ---------- Duración: número (1 a 31) + día(s) / semana(s) / mes(es) / año(s) ----------
  // Arma el texto "3 días" en el campo oculto. Si el número es 1 las
  // unidades salen en singular ("1 día") y si no, en plural ("2 días").
  var BASES = [['día', 'días'], ['semana', 'semanas'], ['mes', 'meses'], ['año', 'años']];

  Validaciones.iniciarDuracion = function (cfg) {
    var num = document.querySelector(cfg.num);
    var uni = document.querySelector(cfg.unidad);
    var oculto = document.querySelector(cfg.oculto);
    if (!num || !uni || !oculto) return;

    // Números del 1 al 31
    num.innerHTML = '';
    num.appendChild(new Option('Número', ''));
    for (var i = 1; i <= 31; i++) num.appendChild(new Option(String(i), String(i)));

    function pintarUnidades(n, baseElegida) {
      uni.innerHTML = '';
      uni.appendChild(new Option('Unidad', ''));
      BASES.forEach(function (b, idx) {
        uni.appendChild(new Option(n === 1 ? b[0] : b[1], String(idx)));
      });
      uni.value = (baseElegida === '' || baseElegida === null || baseElegida === undefined) ? '' : String(baseElegida);
    }

    function sincronizar(conservar) {
      var n = parseInt(num.value, 10);
      if (!n) {
        uni.disabled = true;
        pintarUnidades(2, '');
        oculto.value = '';
        return;
      }
      var base = conservar ? uni.value : '';
      // Al elegir el número por primera vez se propone "días".
      if (base === '') base = '0';
      uni.disabled = false;
      pintarUnidades(n, base);
      oculto.value = n + ' ' + BASES[Number(uni.value)][n === 1 ? 0 : 1];
    }

    num.addEventListener('change', function () { sincronizar(true); });
    uni.addEventListener('change', function () {
      var n = parseInt(num.value, 10);
      oculto.value = (n && uni.value !== '') ? n + ' ' + BASES[Number(uni.value)][n === 1 ? 0 : 1] : '';
    });

    // Valor que ya tenía la vacante (al editar): "3 días" -> 3 + días
    var m = /^(\d{1,2}) (\S+)$/.exec((cfg.valorInicial || '').trim());
    if (m && Number(m[1]) >= 1 && Number(m[1]) <= 31) {
      var base = -1;
      BASES.forEach(function (b, idx) { if (b.indexOf(m[2]) !== -1) base = idx; });
      if (base !== -1) {
        num.value = m[1];
        uni.disabled = false;
        pintarUnidades(Number(m[1]), base);
        oculto.value = Number(m[1]) + ' ' + BASES[base][Number(m[1]) === 1 ? 0 : 1];
        return;
      }
    }
    uni.disabled = true;
    pintarUnidades(2, '');
    oculto.value = '';
  };

  // ---------- Fechas: "Día del trabajo" y "Límite para postular" ----------
  // Las dos se eligen en el calendario, desde mañana en adelante. El límite
  // para postular no puede quedar después del día del trabajo.
  Validaciones.iniciarFechas = function (cfg) {
    var trabajo = document.querySelector(cfg.trabajo);
    var limite = document.querySelector(cfg.limite);
    var aviso = cfg.aviso ? document.querySelector(cfg.aviso) : null;
    if (!trabajo || !limite) return;

    function ajustar() {
      trabajo.min = limite.value && limite.value > cfg.manana ? limite.value : cfg.manana;
      trabajo.max = cfg.tope;
      limite.min = cfg.manana;
      limite.max = trabajo.value && fechaReal(trabajo.value) && trabajo.value < cfg.tope ? trabajo.value : cfg.tope;

      // Si el día del trabajo ya pasó, el calendario del límite no tiene días
      // válidos: se explica qué hacer en vez de dejarlo en gris sin decir nada.
      if (aviso) {
        var pasado = trabajo.value && fechaReal(trabajo.value) && trabajo.value < cfg.manana;
        aviso.textContent = pasado
          ? 'El día del trabajo ya pasó. Cámbialo primero a una fecha desde mañana y después podrás elegir el límite para postular.'
          : '';
        aviso.style.display = pasado ? 'block' : 'none';
      }
    }

    trabajo.addEventListener('change', function () {
      ajustar();
      if (limite.value && trabajo.value && limite.value > trabajo.value) {
        limite.value = '';
        mostrarError(limite, 'Quité el límite para postular porque quedaba después del día del trabajo. Elige otra fecha.');
      }
    });
    limite.addEventListener('change', function () {
      ajustar();
      if (!limite.value || !fechaReal(limite.value)) return;
      if (limite.value < cfg.manana) {
        mostrarError(limite, 'El límite para postular tiene que ser a partir de mañana.');
      } else if (trabajo.value && fechaReal(trabajo.value) && limite.value > trabajo.value) {
        mostrarError(limite, 'El límite para postular no puede ser después del día del trabajo.');
      } else if (limite.value > cfg.tope) {
        mostrarError(limite, 'El límite para postular no puede ser tan lejano (máximo 2 años).');
      }
    });
    ajustar();
  };

  global.Validaciones = Validaciones;
})(window);
