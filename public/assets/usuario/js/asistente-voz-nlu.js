"use strict";

/*
 * ASISTENTE DE VOZ — "cerebro" (entiende lo que la persona dice o escribe).
 *
 * Este archivo NO toca la página: solo recibe un texto y devuelve qué quiere
 * la persona (buscar, ir a una pantalla, publicar, pedir ayuda...) con sus
 * datos (oficio, localidad, precio, orden...). Por eso se puede probar solo.
 * Quien lo usa es asistente-voz.js.
 *
 * Qué tolera:
 *  - Muchas formas de decir lo mismo ("busco", "necesito", "ocupo", "me urge"...).
 *  - Errores típicos del reconocimiento de voz (b/v, s/z/c, "h", acentos).
 *  - Números hablados ("quinientos", "dos mil", "mil doscientos") o en cifras.
 *  - Localidades mal escritas (compara contra las de la base de datos).
 *  - Problemas en vez de oficios ("se me tapó el drenaje" => plomero).
 */
(function (root, factory) {
  if (typeof module === 'object' && module.exports) module.exports = factory();
  else root.AsistenteNLU = factory();
})(typeof self !== 'undefined' ? self : this, function () {

  // ====================================================================
  //  1. UTILIDADES DE TEXTO
  // ====================================================================

  function sinAcentos(t) {
    return String(t == null ? '' : t).toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '');
  }

  // Distancia de edición (cuántas letras cambian entre dos palabras).
  function lev(a, b, tope) {
    if (a === b) return 0;
    if (Math.abs(a.length - b.length) > (tope == null ? 99 : tope)) return 99;
    var prev = [], cur = [], i, j;
    for (j = 0; j <= b.length; j++) prev[j] = j;
    for (i = 1; i <= a.length; i++) {
      cur = [i];
      for (j = 1; j <= b.length; j++) {
        cur[j] = Math.min(prev[j] + 1, cur[j - 1] + 1, prev[j - 1] + (a[i - 1] === b[j - 1] ? 0 : 1));
      }
      prev = cur;
    }
    return prev[b.length];
  }

  // Clave "de oído": palabras que suenan igual se escriben igual.
  function fon(w) {
    return sinAcentos(w)
      .replace(/h/g, '').replace(/ll/g, 'y').replace(/v/g, 'b').replace(/w/g, 'u')
      .replace(/qu/g, 'k').replace(/c(?=[eiy])/g, 's').replace(/z/g, 's').replace(/c/g, 'k')
      .replace(/gu(?=[ei])/g, 'g').replace(/y$/, 'i');
  }

  // Plural => singular (computadoras => computadora, albañiles => albañil).
  function sing(w) {
    if (w.length <= 3) return w;
    if (/(ones)$/.test(w)) return w.slice(0, -2);            // reparaciones => reparacion
    if (/[nrldzj]es$/.test(w) && w.length > 5) return w.slice(0, -2); // albañiles => albañil
    if (/s$/.test(w) && !/(is|us|as|es|os)$/.test(w.slice(-3)) ) return w.slice(0, -1);
    if (/[aeiou]s$/.test(w)) return w.slice(0, -1);
    return w;
  }

  // Raíz de un oficio: plomero, plomería => "plom"; electricista => "electric".
  function raiz(w) {
    w = sinAcentos(sing(w));
    var sufijos = ['eria', 'ero', 'era', 'ista', 'ador', 'adora', 'ico', 'ica', 'ante', 'cion', 'ia', 'or', 'ora', 'o', 'a'];
    for (var i = 0; i < sufijos.length; i++) {
      var s = sufijos[i];
      if (w.length - s.length >= 4 && w.slice(-s.length) === s) return w.slice(0, -s.length);
    }
    return w;
  }

  function R(patron, banderas) { return new RegExp('\\b(?:' + patron + ')\\b', banderas || ''); }

  function unicos(arr) {
    var vistos = {}, out = [];
    arr.forEach(function (x) { if (x && !vistos[x]) { vistos[x] = 1; out.push(x); } });
    return out;
  }

  // ====================================================================
  //  2. NÚMEROS HABLADOS => CIFRAS   ("dos mil quinientos" => "2500")
  // ====================================================================

  var UNI = {
    cero: 0, uno: 1, un: 1, una: 1, dos: 2, tres: 3, cuatro: 4, cinco: 5, seis: 6, siete: 7, ocho: 8, nueve: 9,
    diez: 10, once: 11, doce: 12, trece: 13, catorce: 14, quince: 15, dieciseis: 16, diecisiete: 17, dieciocho: 18,
    diecinueve: 19, veinte: 20, veintiuno: 21, veintiun: 21, veintiuna: 21, veintidos: 22, veintitres: 23,
    veinticuatro: 24, veinticinco: 25, veintiseis: 26, veintisiete: 27, veintiocho: 28, veintinueve: 29
  };
  var DEC = { treinta: 30, cuarenta: 40, cincuenta: 50, sesenta: 60, setenta: 70, ochenta: 80, noventa: 90 };
  var CEN = {
    cien: 100, ciento: 100, doscientos: 200, doscientas: 200, trescientos: 300, trescientas: 300,
    cuatrocientos: 400, cuatrocientas: 400, quinientos: 500, quinientas: 500, seiscientos: 600, seiscientas: 600,
    setecientos: 700, setecientas: 700, ochocientos: 800, ochocientas: 800, novecientos: 900, novecientas: 900
  };

  function tipoNumero(tok, sig) {
    if (/^\d+$/.test(tok)) return 'dig';
    if (tok === 'mil') return 'mil';
    if (tok === 'millon' || tok === 'millones') return 'mill';
    if (tok in CEN) return 'cen';
    if (tok in DEC) return 'dec';
    if (tok in UNI) {
      // "un", "una", "uno" suelen ser artículos: solo cuentan antes de "mil/millón" o tras una decena.
      if (tok === 'un' || tok === 'una' || tok === 'uno') return (sig === 'mil' || sig === 'millon' || sig === 'millones') ? 'uni' : null;
      if (tok === 'cero') return null;
      return 'uni';
    }
    return null;
  }

  function palabrasANumeros(s) {
    var t = s.split(' ').filter(Boolean), out = [], i = 0;
    while (i < t.length) {
      var tipo0 = tipoNumero(t[i], t[i + 1]);
      if (!tipo0) { out.push(t[i]); i++; continue; }
      var total = 0, actual = 0, ultimo = null, j = i, algo = false;
      while (j < t.length) {
        var x = t[j], tp = tipoNumero(x, t[j + 1]);
        if (x === 'y' && ultimo === 'dec' && tipoNumero(t[j + 1], t[j + 2]) === 'uni' && UNI[t[j + 1]] < 10) { j++; continue; }
        if (!tp) break;
        if (tp === 'dig') {
          if (ultimo === null || ultimo === 'mil' || ultimo === 'mill') { actual += parseInt(x, 10); ultimo = 'dig'; j++; algo = true; continue; }
          break;
        }
        if (tp === 'cen') { if (ultimo === null || ultimo === 'mil' || ultimo === 'mill') { actual += CEN[x]; ultimo = 'cen'; j++; algo = true; continue; } break; }
        if (tp === 'dec') { if (ultimo === null || ultimo === 'mil' || ultimo === 'mill' || ultimo === 'cen') { actual += DEC[x]; ultimo = 'dec'; j++; algo = true; continue; } break; }
        if (tp === 'uni') {
          if (ultimo === null || ultimo === 'mil' || ultimo === 'mill' || ultimo === 'cen' || (ultimo === 'dec' && t[j - 1] === 'y')) {
            actual += UNI[x]; ultimo = 'uni'; j++; algo = true; continue;
          }
          break;
        }
        if (tp === 'mil') { total += (actual || 1) * 1000; actual = 0; ultimo = 'mil'; j++; algo = true; continue; }
        if (tp === 'mill') { total += (actual || 1) * 1000000; actual = 0; ultimo = 'mill'; j++; algo = true; continue; }
        break;
      }
      if (!algo || j === i) { out.push(t[i]); i++; continue; }
      out.push(String(total + actual));
      i = j;
    }
    return out.join(' ');
  }

  // Texto de la persona => minúsculas, sin acentos, sin signos, números en cifras.
  function preparar(texto) {
    var s = sinAcentos(texto);
    s = s.replace(/(\d)[,.](?=\d{3}(?!\d))/g, '$1');           // 1,500 => 1500
    s = s.replace(/\$\s*(\d+)/g, ' $1 pesos ');                 // $500 => 500 pesos
    s = s.replace(/[^a-z0-9\s]/g, ' ').replace(/\s+/g, ' ').trim();
    s = palabrasANumeros(s);
    s = s.replace(/\b(\d+) (?:pesos|peso)(?: mexicanos)?(?: (?:pesos|peso))*\b/g, '$1 pesos');
    return s.replace(/\s+/g, ' ').trim();
  }

  // ====================================================================
  //  3. LISTAS DE PALABRAS
  // ====================================================================

  // Palabras que no sirven para saber QUÉ se busca.
  var STOP = {};
  ('a al algo alguien algun alguna alguno algunas algunos ante aqui ahi alla asi aun bien buen buena bueno buenos buenas ' +
   'cada casi como con contra cual cuales cuando cuanto cuantos cuantas de del desde donde e el ella ellos en entonces eres es ' +
   'esa ese eso esos esas esta este esto estos estas fue ha han hay he la las le les lo los me mi mis mismo muy ni no nos nuestro ' +
   'o otra otro otros otras pero poco por porque pues que quien quienes se sea sean ser si sin sobre solo su sus te tu tus un una ' +
   'uno unos unas y ya yo oye mira ey eh ok okey vale porfa favor gracias hola buenas tardes dias noches quisiera quiero queria ' +
   'quieres necesito necesitar ocupo ocupar requiero busco buscar buscas buscando buscame busca encuentra encuentrame encontrar ' +
   'consigue conseguir consigo dame damelo muestra muestrame ensename ver veo vamos vamos mostrar muestre enseñame hay habra tienen tiene ' +
   'existe existen conoces recomiendas recomiendame sugiere lista listame filtra filtrame filtrar ando andamos estoy estamos ' +
   'estar tengo tenemos tener tambien ademas aparte mejor ahora luego despues primero segundo tercero ultimo ultima para ' +
   'puedes puede podrias podria pueden pudieras favor urge urgente rapido rapidito pronto hoy mañana manana ' +
   'quiza quizas tal vez igual mas menos mucho muchos mucha muchas todo todos toda todas cualquier cualquiera demas otro ' +
   'va vas voy van ir ve vaya vete dime dile dices digo decir quede queden quedar le lo la me nos asi asi ' +
   'cosa cosas algo nada cierto cierta tipo tipos manera forma casa medio tiempo completo jornada turno horario ' +
   'ustedes usted tu ti mio mia mios mias nuestro nuestra nuestros nuestras suyo suya ' +
   'aqui cerca lejos cercano cercana cercanos cercanas ' +
   'ordena ordenalo ordenalos ordenala ordenalas ordenar ordename ordenado ordenados ordenada ordenadas acomoda acomodalo acomodalos organiza organizalos clasifica clasificalos ponlo ponlos pon colocalos ' +
   'sintec asistente sintecz sintezate sintesate sinteczate sintecsate ' +
   'pesos peso precio precios costo costos cuesta cuestan cueste cuesten cobra cobran cobre cobren tarifa tarifas ' +
   'sueldo sueldos salario salarios paga pagan pague paguen pagar pago pagos ganar gane ganen gano ganando ' +
   'barato baratos barata baratas economico economicos economica economicas caro caros cara caras costoso costosos ' +
   'reciente recientes nuevo nuevos nueva nuevas ultimos ultimas ' +
   'maximo minimo menos mas hasta entre desde arriba abajo mayor menor ' +
   'dia dias semana semanal semanales mes mensual quincena quincenal diario diarios ' +
   'localidad localidades comunidad comunidades colonia colonias barrio barrios pueblo pueblos zona zonas ' +
   'municipio lugar lugares ubicacion ' +
   'categoria categorias filtro filtros orden ordenado ordenados resultados resultado opciones opcion ' +
   'servicio servicios profesional profesionales especialista especialistas experto expertos persona personas gente personal ' +
   'trabajador trabajadores trabajadora trabajadoras empleado empleados empleada empleadas ' +
   'empleo empleos trabajo trabajos trabajito chamba chambas chambita vacante vacantes puesto puestos oferta ofertas ' +
   'publicar publica publicame vacante anuncio anuncios pantalla pagina seccion apartado ' +
   'pueda pudiera sea fuera sean tenga tengan haya hagan haga hacer hace hacen hecho ' +
   'cual cuales algun ningun ninguno cuantos').split(/\s+/).forEach(function (p) { if (p) STOP[p] = 1; });

  // Verbos que se dicen al pedir un servicio ("que arregle", "que cuide"); no son el oficio.
  var VERBOS = {};
  ('arregle arreglen arreglar repare reparen reparar haga hagan cuide cuiden cuidar limpie limpien limpiar pinte pinten pintar ' +
   'construya construir instale instalen instalar cocine cocinar maneje manejar trabaje trabajar ayude ayuden ayudar venda ' +
   'vender compre comprar lleve llevar traiga traer cargue cargar corte cortar poda podar lave lavar planche planchar ' +
   'enseñe ensene ensenar dibuje diseñe disene disenar revise revisar cambie cambiar ponga poner quite quitar mueva mover ' +
   'resuelva resolver atienda atender entregue entregar cobre cobrar sepa saber conozca conocer sirva servir pueda poder ' +
   'tenga tener quiera querer vaya ir venga venir este estar sea ser ocupe ocupar necesite necesitar busque buscar').split(/\s+/)
    .forEach(function (p) { if (p) VERBOS[p] = 1; });

  // Oficios y cosas que la gente busca por aquí. (Con acentos para hablarlos/mostrarlos bien.)
  var OFICIOS = [
    'plomero', 'plomería', 'electricista', 'electricidad', 'carpintero', 'carpintería', 'albañil', 'albañilería', 'pintor',
    'pintura', 'herrero', 'herrería', 'soldador', 'soldadura', 'mecánico', 'mecánica', 'cerrajero', 'cerrajería', 'jardinero',
    'jardinería', 'costurera', 'costura', 'sastre', 'zapatero', 'panadero', 'repostería', 'pastelero', 'tortillera', 'cocinero',
    'cocinera', 'cocina', 'mesero', 'mesera', 'cantinero', 'barista', 'lavandera', 'planchadora', 'niñera', 'cuidador',
    'cuidadora', 'enfermera', 'enfermero', 'doctor', 'médico', 'dentista', 'abogado', 'contador', 'contabilidad', 'maestro',
    'maestra', 'profesor', 'profesora', 'tutor', 'clases', 'asesoría', 'fotógrafo', 'fotografía', 'músico', 'mariachi', 'payaso',
    'animador', 'decorador', 'decoración', 'florista', 'chofer', 'taxista', 'mudanza', 'mudanzas', 'flete', 'fletes',
    'repartidor', 'mensajero', 'vendedor', 'vendedora', 'cajero', 'cajera', 'velador', 'guardia', 'vigilante', 'intendente',
    'limpieza', 'afanadora', 'aseo', 'cargador', 'peón', 'campesino', 'jornalero', 'ayudante', 'ganadero', 'veterinario',
    'vidriero', 'tapicero', 'refrigeración', 'diseñador', 'diseño', 'programador', 'programación', 'yesero', 'impermeabilizante',
    'impermeabilización', 'instalador', 'topógrafo', 'arquitecto', 'ingeniero', 'secretaria', 'recepcionista', 'auxiliar',
    'llantero', 'vulcanizadora', 'hojalatero', 'hojalatería', 'tablaroquero', 'pintor', 'estilista', 'peluquero', 'peluquera',
    'barbero', 'manicurista', 'masajista', 'albañiles', 'técnico', 'electrodomésticos', 'lavadora', 'refrigerador', 'computadora',
    'computadoras', 'laptop', 'celular', 'celulares', 'internet', 'cámaras', 'ropa', 'comida', 'banquetes', 'banquete', 'pasteles',
    'pastel', 'tamales', 'antojitos', 'eventos', 'fiestas', 'sonido', 'música', 'bordado', 'artesanías', 'cestería', 'alfarería',
    'carnicero', 'carnicería', 'verdulería', 'abarrotes', 'tienda', 'construcción', 'remodelación', 'techos', 'azulejo',
    'azulejero', 'piso', 'pisos', 'cancelería', 'herraje', 'puertas', 'ventanas', 'muebles', 'tapicería', 'colchones',
    'motos', 'moto', 'motocicleta', 'bicicleta', 'carro', 'autos', 'auto', 'camioneta', 'camión', 'tractor', 'agricultura',
    'siembra', 'cosecha', 'milpa', 'café', 'ganado', 'borregos', 'caballos', 'veterinaria', 'tecnología', 'sistemas'
  ];

  // Frases de más de una palabra que cuentan como un solo oficio.
  var MULTI = [
    'aire acondicionado', 'mano de obra', 'lavado de autos', 'lavado de carros', 'cuidado de niños', 'cuidado de adultos',
    'adultos mayores', 'servicio doméstico', 'trabajo doméstico', 'empleada doméstica', 'técnico en computación',
    'reparación de celulares', 'reparación de computadoras', 'diseño gráfico', 'diseño web', 'redes sociales',
    'fumigación de plagas', 'control de plagas', 'cámaras de seguridad', 'paneles solares', 'instalación eléctrica',
    'tinaco', 'cisterna', 'fosa séptica', 'pozo', 'pozo de agua'
  ];

  // Problemas cotidianos => a quién hay que buscar (para quien no sabe qué oficio pedir).
  var PROBLEMAS = [
    [/\b(fuga|gotea|goteo|llave del agua|llave de agua|tuberia|tuberias|drenaje|tapado|tapada|destapar|inodoro|escusado|boiler|calentador de agua|tinaco|cisterna|sin agua)\b/, 'plomero'],
    [/\b(sin luz|se fue la luz|apagon|corto circuito|cortocircuito|contacto|apagador|cableado|instalacion electrica|se botan los fusibles|medidor de luz)\b/, 'electricista'],
    [/\b(puerta|closet|ropero|mueble|muebles|cocina integral|escritorio|repisa|ventana de madera)\b/, 'carpintero'],
    [/\b(barda|muro|pared|loza|cimiento|colar|castillo|construir|construccion|ampliar mi casa|cuarto nuevo|enjarre|aplanado|banqueta)\b/, 'albañil'],
    [/\b(gotera|goteras|se llueve|impermeabilizar|impermeabilizante)\b/, 'impermeabilizante'],
    [/\b(pintar|pintura|repintar|dar una mano de pintura)\b/, 'pintor'],
    [/\b(cerradura|candado|se me quedaron las llaves|perdi las llaves|copia de llaves)\b/, 'cerrajero'],
    [/\b(jardin|pasto|podar|poda|arboles|cortar el pasto|deshierbar)\b/, 'jardinero'],
    [/\b(descompuso mi lavadora|se descompuso la lavadora|lavadora)\b/, 'lavadora'],
    [/\b(refri|refrigerador|congelador|nevera)\b/, 'refrigerador'],
    [/\b(se descompuso el carro|mi carro|mi coche|frenos|afinar|afinacion|motor|mecanico)\b/, 'mecánico'],
    [/\b(llanta ponchada|ponchadura|llantera)\b/, 'llantero'],
    [/\b(mi cel|mi celular|pantalla estrellada|cambio de pantalla|cambio de bateria)\b/, 'celular'],
    [/\b(mi compu|mi computadora|mi laptop|formatear|virus en|wifi|internet|red de internet)\b/, 'computadora'],
    [/\b(mudarme|mudanza|acarreo|flete|fletes|cambio de casa)\b/, 'mudanza'],
    [/\b(fiesta|boda|xv anos|quinceanos|bautizo|cumpleanos|evento|banquete)\b/, 'eventos'],
    [/\b(tamales|pastel|pasteles|comida para|botanas)\b/, 'comida'],
    [/\b(cuidar a mi|cuidar a un|quien cuide|cuidar ninos|cuidar a mis hijos|guarderia|ninera)\b/, 'niñera'],
    [/\b(limpiar mi casa|limpiar la casa|aseo de|quien limpie|limpieza de)\b/, 'limpieza'],
    [/\b(clases particulares|regularizacion|tareas|tutoria|clases de)\b/, 'clases']
  ];

  // Cuando pide la acción ("que me pinten la casa") y no dice el oficio.
  var VERBO_OFICIO = [
    [/\b(?:limpie|limpien|limpiar|aseen|asee)\b/, 'limpieza'],
    [/\b(?:pinte|pinten|pintar|pintaran)\b/, 'pintor'],
    [/\b(?:construya|construyan|construir|edifique)\b/, 'albañil'],
    [/\b(?:cocine|cocinen|cocinar)\b/, 'cocinero'],
    [/\b(?:maneje|manejen|manejar|transporte|transportar)\b/, 'chofer'],
    [/\b(?:planche|planchen|planchar)\b/, 'planchadora'],
    [/\b(?:lave|laven|lavar)\b/, 'lavandera'],
    [/\b(?:pode|poden|podar)\b/, 'jardinero'],
    [/\b(?:cuide|cuiden|cuidar)\b/, 'cuidador'],
    [/\b(?:ensene|enseñen|ensenen|ensenar|dar clases)\b/, 'maestro'],
    [/\b(?:fotografie|fotografiar|tome fotos|tomar fotos)\b/, 'fotógrafo'],
    [/\b(?:cargue|carguen|cargar|acarree)\b/, 'cargador'],
    [/\b(?:vigile|vigilen|vigilar)\b/, 'velador'],
    [/\b(?:arregle|arreglen|arreglar|repare|reparen|reparar)\b/, 'técnico']
  ];

  // Cómo se escriben bien (con acento o ñ) palabras que se reconocen sin ellas.
  var ACENTOS = {};
  OFICIOS.concat(MULTI).forEach(function (p) { ACENTOS[sinAcentos(p)] = p; });

  // ====================================================================
  //  4. ÍNDICES (se calculan una vez por lista de localidades/categorías)
  // ====================================================================

  var COMUNES = ('san santa santo santiago barrio colonia col centro norte sur oriente poniente del las los una con calle avenida ' +
    'puebla municipal cabecera localidad comunidad ejido rancho nuevo nueva grande chico chica viejo vieja lomas cerro miguel pedro ' +
    'andres francisco antonio lucas mateo marcos pablo felipe nicolas bartolo cristobal sebastian agustin gabriel rafael martin diego ' +
    'esteban lorenzo jeronimo maria magdalena guadalupe concepcion isabel trinidad asuncion natividad candelaria dolores carmen').split(' ');

  var _cache = new WeakMap();

  function indice(ctx) {
    var llave = ctx.localidades || ctx;
    if (typeof llave === 'object' && _cache.has(llave)) {
      var c = _cache.get(llave);
      if (c.cat === ctx.categorias && c.sub === ctx.subcategorias) return c;
    }
    var municipio = sinAcentos(ctx.municipio || 'Zacapoaxtla').trim();
    var idx = { cat: ctx.categorias, sub: ctx.subcategorias, locs: [], palabras: {}, genericas: [], cats: [], subs: [], diccionario: {}, raices: {} };
    var veces = {};

    (ctx.localidades || []).forEach(function (l) {
      var clave = sinAcentos(String(l.nombre || '').replace(/\(.*?\)/g, ' ')).replace(/[^a-z0-9]+/g, ' ').trim();
      if (!clave) return;
      var e = { id: Number(l.id), nombre: l.nombre, clave: clave, sinEspacios: clave.replace(/ /g, '') };
      if (clave === municipio || /cabecera/i.test(l.nombre)) { idx.genericas.push(e); return; }
      idx.locs.push(e);
      unicos(clave.split(' ')).forEach(function (p) {
        if (p.length >= 5 && COMUNES.indexOf(p) < 0 && p !== municipio) (veces[p] = veces[p] || []).push(e);
      });
    });
    idx.locs.sort(function (a, b) { return b.clave.length - a.clave.length; });
    Object.keys(veces).forEach(function (p) { if (veces[p].length === 1) idx.palabras[p] = veces[p][0]; });
    idx.municipio = municipio;

    (ctx.categorias || []).forEach(function (c) {
      var clave = sinAcentos(c.nombre).replace(/[^a-z0-9]+/g, ' ').trim();
      idx.cats.push({ id: Number(c.id), nombre: c.nombre, clave: clave, palabras: unicos(clave.split(' ').filter(function (p) { return p.length >= 4 && !STOP[p]; })) });
    });
    (ctx.subcategorias || []).forEach(function (s) {
      var clave = sinAcentos(s.nombre).replace(/[^a-z0-9]+/g, ' ').trim();
      idx.subs.push({ id: Number(s.id), categoria_id: Number(s.categoria_id), nombre: s.nombre, clave: clave });
    });

    // Diccionario de oficios: lo fijo + lo que haya en la base de datos.
    function alta(texto) {
      var clave = sinAcentos(texto).replace(/[^a-z0-9ñ ]+/g, ' ').trim();
      if (!clave) return;
      clave.split(' ').forEach(function (p) {
        if (p.length < 4 || STOP[p]) return;
        if (!idx.diccionario[p]) idx.diccionario[p] = ACENTOS[p] || (texto.split(/\s+/).filter(function (x) { return sinAcentos(x) === p; })[0] || p);
        var r = raiz(p);
        if (r.length >= 4) (idx.raices[r] = idx.raices[r] || []).push(idx.diccionario[p]);
      });
    }
    OFICIOS.forEach(alta);
    (ctx.subcategorias || []).forEach(function (s) { alta(s.nombre); });
    (ctx.categorias || []).forEach(function (c) { alta(c.nombre); });

    if (typeof llave === 'object') _cache.set(llave, idx);
    return idx;
  }

  // ====================================================================
  //  5. EXTRACTORES (sacan un dato del texto y lo quitan del texto)
  // ====================================================================

  var SIN_DINERO = '(?:anos|ano|meses|horas|hora|minutos|personas|persona|trabajadores|empleados|kilometros|km|metros|veces|dias|semanas|vacantes|servicios)';

  function esDinero(n, despues) {
    if (n < 10) return false;
    return !new RegExp('^' + SIN_DINERO + '\\b').test(despues || '');
  }

  // Precio o sueldo. Devuelve { w, min, max } (w = texto sin esa parte).
  function extraerDinero(w, esVacante) {
    var r = { min: null, max: null }, m;
    function quitar(re) { w = w.replace(re, ' '); }

    // Rango: "entre 300 y 500", "de 300 a 500 pesos", "300 - 500"
    m = /\b(?:entre|de|desde|del)\s+(\d+)(?: pesos)?\s+(?:y|a|al|hasta)\s+(\d+)(?: pesos)?(?!\s*(?:anos|meses|horas|personas))/.exec(w);
    if (m && +m[1] >= 10 && +m[2] >= 10) {
      var a = +m[1], b = +m[2];
      r.min = Math.min(a, b); r.max = Math.max(a, b);
      quitar(new RegExp(m[0].replace(/[.*+?^${}()|[\]\\]/g, '\\\\$&')));
      w = w.replace(m[0], ' ');
      return { w: w, min: r.min, max: r.max };
    }

    // Tope: "menos de 500", "hasta 500", "máximo 500", "500 o menos", "presupuesto de 500"
    var topes = [
      /\b(?:menos de|menor a|menor que|hasta|maximo|max|no mas de|nomas de|como mucho|por menos de|abajo de|debajo de|que no pase de|que no pasen de|no pase de|tope de|presupuesto de|con presupuesto de|con un presupuesto de|dispongo de|tengo|cuente con) (\d+)(?: pesos)?/,
      /\b(\d+)(?: pesos)? (?:o menos|maximo|como maximo|para abajo|de tope)/
    ];
    for (var i = 0; i < topes.length; i++) {
      m = topes[i].exec(w);
      if (m && esDinero(+m[1], w.slice(m.index + m[0].length).trim())) { r.max = +m[1]; w = w.replace(m[0], ' '); break; }
    }

    // Mínimo: "más de 200", "mínimo 200", "desde 200", "arriba de 200", "200 o más"
    var minimos = [
      /\b(?:mas de|mayor a|mayor que|minimo|min|desde|arriba de|por encima de|a partir de|al menos|por lo menos|como minimo|no menos de|superior a|encima de) (\d+)(?: pesos)?/,
      /\b(\d+)(?: pesos)? (?:o mas|para arriba|minimo|como minimo|en adelante|o mas)/
    ];
    for (i = 0; i < minimos.length; i++) {
      m = minimos[i].exec(w);
      if (m && esDinero(+m[1], w.slice(m.index + m[0].length).trim())) { r.min = +m[1]; w = w.replace(m[0], ' '); break; }
    }

    // Una cantidad suelta con contexto de dinero.
    if (r.min == null && r.max == null) {
      var sueldo = /\b(?:pague|paguen|paga|pagan|pagar|pagando|sueldo|salario|gane|ganen|ganar|ganando|gano|ganas|me den|me paguen) (?:de |como |unos |al menos |minimo )?(\d+)/.exec(w);
      if (sueldo && esDinero(+sueldo[1], w.slice(sueldo.index + sueldo[0].length).trim())) { r.min = +sueldo[1]; w = w.replace(sueldo[0], ' '); }
      else {
        var suelta = /\b(?:de |por |a |en |con |cuesta |cueste |cuesten |cobre |cobren |cobra |precio de |hasta |como de |alrededor de |aproximadamente |unos |tarifa de )?(\d+) pesos\b/.exec(w);
        if (suelta && esDinero(+suelta[1], w.slice(suelta.index + suelta[0].length).trim())) {
          if (esVacante) r.min = +suelta[1]; else r.max = +suelta[1];
          w = w.replace(suelta[0], ' ');
        } else {
          var cuesta = /\b(?:cuesta|cueste|cuesten|cobre|cobren|cobra|precio de|presupuesto de|tarifa de) (\d+)\b/.exec(w);
          if (cuesta && esDinero(+cuesta[1], w.slice(cuesta.index + cuesta[0].length).trim())) {
            if (esVacante) r.min = +cuesta[1]; else r.max = +cuesta[1];
            w = w.replace(cuesta[0], ' ');
          }
        }
      }
    }
    if (r.min != null && r.max != null && r.min > r.max) { var t = r.min; r.min = r.max; r.max = t; }
    return { w: w, min: r.min, max: r.max };
  }

  // Orden: más recientes, cercanía, más barato/mejor pagado.
  function extraerOrden(w) {
    var orden = null, cerca = false;
    var tests = [
      ['menor_precio', /\b(?:por precio|por costo|por tarifa|mas barat[oa]s?|mas economic[oa]s?|barat[oa]s?|economic[oa]s?|menor precio|precio mas bajo|precios? bajos?|de menor a mayor|ascendente|que cobren menos|que cobre menos|los mas baratos|el mas barato|la mas barata|menor sueldo|menos sueldo|accesibles?)\b/],
      ['mayor_precio', /\b(?:por sueldo|por salario|mas car[oa]s?|mayor precio|precio mas alto|precios? altos?|de mayor a menor|descendente|mejor pagad[oa]s?|mejor sueldo|mejores sueldos|mas sueldo|sueldo mas alto|salario mas alto|que mas paguen|que mejor paguen|bien pagad[oa]s?|los que mas pagan|los que mejor pagan|mejor pagados|premium|de lujo)\b/],
      ['recientes', /\b(?:por fecha|por novedad|mas recientes?|recientes?|mas nuev[oa]s?|nuev[oa]s|ultim[oa]s?|recien publicad[oa]s?|publicad[oa]s? hoy|publicad[oa]s? recientemente|ordenad[oa]s? por fecha|los de hoy)\b/]
    ];
    tests.forEach(function (t) {
      if (!orden && t[1].test(w)) { orden = t[0]; w = w.replace(t[1], ' '); }
    });
    var cercaRe = /\b(?:por cercania|por distancia|cercania|distancia|cerca de mi|cerca de aqui|cerca de donde vivo|cerca de mi casa|cerca|cercan[oa]s?|cerquita|cerquitas|por aqui|por mi zona|por mi casa|a la redonda|alrededor|mas cerca|proxim[oa]s?|aqui cerca|aqui mismo|de mi zona|mi zona|en mi zona|de por aqui|que esten cerca|que este cerca|que me queden cerca)\b/;
    if (cercaRe.test(w)) { cerca = true; w = w.replace(cercaRe, ' '); }
    return { w: w, orden: orden, cerca: cerca };
  }

  // "en mi comunidad / donde vivo / mi colonia" => la localidad del perfil.
  function extraerMiLocalidad(w) {
    var re = /\b(?:en |de |por |dentro de |desde |para )?(?:mi |nuestra )(?:localidad|comunidad|colonia|barrio|pueblo|ranchito|rancho|municipio)\b|\bdonde vivo\b|\bdonde estoy\b|\bde mi pueblo\b/;
    if (re.test(w)) return { w: w.replace(re, ' '), mia: true };
    return { w: w, mia: false };
  }

  // Localidad por nombre (con tolerancia a errores de voz).
  function extraerLocalidad(w, idx) {
    if (!idx.locs.length && !idx.genericas.length) return { w: w, loc: null };
    var envuelto = ' ' + w + ' ', i, e;

    for (i = 0; i < idx.locs.length; i++) {
      e = idx.locs[i];
      if (envuelto.indexOf(' ' + e.clave + ' ') >= 0) {
        return { w: envuelto.replace(' ' + e.clave + ' ', '  ').trim(), loc: e };
      }
    }

    var tokens = w.split(' ');
    // Nombre pegado que la voz separó ("cua cuila" => "cuacuila").
    for (var n = 2; n <= 3; n++) {
      for (var k = 0; k + n <= tokens.length; k++) {
        var junto = tokens.slice(k, k + n).join('');
        for (i = 0; i < idx.locs.length; i++) {
          e = idx.locs[i];
          if (e.sinEspacios.length >= 6 && (junto === e.sinEspacios || fon(junto) === fon(e.sinEspacios))) {
            var resto = tokens.slice(0, k).concat(tokens.slice(k + n));
            return { w: resto.join(' '), loc: e };
          }
        }
      }
    }

    // Palabra propia de una sola localidad ("tzinacapan").
    for (k = 0; k < tokens.length; k++) {
      var p = tokens[k];
      if (idx.palabras[p]) { tokens.splice(k, 1); return { w: tokens.join(' '), loc: idx.palabras[p] }; }
    }

    // Parecido de oído: solo si viene después de "en / de / por..." para no confundir con otros nombres.
    var prep = /\b(?:en|de|por|desde|hacia|rumbo a|cerca de|a|para|al|del|zona de|comunidad de|localidad de|colonia|barrio de|pueblo de)\s+(?:la |el |san |santa |santo )?([a-z0-9]{5,}(?: [a-z0-9]{3,})?)/g;
    var m, mejor = null;
    while ((m = prep.exec(w))) {
      var cands = [m[1]];
      if (m[1].indexOf(' ') > 0) cands.push(m[1].split(' ')[0], m[1].replace(/ /g, ''));
      for (var c = 0; c < cands.length; c++) {
        var cand = cands[c];
        if (cand.length < 5 || STOP[cand] || VERBOS[cand] || /^\d/.test(cand)) continue;
        // si es un oficio conocido, no es una localidad
        if (idx.diccionario[sing(cand)] || idx.diccionario[cand]) continue;
        var fonCand = fon(cand);
        idx.locs.forEach(function (loc) {
          var comparables = [loc.clave, loc.sinEspacios].concat(loc.clave.split(' ').filter(function (x) { return x.length >= 5 && COMUNES.indexOf(x) < 0; }));
          comparables.forEach(function (comp) {
            var d = Math.min(lev(cand, comp, 3), lev(fonCand, fon(comp), 3));
            var tol = comp.length >= 9 ? 2 : 1;
            if (d <= tol && (!mejor || d < mejor.d)) mejor = { loc: loc, d: d, texto: m[1], cand: cand };
          });
        });
      }
    }
    if (mejor) {
      var re = new RegExp('\\b' + mejor.texto.replace(/ /g, ' ') + '\\b');
      return { w: w.replace(re, ' '), loc: mejor.loc };
    }

    // Cabecera / municipio ("Zacapoaxtla"): sirve la localidad "Zacapoaxtla (Cabecera)" si existe.
    if (idx.municipio && envuelto.indexOf(' ' + idx.municipio + ' ') >= 0) {
      var quitado = envuelto.replace(' ' + idx.municipio + ' ', '  ').trim();
      return { w: quitado, loc: idx.genericas[0] || null, municipio: true };
    }
    return { w: w, loc: null };
  }

  function extraerCategoria(w, idx) {
    var m = /\b(?:de |en |la |del )?categoria(?: de| del| de la)?\s+([a-z0-9 ]{3,40})/.exec(w), mejor = null;
    if (m && idx.cats.length) {
      var dicho = m[1].trim().split(' ').slice(0, 3);
      for (var n = dicho.length; n >= 1 && !mejor; n--) {
        var frase = dicho.slice(0, n).join(' ');
        idx.cats.forEach(function (c) {
          if (mejor) return;
          if (c.clave === frase || c.palabras.indexOf(frase) >= 0 || (frase.length >= 5 && c.palabras.some(function (p) { return lev(frase, p, 2) <= 1; }))) mejor = c;
        });
        if (mejor) { w = w.replace(new RegExp('\\b(?:de |en |la |del )?categoria(?: de| del| de la)?\\s+' + frase + '\\b'), ' '); }
      }
      if (!mejor) w = w.replace(/\b(?:de |en |la |del )?categoria(?: de| del| de la)?\b/, ' ');
    }
    return { w: w, cat: mejor };
  }

  // Filtros propios de las vacantes.
  function extraerFiltrosVacante(w) {
    var f = {};
    if (/\bsin experiencia\b|\bno (?:pidan|piden|pida|requiera|requieren) experiencia\b|\bprincipiante\b|\bsin saber\b/.test(w)) { f.experiencia = 'Sin experiencia'; w = w.replace(/\bsin experiencia\b|\bno (?:pidan|piden|pida|requiera|requieren) experiencia\b|\bprincipiante\b/, ' '); }
    else {
      var m = /\b(?:con |de |minimo |al menos |hasta )?(6 meses|medio ano|seis meses|1 ano|un ano|2 anos|3 anos|5 anos|dos anos|tres anos|cinco anos) (?:de |minimo )?(?:experiencia)?/.exec(w);
      if (m && /experiencia|minimo/.test(w)) {
        var t = m[1];
        f.experiencia = /6 meses|medio ano|seis/.test(t) ? '6 meses mínimo' : /^(1|un) ano/.test(t) ? '1 año mínimo' : /^(2|dos)/.test(t) ? '2 años mínimo' : /^(3|tres)/.test(t) ? '3 años mínimo' : '5 años mínimo';
        w = w.replace(m[0], ' ').replace(/\bexperiencia\b/, ' ');
      }
    }
    var contratos = [
      ['Por obra', /\b(?:por obra|obra determinada|por proyecto)\b/],
      ['Temporada', /\b(?:de temporada|por temporada|temporada)\b/],
      ['Temporal', /\b(?:temporal(?:es)?|por unos dias|por poco tiempo|provisional)\b/],
      ['Eventual', /\b(?:eventual(?:es)?|por evento|ocasional)\b/],
      ['Fijo', /\b(?:fijo|de planta|base|permanente|contrato indefinido|tiempo completo|planta)\b/]
    ];
    for (var i = 0; i < contratos.length; i++) {
      if (contratos[i][1].test(w)) { f.contrato = contratos[i][0]; w = w.replace(contratos[i][1], ' '); break; }
    }
    var pagos = [
      ['Pago por destajo', /\b(?:por destajo|a destajo|destajo)\b/],
      ['Pago quincenal', /\b(?:quincenal(?:es)?|cada quincena|por quincena|a la quincena)\b/],
      ['Pago semanal', /\b(?:semanal(?:es)?|cada semana|por semana|a la semana|pago de la semana|raya semanal)\b/],
      ['Pago mensual', /\b(?:mensual(?:es)?|cada mes|por mes|al mes)\b/],
      ['Pago al día', /\b(?:al dia|por dia|diari[oa]s?|cada dia|pago diario|el mismo dia|pago del dia)\b/]
    ];
    for (i = 0; i < pagos.length; i++) {
      if (pagos[i][1].test(w)) { f.tipo_pago = pagos[i][0]; w = w.replace(pagos[i][1], ' '); break; }
    }
    return { w: w, filtros: f };
  }

  // Qué dice que quiere QUITAR de la búsqueda: "quita el precio", "sin localidad".
  function extraerQuitar(w) {
    var quitar = [], re = /\b(?:(?:quita|quitar|quitale|quitales|borra|borrar|borrale|elimina|eliminar|eliminale|olvida|olvidate de)\s+(?:el |la |los |las |ese |esa |todo el |todos los |todas las )?(?:filtro |filtros )?(?:de |del |por )?|sin (?:el |la )?filtro (?:de |del |por )?)(precio|precios|localidad|localidades|ubicacion|zona|lugar|categoria|busqueda|palabra|salario|sueldo|contrato|experiencia|orden|todo|filtros)\b/g, m;
    var mapa = {
      precio: ['precio_min', 'precio_max', 'salario_min', 'salario_max'], precios: ['precio_min', 'precio_max', 'salario_min', 'salario_max'],
      salario: ['salario_min', 'salario_max'], sueldo: ['salario_min', 'salario_max'],
      localidad: ['localidad_id'], localidades: ['localidad_id'], ubicacion: ['localidad_id'], zona: ['localidad_id'], lugar: ['localidad_id'],
      categoria: ['categoria_id'], busqueda: ['q'], palabra: ['q'], contrato: ['contrato'], experiencia: ['experiencia'], orden: ['orden']
    };
    var todo = false;
    while ((m = re.exec(w))) {
      if (m[1] === 'todo' || m[1] === 'filtros') todo = true;
      else (mapa[m[1]] || []).forEach(function (k) { quitar.push(k); });
    }
    if (todo || quitar.length) w = w.replace(re, ' ');
    return { w: w, quitar: unicos(quitar), todo: todo };
  }

  // ====================================================================
  //  6. QUÉ BUSCA (la palabra del oficio / cosa)
  // ====================================================================

  function acentuar(p, opts) {
    var c = opts && opts.crudo && opts.crudo[p];
    if (c && c !== p) return c;            // la persona (o el reconocedor) ya puso acento o ñ
    return ACENTOS[p] || c || p;
  }

  function elegirContenido(w, idx, opts) {
    opts = opts || {};
    // Frases de varias palabras
    var multi = null;
    MULTI.forEach(function (f) { if (!multi && (' ' + w + ' ').indexOf(' ' + sinAcentos(f) + ' ') >= 0) multi = f; });
    if (multi) return { q: multi, alternativas: [], origen: 'multi', resto: w.replace(sinAcentos(multi), ' ') };

    var tokens = w.split(' ').filter(function (t) {
      return t && !STOP[t] && !/^\d+$/.test(t) && t.length >= 3;
    });
    if (!tokens.length) return { q: '', alternativas: [], origen: 'nada', resto: w };

    var mejor = null;
    tokens.forEach(function (t, pos) {
      var s = sing(t), puntos = 0, canon = null;
      if (idx.diccionario[s] || idx.diccionario[t]) { puntos = 4; canon = idx.diccionario[s] || idx.diccionario[t]; }
      else if (idx.raices[raiz(s)] && raiz(s).length >= 4) { puntos = 3.5; canon = idx.raices[raiz(s)][0]; }
      else if (s.length >= 6) {
        var mejorD = 9, cm = null;
        Object.keys(idx.diccionario).forEach(function (k) {
          var d = Math.min(lev(s, k, 2), lev(fon(s), fon(k), 2));
          if (d < mejorD) { mejorD = d; cm = k; }
        });
        if (mejorD <= (s.length >= 9 ? 2 : 1)) { puntos = 3; canon = idx.diccionario[cm]; }
      }
      if (!puntos) {
        if (VERBOS[t] || /(?:ando|endo|ado|ido|ar|er|ir)$/.test(t) && t.length > 6) puntos = 0.2;
        else puntos = 1;
      }
      puntos += pos * 0.01; // a igualdad, la palabra más al final
      if (!mejor || puntos > mejor.puntos) mejor = { puntos: puntos, texto: t, s: s, canon: canon };
    });
    if (!mejor || mejor.puntos < 0.5) return { q: '', alternativas: [], origen: 'nada', resto: w };

    var q = mejor.canon ? mejor.canon : acentuar(mejor.s, opts);
    // Si lo dicho y el diccionario coinciden en raíz, se usa lo que dijo la persona (suena natural en el chip).
    if (mejor.canon && raiz(mejor.s) === raiz(mejor.canon) && mejor.s.length >= 4) q = acentuar(mejor.s, opts);
    var alternativas = [];
    var r = raiz(q);
    if (idx.raices[r]) idx.raices[r].forEach(function (otro) { if (sinAcentos(otro) !== sinAcentos(q)) alternativas.push(otro); });
    if (r.length >= 4 && r !== sinAcentos(q)) alternativas.push(r);
    idx.subs.forEach(function (sc) {
      if (raiz(sc.clave.split(' ')[0]) === r && sinAcentos(sc.nombre) !== sinAcentos(q)) alternativas.unshift(sc.nombre);
    });
    return {
      q: q, alternativas: unicos(alternativas).slice(0, 3), origen: mejor.canon ? 'diccionario' : 'libre',
      resto: w.replace(new RegExp('\\b' + mejor.texto + '\\b'), ' ')
    };
  }

  // ====================================================================
  //  7. PANTALLAS Y DESTINOS
  // ====================================================================

  var DESTINOS = {
    inicio_trabajador: { url: '/usuario', modo: 'worker', nombre: 'tu inicio de trabajador', pantalla: 'Inicio', que: 'Aquí ves un resumen: servicios activos, tus publicaciones y accesos rápidos.' },
    inicio_empleador: { url: '/usuario/empleador', modo: 'employer', nombre: 'tu inicio de empleador', pantalla: 'Inicio de empleador', que: 'Aquí ves tus vacantes, postulantes y accesos rápidos para contratar.' },
    ver_servicios: { url: '/usuario/verEmpleos', modo: 'worker', nombre: 'Ver empleos', pantalla: 'Ver empleos', que: 'Aquí ves a los profesionales que ofrecen sus servicios (plomeros, electricistas, etc.) y puedes contactarlos.' },
    buscar_vacantes: { url: '/usuario/buscar-talento', modo: 'employer', nombre: 'Buscar trabajo', pantalla: 'Buscar trabajo', que: 'Aquí ves las vacantes que publican los empleadores y puedes postularte.' },
    publicar_servicio: { url: '/usuario/publicarEmpleo', modo: 'worker', nombre: 'Publicar empleo', pantalla: 'Publicar empleo', que: 'Aquí ofreces tus servicios: pones título, descripción, precio y ubicación para que te contraten.' },
    publicar_vacante: { url: '/usuario/publicar-vacante', modo: 'employer', nombre: 'Publicar vacante', pantalla: 'Publicar vacante', que: 'Aquí publicas una vacante cuando quieres contratar a alguien.' },
    mis_servicios: { url: '/usuario/misEmpleos', modo: 'worker', nombre: 'Mis empleos', pantalla: 'Mis empleos', que: 'Aquí están los servicios que tú publicaste; puedes editarlos, pausarlos o agregar tu currículum.' },
    mis_vacantes: { url: '/usuario/mis-vacantes', modo: 'employer', nombre: 'Mis vacantes', pantalla: 'Mis vacantes', que: 'Aquí están las vacantes que publicaste; puedes editarlas, cerrarlas o ver cuántos se postularon.' },
    postulantes: { url: '/usuario/postulantes', modo: 'employer', nombre: 'Postulantes', pantalla: 'Postulantes', que: 'Aquí ves a quienes se postularon a tus vacantes y decides a quién contratar.' },
    solicitudes: { url: '/usuario/solicitudes', modo: 'worker', nombre: 'Solicitudes', pantalla: 'Solicitudes', que: 'Aquí ves las solicitudes que te mandan por tus servicios y las aceptas o rechazas.' },
    pronostico: { url: '/usuario/pronostico', modo: 'employer', nombre: 'Pronóstico de demanda', pantalla: 'Pronóstico', que: 'Aquí ves una predicción de la demanda de trabajo para planear tus contrataciones.' },
    perfil: { url: '/usuario/profile', modo: null, nombre: 'tu perfil', pantalla: 'Perfil', que: 'Aquí editas tus datos, tu foto, tu localidad, tu contraseña y verificas tu identidad.' },
    acceso: { url: '/acceso', modo: null, publico: true, nombre: 'Iniciar sesión', pantalla: 'Acceso', que: 'Aquí entras con tu correo o creas una cuenta nueva.' },
    registro: { url: '/acceso', modo: null, publico: true, nombre: 'el registro', pantalla: 'Registro', que: 'Aquí creas tu cuenta gratis.', despues: { clic: '#registerTab' } },
    inicio_publico: { url: '/', modo: null, publico: true, nombre: 'la página principal', pantalla: 'Inicio', que: 'Aquí ves los servicios y vacantes disponibles sin necesidad de entrar.' },
    anunciar: { url: '/anunciar', modo: null, publico: true, nombre: 'Anunciar mi negocio', pantalla: 'Anunciar', que: 'Aquí contratas un espacio de publicidad para tu negocio.' },
    contacto: { url: '/servicio-cliente', modo: null, publico: true, nombre: 'Servicio al cliente', pantalla: 'Servicio al cliente', que: 'Aquí encuentras cómo contactar al equipo para recibir ayuda.' },
    acerca: { url: '/acerca-de', modo: null, publico: true, nombre: 'Acerca de', pantalla: 'Acerca de', que: 'Aquí conoces qué es SINTECZATE y quiénes lo hacen.' },
    terminos: { url: '/terminos', modo: null, publico: true, nombre: 'Términos y condiciones', pantalla: 'Términos', que: 'Aquí están los términos y condiciones de uso.' }
  };

  // Frases (ya sin acentos) que llevan a cada destino "propio" (con mi/mis o etiqueta exacta).
  var RUTAS_PROPIAS = [
    ['mis_vacantes', R('mis [bv]acantes|[bv]acantes (?:que )?(?:yo )?(?:publique|he publicado|tengo publicadas|mias)|mis ofertas(?: de (?:trabajo|empleo))?|mis publicaciones de (?:trabajo|empleo)|mis chambas publicadas')],
    ['mis_servicios', R('mis (?:empleos|servicios|anuncios|publicaciones)|lo que (?:ofrezco|publique)|servicios (?:que )?(?:yo )?(?:publique|ofrezco|tengo)|mi curriculum|mi cv|mis curriculums|mi servicio')],
    ['postulantes', R('postulantes|candidatos|aspirantes|quien(?:es)? se (?:ha[ns]? )?postul\\w+|(?:gente|personas) que se (?:postulo|postularon|ha postulado)|mis postulantes|solicitantes de empleo')],
    ['solicitudes', R('solicitudes|mis solicitudes|pedidos|mis pedidos|me (?:han |hicieron )?(?:pedido|solicitado|contratado|contactado)|quien me (?:ha )?(?:pidio|solicito|contrato|contacto)|contrataciones|mis contrataciones|trabajos que me piden')],
    ['pronostico', R('pronostico|prediccion|predicciones|demanda|estadisticas?|tendencias?|proyecciones?|graficas?|analisis')],
    ['perfil', R('mi perfil|perfil|mi cuenta|mis datos|mi informacion|editar (?:mi )?(?:perfil|datos|informacion)|mi foto|foto de perfil|contrasena|password|(?:cambiar|cambio|cambia|cambiarme|actualizar|actualizo|editar|edito|modificar|modifico|poner|pongo|agregar|agrego|subir|subo|corregir|corrijo) (?:mi |mis )?(?:telefono|numero|correo|nombre|localidad|ubicacion|descripcion|foto|contrasena|datos|apellido|direccion)|verific\\w+ (?:mi )?(?:identidad|cuenta)|mis documentos|configuracion|ajustes|mi ine|mi credencial')],
    ['inicio', R('inicio|pagina principal|pantalla principal|principal|panel|dashboard|tablero|home|menu principal|pagina de inicio|pagina inicial')]
  ];

  // ====================================================================
  //  8. AYUDA POR PANTALLA (también sirve de prueba: todos estos ejemplos deben entenderse)
  // ====================================================================

  var PAGINAS = {
    ver_servicios: { titulo: 'Ver empleos', modo: 'worker', aqui: ['Busca un plomero barato en mi localidad', 'Solo los de menos de quinientos pesos', 'Ordénalos por los más cercanos', 'Quita el filtro de precio', 'Abre el primero', 'Léeme los resultados'] },
    buscar_vacantes: { titulo: 'Buscar trabajo', modo: 'employer', aqui: ['Busco trabajo de mesero', 'Que paguen más de trescientos pesos', 'Solo en Xaltipan', 'Pago diario y sin experiencia', 'Ordena por el mejor pagado', 'Abre la primera'] },
    publicar_servicio: { titulo: 'Publicar empleo', modo: 'worker', aqui: ['¿Qué pongo en esta pantalla?', 'Ir a mis empleos', 'Quiero publicar una vacante'] },
    publicar_vacante: { titulo: 'Publicar vacante', modo: 'employer', aqui: ['¿Qué pongo en esta pantalla?', 'Ir a mis vacantes', 'Quiero ofrecer mis servicios'] },
    mis_servicios: { titulo: 'Mis empleos', modo: 'worker', aqui: ['Publicar un servicio nuevo', 'Ir a solicitudes', '¿Dónde pongo mi currículum?'] },
    mis_vacantes: { titulo: 'Mis vacantes', modo: 'employer', aqui: ['Publicar una vacante', 'Ver postulantes', 'Pronóstico de demanda'] },
    postulantes: { titulo: 'Postulantes', modo: 'employer', aqui: ['Ir a mis vacantes', 'Publicar una vacante'] },
    solicitudes: { titulo: 'Solicitudes', modo: 'worker', aqui: ['Ir a mis empleos', 'Publicar un servicio'] },
    pronostico: { titulo: 'Pronóstico', modo: 'employer', aqui: ['Ir a mis vacantes', 'Ver postulantes'] },
    inicio_trabajador: { titulo: 'Inicio', modo: 'worker', aqui: ['Busco un electricista', 'Publicar mi servicio', 'Ver mis solicitudes'] },
    inicio_empleador: { titulo: 'Inicio de empleador', modo: 'employer', aqui: ['Quiero contratar personal', 'Ver postulantes', 'Busco trabajo de ayudante'] },
    perfil: { titulo: 'Perfil', modo: null, aqui: ['Ir a mi inicio', '¿Cómo cambio mi localidad?'] },
    home: { titulo: 'Inicio', modo: null, aqui: ['Busco un plomero en Xaltipan', 'Quiero ver las vacantes', 'No sé por dónde empezar', 'Quiero registrarme'] },
    acceso: { titulo: 'Acceso', modo: null, aqui: ['Quiero crear mi cuenta', 'Ya tengo cuenta', 'Ir al inicio'] }
  };

  var EJEMPLOS = [
    { grupo: 'Buscar a un profesional', icono: 'mdi-account-search', items: [
      'Busco un plomero', 'Necesito un electricista barato', 'Ocupo un albañil en mi localidad',
      'Plomero en Xaltipan de menos de quinientos pesos', 'Quiero contratar un carpintero', 'Se me tapó el drenaje'] },
    { grupo: 'Buscar trabajo', icono: 'mdi-briefcase-search', items: [
      'Busco trabajo', 'Busco chamba de mesero', 'Vacantes de ayudante cerca de mí',
      'Trabajo que pague más de trescientos pesos al día', 'Empleo sin experiencia'] },
    { grupo: 'Filtrar y ordenar', icono: 'mdi-filter-variant', items: [
      'Solo los más baratos', 'Ordénalos por cercanía', 'Mejor pagado primero', 'Solo en mi comunidad',
      'Quita el filtro de precio', 'Limpiar filtros'] },
    { grupo: 'Publicar', icono: 'mdi-bullhorn-outline', items: [
      'Quiero ofrecer mis servicios', 'Quiero publicar una vacante', 'Necesito contratar personal',
      'Quiero anunciar mi negocio', 'No sé dónde publicar'] },
    { grupo: 'Ir a una pantalla', icono: 'mdi-compass-outline', items: [
      'Ir a mi perfil', 'Abre mis vacantes', 'Mis solicitudes', 'Ver postulantes', 'Pronóstico de demanda', 'Ir al inicio'] },
    { grupo: 'Cuenta y modo', icono: 'mdi-account-cog-outline', items: [
      'Cambia a modo empleador', 'Cambia a modo trabajador', 'Abre mis notificaciones', 'Cerrar sesión'] },
    { grupo: 'Control del asistente', icono: 'mdi-microphone-settings', items: [
      'Ayuda', 'Repite', 'Cállate', 'Silencia la voz', 'Activa la voz', 'Probar el micrófono', 'Ocúltate'] }
  ];

  // ====================================================================
  //  9. FORMAS DE DECIR (patrones de intención)
  // ====================================================================

  var RE = {
    quitarInicio: /^(?:(?:hola|buenos dias|buenas tardes|buenas noches|buenas|hey|ey|oye|oiga|mira|ok|okey|a ver|este|pues|bueno|disculpa|perdon|por favor|porfa|porfavor|que onda|que tal|sintec|sin tec|sin tek|cintec|asistente|sintecz\w*|sintezate|oye asistente|hola asistente|me puedes|me podrias|me podria|puedes|podrias|podria|quisiera que|quiero que|favor de|me ayudas a|ayudame a|me ayudas con|oye me)(?:\s+|$))+/,
    saludo: R('hola|buenas|buenos dias|buenas tardes|buenas noches|que onda|que tal|hey|saludos|buen dia'),
    gracias: R('gracias|muchas gracias|mil gracias|te lo agradezco|muy amable|excelente|perfecto|genial|chido|padre|muy bien|super|estupendo|buen trabajo'),
    adios: R('adios|hasta luego|nos vemos|chao|chau|bye|hasta pronto|me voy|ya me voy'),
    ocultar: R('ocultate|ocultarte|ocultar|oculta|esconde|escondete|esconderte|desaparece|desaparecer|minimiza|minimizate|cierrate|cierra el asistente|cierra asistente|quitate|apartate|vete|largate|ya no te necesito|puedes irte|retirate|descansa|duermete|apagate|desactivate'),
    callar: /^(?:callate|silencio|para de hablar|deja de hablar|ya basta|detente|alto|no hables|basta|para|ya callate|calla|ya|callate por favor|silencio por favor|para por favor|ya callate por favor)$/,
    silenciar: R('silencia(?:r)?(?: la voz)?|mutea(?:r)?|sin voz|desactiva(?:r)? (?:la )?voz|quita(?:r)? (?:la )?voz|solo texto|apaga(?:r)? (?:la )?voz|no me hables|apaga tu voz|que no hables'),
    activarVoz: R('activa(?:r)? (?:la )?voz|prende(?:r)? (?:la )?voz|enciende(?:r)? (?:la )?voz|ponle voz|con voz|habla(?:me)?|puedes hablar|vuelve a hablar|quiero oirte|quiero escucharte|habla otra vez'),
    repetir: R('repite|repitelo|repetir|repiteme|otra vez|de nuevo|que dijiste|como dijiste|no te (?:escuche|oi|entendi)|no escuche|no oi|puedes repetir|dilo de nuevo|dilo otra vez|que dices'),
    probarMic: R('(?:prueba|probar|probando|pruebame|probemos|prueban) (?:el |mi |tu |un |la |de )?(?:microfono|mic|audio|sonido)|prueba de (?:microfono|audio|sonido)|test (?:de|del) (?:microfono|audio)|(?:revisa|revisar|checa|checar|verifica|verificar|comprueba|comprobar) (?:el |mi |tu )?(?:microfono|mic)|no me (?:escuchas|escucha|oyes|oye|entiendes|entiende)|no (?:funciona|sirve|jala|va) (?:el |mi |tu )?(?:microfono|mic)|(?:el |mi )?microfono no (?:funciona|sirve|jala|va|sirve)|no (?:me )?(?:capta|detecta|detectas|capta mi voz)|no sirve la voz|no hay sonido|no se escucha'),
    ayuda: R('ayuda|auxilio|comandos|que puedo (?:decir|pedirte|pedir|decirte)|que sabes hacer|que puedes hacer|como te uso|como funcionas|instrucciones|que haces|ayudame|ayudenme|muestrame los comandos|lista de comandos|ejemplos|que entiendes|que te puedo decir|dime que puedo'),
    orientar: R('no se (?:que|como|donde|por donde|a donde|cual)|no entiendo|como funciona|como empiezo|como inicio|como se usa|como le hago|por donde (?:empiezo|inicio|comienzo)|que hago|que puedo hacer|que es esto|que ofrecen|que hacen|de que trata|de que se trata|para que sirve|primera vez|soy nuevo|soy nueva|nuevo aqui|nueva aqui|que opciones|que hay aqui|que hay|orientame|guiame|explicame|informacion|no se nada|estoy perdido|estoy perdida|me perdi|donde empiezo|como uso esto|como se maneja|para que es esto|de que va|que puedo encontrar|que se puede hacer|que se hace aqui|quiero saber como'),
    identidad: R('quien eres|como te llamas|cual es tu nombre|que eres|eres un robot|eres una persona|quien te hizo|quien te creo|eres real|cual es tu nombre'),
    queEsto: R('donde estoy|en que (?:pantalla|pagina|seccion) estoy|que (?:es|hay en|puedo hacer en|se hace en|hago en) (?:esta|esta) ?(?:pantalla|pagina|seccion)|explicame (?:esta|la) (?:pantalla|pagina|seccion)|para que sirve (?:esta|la) (?:pantalla|pagina|seccion)|que es esta (?:pantalla|pagina|seccion)|que pongo aqui|que pongo en esta|que hago aqui|que puedo hacer aqui|como se usa esta|que debo poner|donde pongo'),
    atras: R('regresa|regresar|atras|vuelve|volver|pagina anterior|regresame|retrocede|retroceder|vuelve atras|pantalla anterior'),
    recargar: R('recarga|recargar|actualiza|actualizar|refresca|refrescar|reinicia la pagina'),
    abajo: R('baja|bajar|desplaza(?:te)? hacia abajo|ve hacia abajo|scroll abajo|mas abajo|ve al final|hasta abajo|final de la pagina|baja la pagina|ve abajo'),
    arriba: R('sube|subir|ve arriba|hacia arriba|al inicio de la pagina|hasta arriba|principio de la pagina|sube la pagina|ve al principio|regresa arriba|arriba de la pagina'),
    cerrarSesion: R('cierra(?:r)? (?:mi |la )?sesion|salir de (?:mi |la )?(?:cuenta|sesion)|desconecta(?:r|me)|log ?out|sal de mi cuenta|salgo de mi cuenta|termina(?:r)? (?:mi )?sesion|cerrar sesion'),
    salir: R('salir|salgo|quiero salir|salte|sal'),
    login: R('iniciar sesion|inicia sesion|iniciar mi sesion|entrar a mi cuenta|entrar a mi perfil|acceder|ingresar|login|loguearme|ya tengo cuenta|ya tengo mi cuenta|ya estoy registrado|ya estoy registrada|ya soy usuario|entrar|hacer login'),
    registro: R('registrar(?:me)?|crear (?:una |mi )?cuenta|abrir (?:una |mi )?cuenta|darme de alta|registro|inscribirme|nueva cuenta|hacerme una cuenta|hacer una cuenta|quiero una cuenta|crearme una cuenta|registrate|unirme|unirse'),
    notificaciones: R('notificaciones|notificacion|avisos|aviso|campana|alertas|mensajes nuevos|que hay de nuevo|tengo algo nuevo|tengo nuevo'),
    leer: R('(?:leeme|lee|dime|cuentame|enumera|dictame|menciona|repasa) (?:los |las |todos los |todas las )?(?:resultados|vacantes|servicios|opciones|primeros|primeras|primer|todo)|que encontraste|que hay en los resultados|cuales son los resultados|cuales son las opciones|lee los resultados'),
    contar: R('cuantos (?:hay|resultados|servicios|vacantes|encontraste|salieron|aparecen)|cuantas (?:hay|vacantes|opciones|encontraste|salieron)'),
    abrirN: /\b(?:abre|abrir|abreme|ver|veo|muestrame|mostrar|entra a|entrar a|entra al|quiero ver|dame|vamos a|ve a|ir a|dime de|detalle de|detalles de|informacion de|mas informacion de)\s+(?:el|la|los|las|al|a la)?\s*(primer[oa]?|segund[oa]|tercer[oa]?|cuart[oa]|quint[oa]|ultim[oa]|1|2|3|4|5|uno|dos|tres|cuatro|cinco)\b(?: resultado| opcion| servicio| vacante| anuncio| de la lista)?/,
    postular: R('postula(?:te|r|rme)?|aplica(?:r)?|aplicar(?:me)?|solicita(?:r)?|contacta(?:r)?|contactar(?:lo|la)?|contratalo|contratala|contratar(?:lo|la)?'),
    quitarFiltros: R('(?:limpia|limpiar|borra|borrar|quita|quitar|elimina|eliminar|reinicia|reiniciar|resetea|resetear|olvida) (?:todos |todas )?(?:los |las |mis )?(?:filtros|busqueda|busquedas)|sin filtros|ver todo|ver todos|ver todas|mostrar todo|muestrame todo|muestra todo|todos los resultados|quitar todo|empezar de nuevo|desde cero|nueva busqueda'),
    cambiarModo: /\b(?:modo|cambia(?:r|me)?(?: a| al| de)?|pon(?:me|er)?|pasa(?:r|me)?|activa(?:r)?|entra(?:r)?|quiero (?:ser|estar|entrar|usar)|soy|usar como)\b.*\b(empleador|empleadores|patron|contratante|jefe|trabajador|trabajadora|empleado)\b/,
    si: /^(?:si|sii|siii|simon|claro|va|vale|ok|okey|dale|adelante|por favor|aja|ajam|afirmativo|correcto|exacto|de acuerdo|esta bien|hazlo|llevame|andale|orale|que si|asi es|eso|eso es|yes|sipi|sale|aha|uhum|por supuesto|obvio|desde luego|con gusto)(?: .*)?$/,
    no: /^(?:no|nel|nop|nope|negativo|mejor no|nada|para nada|todavia no|no gracias|nunca|no quiero|no por ahora|ahorita no|tampoco|ni modo|no es eso|no era eso)(?: .*)?$/,
    cancelar: /^(?:cancela(?:r)?|olvidalo|olvida|no importa|dejalo|ya no|ya no quiero|nada|mejor no|cancelar todo|dejalo asi|no gracias|olvidalo todo)$/
  };

  // Verbos de búsqueda / necesidad
  var VERBOS_BUSCAR = R('busc\\w*|necesit\\w*|nececit\\w*|ocup(?:o|ar|amos|a)|requier\\w*|quier\\w*|quisier\\w*|me gustaria|me urge|encuentr\\w*|encontrar|consig\\w*|dame|damelo|muestr\\w*|ensen\\w*|ver|veo|hay|habra|tienen|tiene|existe|existen|donde (?:hay|encuentro|consigo|puedo conseguir)|conoces|recomiend\\w*|sugier\\w*|cuales|filtr\\w*|list\\w*|ando buscando|estoy buscando|estamos buscando|se necesita|se ocupa|se solicita|solicit\\w*|contrat\\w*|emplear|conseguir|quien (?:me )?(?:haga|arregle|repare|cuide|limpie|pinte|construya|instale|ayude)|alguien que|una persona que|un trabajador que|alguien para|quien (?:pueda|sepa)|ayuda con|ayuda para');
  // ¿Quién busca? Servicios (profesionales) o vacantes (empleos).
  var PIDE_EMPLEO = R('(?:busco|buscar|buscando|necesito|nececito|ocupo|quiero|quisiera|me urge|consigo|conseguir|encuentro|encontrar|hay|habra|tienen|donde (?:hay|consigo|encuentro)|ando buscando|estoy buscando|estamos buscando|dame|muestrame|ver) (?:un |una |algun |alguna |algo de |otro |otra )?(?:trabajo|trabajos|trabajito|empleo|empleos|chamba|chambas|chambita|vacante|vacantes|puesto|oferta de trabajo|ofertas de trabajo|oferta de empleo|ofertas de empleo|oferta laboral|ofertas laborales)|quiero trabajar|necesito trabajar|quiero emplearme|me quiero emplear|donde trabajar|donde puedo trabajar|donde puedo emplearme|postularme|postular(?:me)? a|bolsa de trabajo|estan contratando|estan solicitando|contratan|solicitan personal|se solicita|se solicitan|se necesita personal|se ocupa personal|andan contratando|empleo de|trabajo de|chamba de|trabajo como|empleo como|chamba como|[bv]acantes? de|[bv]acantes? para|ofertas? de|trabajo en|chamba en|empleo en|[bv]acantes? en|chambas|trabajo cerca|empleo cerca|chamba cerca');
  var GENERICO_CONTRATAR = R('personal|gente|empleados?|trabajadores?|ayudantes?|colaboradores?|equipo|operarios?|obreros?|peones|auxiliares|cuadrilla|plantilla|mano de obra|gente de trabajo');

  var VERBOS_PUBLICAR = R('publicar|publicarme|publicarlo|publicarla|publica|publicame|publico|publicamos|publicando|publiqu\\w*|anunciar|anunciarme|anunciarlo|anuncia|anunciame|anunciando|ofrecer|ofrezco|ofrecerme|dar de alta|dame de alta|promocion\\w*|dar empleo|dar trabajo|dar chamba|abrir una vacante|abrir vacante|lanzar|vender mis servicios|promover');
  // Verbos más generales: solo cuentan si junto traen algo que se publica (servicio, vacante, anuncio...).
  var VERBOS_PUBLICAR_DEBIL = R('poner|pon|ponga|subir|sube|suba|crear|crea|cree|registrar|registra|agregar|agrega|anadir|anade|nuevo|nueva');
  var OBJETO_PUBLICAR = R('[bv]acantes?|servicios?|anuncios?|empleos?|trabajos?|chambas?|ofertas?|negocio|publicidad|oficio');
  var PUB_VACANTE_FUERTE = R('ofrecer (?:un |una )?(?:trabajo|empleo|chamba|puesto)|dar (?:trabajo|empleo|chamba)|ofrezco (?:trabajo|empleo|chamba)|contratar (?:personal|gente|empleados?|trabajadores?|ayudantes?|a alguien|alguien|colaboradores|a varios)|necesito (?:personal|gente|empleados|trabajadores|ayudantes|contratar)|ocupo (?:personal|gente|empleados|trabajadores|ayudantes)|busco (?:personal|gente|empleados|trabajadores|ayudantes|colaboradores)|buscar (?:gente|personal|empleados|trabajadores)|tengo (?:un |una )?(?:negocio|taller|tienda|empresa|restaurante|local|rancho|obra) y (?:necesito|ocupo|busco)|abrir (?:una )?[bv]acante|nueva [bv]acante|reclutar|reclutamiento|dar empleo|quiero contratar (?:personal|gente|empleados|trabajadores|ayudantes)|necesito (?:a )?(?:varios|unos) (?:ayudantes|trabajadores|empleados)|publicar (?:un |una )?(?:trabajo|empleo|chamba) para');
  var PUB_VACANTE_DEBIL = R('[bv]acantes?|ofertas? de (?:trabajo|empleo)|oferta laboral|ofertar trabajo|puesto');
  var PUB_SERVICIO_FUERTE = R('(?:que )?(?:\\w+ ){0,2}me (?:contrate|contraten|busque|busquen|encuentre|encuentren|llamen)|ofrecer mis servicios|ofrezco mis servicios|ofrecer mi trabajo|ofrecer mi oficio|vender mis servicios|promocionar mis servicios|ser contratado|ser contratada|trabajar por mi cuenta|me dedico a|dar mis servicios|anunciar mis servicios|anunciarme como');
  var PUB_SERVICIO = R('(?:mis |mi )?servicios?|mi oficio|mi trabajo|mis trabajos|lo que (?:se|hago|ofrezco)|clientes|empleo|ofrecerme|anunciarme|ofrecer mis|publicar empleo');
  var PUB_ANUNCIO = R('anuncio publicitario|publicidad|promocionar (?:mi )?(?:negocio|tienda|empresa|local|restaurante|marca|producto)|anunci(?:ar|o|arme|amos|a) (?:mi |mis |nuestro |nuestra )?(?:negocio|tienda|empresa|local|restaurante|marca|producto|evento)|banner|espacio publicitario|poner publicidad|promocion de mi negocio|anunciar mi|promocionar mi|hacer publicidad|anuncios? de pago|pagar por anunciar|que mi negocio salga|que vean mi negocio');

  // ====================================================================
  //  10. INTERPRETAR
  // ====================================================================

  function respuestaSimple(intent, extra) {
    var o = { intent: intent };
    if (extra) Object.keys(extra).forEach(function (k) { o[k] = extra[k]; });
    return o;
  }

  var ORDINALES = { primer: 0, primero: 0, primera: 0, '1': 0, uno: 0, segundo: 1, segunda: 1, '2': 1, dos: 1, tercer: 2, tercero: 2, tercera: 2, '3': 2, tres: 2, cuarto: 3, cuarta: 3, '4': 3, cuatro: 3, quinto: 4, quinta: 4, '5': 4, cinco: 4, ultimo: -1, ultima: -1 };

  function ordinalDe(w) {
    var m = /\b(primer[oa]?|segund[oa]|tercer[oa]?|cuart[oa]|quint[oa]|ultim[oa]|uno|dos|tres|cuatro|cinco|[1-5])\b/.exec(w);
    return m ? (ORDINALES[m[1]] !== undefined ? ORDINALES[m[1]] : null) : null;
  }

  // Responde a lo que la persona dice cuando el asistente le acaba de preguntar algo.
  function resolverPendiente(w, pend, ctx) {
    if (!pend) return null;
    if (pend.tipo === 'confirmar') {
      if (RE.si.test(w) && w.split(' ').length <= 4) return respuestaSimple('si', { pend: pend });
      if (RE.no.test(w) && w.split(' ').length <= 4) return respuestaSimple('no', { pend: pend });
      return null;
    }
    if (pend.tipo === 'elegir' && pend.opciones) {
      var cortas = w.split(' ').length <= 6;
      if (cortas) {
        for (var i = 0; i < pend.opciones.length; i++) {
          var o = pend.opciones[i];
          if (o.claves && new RegExp('\\b(?:' + o.claves + ')\\b').test(w)) return respuestaSimple('elegir', { id: o.id, opcion: o, pend: pend });
        }
        var n = ordinalDe(w);
        if (n !== null && n >= 0 && n < pend.opciones.length && /\b(?:opcion|primer|segund|tercer|cuart|quint|la |el |numero|uno|dos|tres|^\d)/.test(w)) {
          return respuestaSimple('elegir', { id: pend.opciones[n].id, opcion: pend.opciones[n], pend: pend });
        }
        if (RE.no.test(w) || RE.cancelar.test(w)) return respuestaSimple('cancelar', { pend: pend });
      }
      return null;
    }
    if (pend.tipo === 'preguntar') {
      if (RE.cancelar.test(w) || (RE.no.test(w) && w.split(' ').length <= 3)) return respuestaSimple('cancelar', { pend: pend });
      return { forzar: pend.para }; // se interpreta como respuesta a la pregunta
    }
    return null;
  }

  function interpretar(texto, ctx) {
    ctx = ctx || {};
    var original = String(texto == null ? '' : texto).trim();
    var w = preparar(original);
    if (!w) return respuestaSimple('nada');

    var forzar = null;
    var pr = resolverPendiente(w, ctx.pendiente, ctx);
    if (pr && pr.intent) { pr.texto = original; return pr; }
    if (pr && pr.forzar) forzar = pr.forzar;

    // Quita muletillas del inicio ("oye, por favor...") y la palabra de llamada.
    var sinInicio = w.replace(RE.quitarInicio, '').trim();
    var llamado = w !== sinInicio;
    w = sinInicio;
    if (!w) {
      if (/\b(?:hola|buenas|buenos dias|buenas tardes|buenas noches|que onda|que tal|hey|saludos)\b/.test(preparar(original))) return respuestaSimple('saludo', { texto: original });
      return respuestaSimple('llamado', { texto: original });
    }
    var tokens = w.split(' '), n = tokens.length;
    var idx = indice(ctx);
    var crudo = {};
    String(original).toLowerCase().split(/[\s,.;:!?¿¡"()]+/).forEach(function (t) {
      if (!t) return;
      crudo[sinAcentos(sing(t))] = sing(t);
    });
    var logueado = ctx.logueado !== false && ctx.logueado !== undefined ? !!ctx.logueado : !!ctx.logueado;
    var salida;

    // ---------- Respuestas cortas de cortesía y control ----------
    if (!forzar) {
      if (RE.cancelar.test(w)) return respuestaSimple('cancelar', { texto: original });
      if (n <= 5 && RE.gracias.test(w) && !RE.ayuda.test(w)) return respuestaSimple('gracias', { texto: original });
      if (n <= 4 && RE.adios.test(w)) return respuestaSimple('adios', { texto: original });
      if (n <= 5 && RE.saludo.test(w) && !/\b(?:busco|necesito|ocupo|quiero|publicar)\b/.test(w)) return respuestaSimple('saludo', { texto: original });
      if (RE.identidad.test(w)) return respuestaSimple('identidad', { texto: original });
      if (n <= 5 && /\b(?:como estas|como te va|como andas|como amaneciste|que tal estas|todo bien|como has estado)\b/.test(w)) return respuestaSimple('saludo', { estado: true, texto: original });
      if (n <= 9 && RE.probarMic.test(w)) return respuestaSimple('probarMic', { texto: original });
      if (n <= 7 && RE.silenciar.test(w) && !/\bmuestra|busca|buscar\b/.test(w)) return respuestaSimple('silenciar', { texto: original });
      if (n <= 6 && RE.activarVoz.test(w) && /\bvoz|habla|hablar|oirte|escucharte\b/.test(w)) return respuestaSimple('activarVoz', { texto: original });
      if (RE.callar.test(w)) return respuestaSimple('callar', { texto: original });
      if (n <= 6 && RE.ocultar.test(w) && !/\bocultar mi\b/.test(w)) return respuestaSimple('ocultar', { texto: original });
      if (RE.quitarFiltros.test(w)) return respuestaSimple('quitarFiltros', { texto: original });
      if (n <= 5 && RE.repetir.test(w) && !/\bde nuevo (?:un|una)\b/.test(w)) return respuestaSimple('repetir', { texto: original });
      if (n <= 6 && RE.cerrarSesion.test(w)) return respuestaSimple('cerrarSesion', { texto: original });
      if (n <= 3 && RE.salir.test(w)) return respuestaSimple('salir', { texto: original });
      if (n <= 5 && RE.atras.test(w) && !/\b(?:inicio|principal|perfil|mis|mi|vacantes|empleos|servicios)\b/.test(w)) return respuestaSimple('atras', { texto: original });
      if (n <= 4 && RE.recargar.test(w)) return respuestaSimple('recargar', { texto: original });
      if (n <= 7 && RE.abajo.test(w)) return respuestaSimple('desplazar', { dir: 'abajo', texto: original });
      if (n <= 7 && RE.arriba.test(w) && !/\binicio\b(?! de la pagina)/.test(w) && !/\b(?:servicio|vacante|anuncio|curriculum|cv|documento|foto|archivo|empleo)\b/.test(w)) return respuestaSimple('desplazar', { dir: 'arriba', texto: original });
      if (n <= 8 && RE.queEsto.test(w) && !/\b(?:curriculum|cv|hoja de vida|perfil|localidad|telefono|foto|contrasena)\b/.test(w)) return respuestaSimple('queEsto', { texto: original });
      if (/\bayuda (?:con|para usar|en) (?:la|esta|el|este) (?:pagina|sitio|plataforma|aplicacion|app|sistema)\b/.test(w)) return respuestaSimple('orientar', { motivo: 'general', texto: original });
      if (n <= 6 && RE.ayuda.test(w)) return respuestaSimple('ayuda', { texto: original });
    }

    // ---------- Modo trabajador / empleador ----------
    var mm = !forzar && RE.cambiarModo.exec(w);
    if (mm && n <= 10 && /\b(?:modo|cambia|cambiar|cambiame|pon|ponme|pasa|pasame|quiero ser|quiero estar|soy|usar como|activa|entra)\b/.test(w) &&
        !/\b(?:busco|necesito|ocupo|contratar|publicar|vacante|servicio)\b/.test(w)) {
      var quiereEmpleador = /empleador|empleadores|patron|contratante|jefe/.test(mm[1]);
      return respuestaSimple('cambiarModo', { modo: quiereEmpleador ? 'employer' : 'worker', texto: original });
    }

    // ---------- Notificaciones ----------
    if (!forzar && n <= 8 && RE.notificaciones.test(w) && !/\b(?:busco|necesito|publicar)\b/.test(w)) return respuestaSimple('abrirNotificaciones', { texto: original });

    // ---------- Cuenta (para quien aún no entra) ----------
    if (!forzar && n <= 9 && RE.registro.test(w) && !/\b(?:busco|necesito)\b/.test(w)) return respuestaSimple('ir', { destino: 'registro', texto: original });
    if (!forzar && n <= 7 && RE.login.test(w) && !/\b(?:busco|necesito|publicar)\b/.test(w)) return respuestaSimple('ir', { destino: 'acceso', texto: original });

    // ---------- Resultados en pantalla ----------
    if (!forzar && ctx.hayResultados) {
      var ab = RE.abrirN.exec(w);
      if (ab) {
        var k = ordinalDe(ab[1]);
        if (k !== null) return respuestaSimple('abrirResultado', { n: k, postular: RE.postular.test(w), texto: original });
      }
      if (n <= 9 && RE.leer.test(w)) return respuestaSimple('leerResultados', { texto: original });
      if (n <= 7 && RE.contar.test(w)) return respuestaSimple('contarResultados', { texto: original });
    }
    if (!forzar && n <= 9 && RE.leer.test(w)) return respuestaSimple('leerResultados', { texto: original });
    if (!forzar && n <= 7 && RE.contar.test(w)) return respuestaSimple('contarResultados', { texto: original });

    // ---------- Otras páginas fijas ----------
    if (!forzar && n <= 8) {
      if (/\b(?:servicio al cliente|atencion al cliente|contacto|contactar a ustedes|soporte|hablar con alguien|hablar con una persona|quiero hablar con|reportar un problema|tengo un problema con la pagina|queja|quejas|sugerencia|sugerencias|whatsapp de ustedes)\b/.test(w)) return respuestaSimple('ir', { destino: 'contacto', texto: original });
      if (/\b(?:acerca de|quienes son|quienes sois|quienes hacen|quien hizo la pagina|sobre ustedes|nosotros|quienes somos)\b/.test(w)) return respuestaSimple('ir', { destino: 'acerca', texto: original });
      if (/\b(?:terminos|condiciones|aviso de privacidad|privacidad|politicas)\b/.test(w)) return respuestaSimple('ir', { destino: 'terminos', texto: original });
    }

    // ---------- Anuncios (publicidad) ----------
    if (!forzar && PUB_ANUNCIO.test(w)) return respuestaSimple('publicar', { tipo: 'anuncio', texto: original });

    // ---------- Publicar ----------
    if (!forzar) {
      var fuerteVac = PUB_VACANTE_FUERTE.test(w), fuerteServ = PUB_SERVICIO_FUERTE.test(w);
      var hayPublicar = VERBOS_PUBLICAR.test(w) || (VERBOS_PUBLICAR_DEBIL.test(w) && OBJETO_PUBLICAR.test(w));
      var comoPublicar = /\b(?:como|donde|en donde|por donde|que debo|que tengo que|que hay que)\b.*\b(?:publico|publicar|anuncio|anunciar|ofrezco|ofrecer|subo|subir|pongo|poner|creo|crear|doy de alta)\b/.test(w) &&
        !/\b(?:curriculum|cv|hoja de vida|foto|telefono|localidad|contrasena|perfil)\b/.test(w);
      if (hayPublicar || comoPublicar || fuerteVac || fuerteServ) {
        var tipoPub = null;
        if (fuerteVac && !fuerteServ) tipoPub = 'vacante';
        else if (fuerteServ) tipoPub = 'servicio';
        else if (PUB_VACANTE_DEBIL.test(w)) tipoPub = 'vacante';
        else if (PUB_SERVICIO.test(w)) tipoPub = 'servicio';
        if (/\bpublicar empleo\b/.test(w)) tipoPub = 'servicio';
        if (!tipoPub && /\b(?:trabajo|chamba)\b/.test(w) && /\b(?:un|una|el)\b.*\b(?:trabajo|chamba)\b/.test(w)) tipoPub = 'vacante';
        return respuestaSimple('publicar', { tipo: tipoPub, como: comoPublicar || /\bcomo\b/.test(w), porContratar: !!(fuerteVac && !hayPublicar), texto: original });
      }
    }

    // ---------- Pantallas propias ("mis vacantes", "mi perfil"...) ----------
    if (!forzar) {
      for (var i = 0; i < RUTAS_PROPIAS.length; i++) {
        var par = RUTAS_PROPIAS[i], id = par[0];
        if (par[1].test(w)) {
          var soloNav = n <= 8 || /\b(?:abre|abrir|ir|ve|vamos|llevame|muestrame|ver|entra|mi|mis)\b/.test(w);
          if (id === 'inicio') {
            // "inicio" suelto o con verbo de ir; evita "inicio de sesión"
            if (/\binicio de sesion\b/.test(w)) continue;
            if (n <= 6 && !VERBOS_BUSCAR.test(w.replace(/\b(?:ir|ve|vamos|llevame|muestrame|ver|abre|abrir)\b/g, ' '))) {
              var modoActual = ctx.modo === 'employer' ? 'employer' : 'worker';
              var empl = /\b(?:empleador|empleadores|patron|contratante)\b/.test(w), trab = /\b(?:trabajador|trabajadora|empleado)\b/.test(w);
              var modoIr = empl ? 'employer' : trab ? 'worker' : modoActual;
              return respuestaSimple('ir', { destino: modoIr === 'employer' ? 'inicio_empleador' : 'inicio_trabajador', texto: original });
            }
            continue;
          }
          if (soloNav && n <= 9) {
            // Si trae un oficio o filtros claros, es una búsqueda ("solicitudes de plomero" no existe; pero "perfil de plomero" tampoco): ir.
            return respuestaSimple('ir', { destino: id, texto: original });
          }
        }
      }
    }

    // ====================================================================
    //  BUSCAR (servicios o vacantes)
    // ====================================================================
    var pidePuesto = PIDE_EMPLEO.test(w);
    var tipo = forzar === 'vacantes' ? 'vacantes' : forzar === 'servicios' ? 'servicios' : null;
    if (!tipo) {
      var verEmpleosLiteral = /\b(?:ver empleos|ver los empleos|abre ver empleos|ir a ver empleos)\b/.test(w);
      if (verEmpleosLiteral) tipo = 'servicios';
      else if (pidePuesto) tipo = 'vacantes';
      else if (/\b(?:[bv]acantes?|ofertas? de (?:trabajo|empleo)|bolsa de trabajo|chambas?|trabajos disponibles|empleos disponibles)\b/.test(w)) tipo = 'vacantes';
      else if (/\b(?:servicios|profesionales|especialistas|expertos)\b/.test(w)) tipo = 'servicios';
    }

    // Datos del texto
    var rest = w, avisos = [];
    var hint = tipo === 'vacantes';
    var qr = extraerQuitar(rest); rest = qr.w;
    var d = extraerDinero(rest, hint); rest = d.w;
    var o = extraerOrden(rest); rest = o.w;
    var ml = extraerMiLocalidad(rest); rest = ml.w;
    var lo = extraerLocalidad(rest, idx); rest = lo.w;
    var ca = extraerCategoria(rest, idx); rest = ca.w;
    var fv = extraerFiltrosVacante(rest);
    var hayFiltroVacante = Object.keys(fv.filtros).length > 0;
    rest = fv.w;

    // ¿Es un problema ("se me tapó el drenaje") en lugar de un oficio?
    var contenido = elegirContenido(rest, idx, { crudo: crudo });
    var porProblema = null;
    if (contenido.origen !== 'diccionario' && contenido.origen !== 'multi') {
      for (var p = 0; p < PROBLEMAS.length; p++) {
        if (PROBLEMAS[p][0].test(w)) { porProblema = PROBLEMAS[p][1]; break; }
      }
    }
    if (porProblema && (contenido.origen === 'nada' || contenido.origen === 'libre')) {
      contenido = { q: porProblema, alternativas: [], origen: 'problema', resto: rest };
    }
    if (contenido.origen === 'nada') {
      for (var vo = 0; vo < VERBO_OFICIO.length; vo++) {
        if (VERBO_OFICIO[vo][0].test(w)) { contenido = { q: VERBO_OFICIO[vo][1], alternativas: [], origen: 'problema', resto: rest }; break; }
      }
    }

    var q = contenido.q;
    var genericoContratar = GENERICO_CONTRATAR.test(w);
    var hayVerboBuscar = VERBOS_BUSCAR.test(w) || forzar;
    var hayFiltros = d.min != null || d.max != null || o.orden || o.cerca || lo.loc || ml.mia || ca.cat || hayFiltroVacante || qr.quitar.length || qr.todo;
    var contrata = /\b(?:contrat\w*|emplear|emplee)\b/.test(w);

    if (!tipo) {
      if (hayFiltroVacante || (d.min != null || d.max != null) && /\b(?:sueldo|salario|pagan|paguen|pague|paga|ganar|gane|pago)\b/.test(w)) tipo = 'vacantes';
      else if (contrata && genericoContratar && !q) {
        return respuestaSimple('publicar', { tipo: 'vacante', texto: original, porContratar: true });
      }
      else if (!q && /\b(?:trabajo|trabajos|empleo|empleos|chamba|chambas|trabajito|chambita)\b/.test(w)) tipo = 'vacantes';
      else if (RE.orientar.test(w) && contenido.origen !== 'diccionario' && contenido.origen !== 'multi' && contenido.origen !== 'problema') {
        return respuestaSimple('orientar', { motivo: 'general', texto: original });
      }
      else if ((q && (hayVerboBuscar || contenido.origen !== 'libre')) || ca.cat || porProblema) tipo = 'servicios';
    }

    // Refinar la búsqueda que ya está en pantalla ("ahora en Xaltipan", "más baratos")
    var paginaBusqueda = ctx.pagina === 'ver_servicios' || ctx.pagina === 'buscar_vacantes' || (ctx.pagina === 'home' && ctx.params && (ctx.params.q || ctx.params.q_vacante || ctx.params.categoria_id || ctx.params.localidad_id || ctx.params.localidad_vacante_id));
    var tipoPagina = ctx.pagina === 'ver_servicios' ? 'servicios' : ctx.pagina === 'buscar_vacantes' ? 'vacantes' : null;
    var refinar = false;
    if (paginaBusqueda && !forzar) {
      var inicioRefina = /^(?:ahora|mejor|y |pero|tambien|ademas|que sea|solo|nada mas|mas |pon |ponle|agrega|agregale|quita|quitale|cambia|cambiale|filtra|filtrame|ordena|ordenalos|ordenalas|muestrame solo|dejame solo|los que|las que|el que|la que|con |sin |en |de |hasta |menos |entre |por )/.test(w);
      var nuevaBusqueda = /\b(?:busco|buscar|necesito|nececito|ocupo|quiero|quisiera|me urge|encuentra|encuentrame|dame|muestrame (?:un|una|unos|unas|los|las)|contratar|hay)\b/.test(w);
      if ((inicioRefina && !nuevaBusqueda) || (!nuevaBusqueda && !q && hayFiltros) || (!q && hayFiltros && n <= 8)) refinar = true;
      if (refinar && !tipo) tipo = tipoPagina || (ctx.params && (ctx.params.q_vacante || ctx.params.localidad_vacante_id) ? 'vacantes' : 'servicios');
      if (refinar && tipoPagina && tipo !== tipoPagina && !tipoForzadoPorTexto(w)) tipo = tipoPagina;
    }

    function tipoForzadoPorTexto(t) { return /\b(?:trabajo|empleo|chamba|vacante|vacantes|servicio|servicios|profesional)\b/.test(t); }

    // ¿Habla de la pantalla de listas sin más? ("ver vacantes", "servicios disponibles")
    if (!tipo && (hayVerboBuscar || hayFiltros) && !q) {
      // pidió buscar pero no dijo qué ni quién: preguntar
      return respuestaSimple('orientar', { motivo: 'buscar', texto: original });
    }

    if (tipo) {
      var esVac = tipo === 'vacantes';
      var params = {};
      if (q) params.q = q;
      // Si lo que dijo es justo el nombre de una categoría, se filtra por categoría (servicios).
      if (!esVac && q && !ca.cat) {
        var qn = sinAcentos(q);
        idx.cats.forEach(function (c) { if (!ca.cat && (c.clave === qn || c.palabras.indexOf(qn) >= 0)) { ca.cat = c; delete params.q; } });
      }
      if (ca.cat && !esVac) params.categoria_id = ca.cat.id;
      if (ca.cat && esVac && !q) params.q = ca.cat.nombre;
      if (lo.loc) params.localidad_id = lo.loc.id;
      var miLoc = ml.mia || (o.cerca && false);
      if (ml.mia && ctx.miLocalidadId) params.localidad_id = ctx.miLocalidadId;
      if (esVac) {
        if (d.min != null) params.salario_min = d.min;
        if (d.max != null) params.salario_max = d.max;
        Object.keys(fv.filtros).forEach(function (kf) { params[kf] = fv.filtros[kf]; });
      } else {
        if (d.min != null) params.precio_min = d.min;
        if (d.max != null) params.precio_max = d.max;
      }
      var orden = null;
      if (o.cerca) orden = 'cercania';
      else if (o.orden === 'recientes') orden = 'recientes';
      else if (o.orden === 'menor_precio') orden = esVac ? 'salario_asc' : 'precio_asc';
      else if (o.orden === 'mayor_precio') orden = esVac ? 'salario_desc' : 'precio_desc';
      if (orden) params.orden = orden;

      var sinLocalidadParaCerca = !!(o.cerca && !ctx.miLocalidadId);
      var quitarLista = qr.quitar.slice();
      salida = {
        intent: 'buscar', tipo: tipo, params: params, quitar: quitarLista, quitarTodo: qr.todo, refinar: refinar,
        contratar: !!(contrata && !esVac && q), alternativas: contenido.alternativas, origenQ: contenido.origen,
        etiquetas: { localidad: lo.loc ? lo.loc.nombre : (ml.mia ? (ctx.miLocalidad || 'tu localidad') : null), categoria: ca.cat ? ca.cat.nombre : null },
        cerca: !!o.cerca, sinLocalidadParaCerca: sinLocalidadParaCerca, miLocalidadSinDato: !!(ml.mia && !ctx.miLocalidadId),
        municipioSolo: !!(lo.municipio && !lo.loc), forzado: !!forzar, sinDetalle: !q && !hayFiltros, texto: original
      };
      return salida;
    }

    // ---------- Orientación para quien no sabe qué hacer ----------
    if (RE.orientar.test(w) || /\b(?:ayuda|ayudame|ayudenme)\b/.test(w)) return respuestaSimple('orientar', { motivo: 'general', texto: original });

    // ---------- Una sola palabra de oficio ("plomero") ----------
    if (q && (contenido.origen === 'diccionario' || contenido.origen === 'multi' || contenido.origen === 'problema')) {
      return { intent: 'buscar', tipo: 'servicios', params: { q: q }, quitar: [], refinar: false, contratar: false, alternativas: contenido.alternativas, origenQ: contenido.origen, etiquetas: { localidad: null, categoria: null }, texto: original };
    }

    return respuestaSimple('desconocido', { texto: original, sugerencia: q || null });
  }

  // ====================================================================
  //  11. APOYOS PARA QUIEN USA EL CEREBRO
  // ====================================================================

  function paginaDe(ruta) {
    var p = String(ruta || '').split('?')[0].split('#')[0].replace(/\/+$/, '') || '/';
    var mapa = {
      '/usuario': 'inicio_trabajador', '/usuario/empleador': 'inicio_empleador', '/usuario/verEmpleos': 'ver_servicios',
      '/usuario/buscar-talento': 'buscar_vacantes', '/usuario/publicarEmpleo': 'publicar_servicio',
      '/usuario/publicar-vacante': 'publicar_vacante', '/usuario/misEmpleos': 'mis_servicios', '/usuario/mis-vacantes': 'mis_vacantes',
      '/usuario/postulantes': 'postulantes', '/usuario/solicitudes': 'solicitudes', '/usuario/pronostico': 'pronostico',
      '/usuario/profile': 'perfil', '/': 'home', '/acceso': 'acceso', '/acerca-de': 'acerca', '/servicio-cliente': 'contacto', '/terminos': 'terminos', '/anunciar': 'anunciar'
    };
    if (mapa[p]) return mapa[p];
    if (/^\/usuario\/ver_servicio\/\d+/.test(p)) return 'detalle_servicio';
    if (/^\/usuario\/ver_vacante\/\d+/.test(p)) return 'detalle_vacante';
    if (/^\/servicio\/\d+/.test(p)) return 'detalle_servicio_publico';
    if (/^\/vacante\/\d+/.test(p)) return 'detalle_vacante_publico';
    if (/\/cv\//.test(p) || /\/cv$/.test(p)) return 'cv';
    return 'otra';
  }

  // Dirección a la que hay que ir para una búsqueda.
  function urlBusqueda(intent, opciones) {
    opciones = opciones || {};
    var logueado = !!opciones.logueado, tipo = intent.tipo, p = intent.params || {}, q = [], k;
    function add(nombre, valor) { if (valor !== undefined && valor !== null && valor !== '') q.push(encodeURIComponent(nombre) + '=' + encodeURIComponent(valor)); }
    if (logueado) {
      var base = tipo === 'vacantes' ? '/usuario/buscar-talento' : '/usuario/verEmpleos';
      var orden = tipo === 'vacantes'
        ? ['q', 'localidad_id', 'salario_min', 'salario_max', 'experiencia', 'contrato', 'tipo_pago', 'orden']
        : ['q', 'categoria_id', 'localidad_id', 'precio_min', 'precio_max', 'orden'];
      orden.forEach(function (nombre) { add(nombre, p[nombre]); });
      return base + (q.length ? '?' + q.join('&') : '');
    }
    // Visitantes: la página principal solo sabe de texto, categoría y localidad.
    if (tipo === 'vacantes') { add('q_vacante', p.q); add('localidad_vacante_id', p.localidad_id); return '/' + (q.length ? '?' + q.join('&') : '') + '#listVacantes'; }
    add('q', p.q); add('categoria_id', p.categoria_id); add('localidad_id', p.localidad_id);
    return '/' + (q.length ? '?' + q.join('&') : '') + '#listProducts';
  }

  // Une la búsqueda nueva con los filtros que ya estaban puestos en la pantalla.
  function fusionarParams(actuales, intent) {
    var salida = {};
    var tipo = intent.tipo;
    var valida = tipo === 'vacantes'
      ? ['q', 'localidad_id', 'salario_min', 'salario_max', 'experiencia', 'contrato', 'tipo_pago', 'orden']
      : ['q', 'categoria_id', 'localidad_id', 'precio_min', 'precio_max', 'orden'];
    if (intent.refinar && actuales) {
      valida.forEach(function (k) { if (actuales[k] !== undefined && actuales[k] !== '') salida[k] = actuales[k]; });
      // En la página principal las vacantes usan otros nombres
      if (actuales.q_vacante && tipo === 'vacantes') salida.q = actuales.q_vacante;
      if (actuales.localidad_vacante_id && tipo === 'vacantes') salida.localidad_id = actuales.localidad_vacante_id;
    }
    if (intent.quitarTodo) salida = {};
    (intent.quitar || []).forEach(function (k) { delete salida[k]; });
    var nuevos = intent.params || {};
    Object.keys(nuevos).forEach(function (k) {
      salida[k] = nuevos[k];
    });
    // Si pidió precio mínimo/máximo nuevo, el viejo del mismo lado se respeta; si dijo un rango, ya vienen los dos.
    return salida;
  }

  function dinero(n) { return '$' + Number(n).toLocaleString('es-MX'); }

  // "plomero · en Xaltipan · hasta $500 · más baratos" (para mostrar y para hablar).
  function describir(intent, ctx) {
    ctx = ctx || {};
    var p = intent.params || {}, partes = [], esVac = intent.tipo === 'vacantes';
    if (p.q) partes.push(p.q);
    if (intent.etiquetas && intent.etiquetas.categoria && !p.q) partes.push('categoría ' + intent.etiquetas.categoria);
    else if (intent.etiquetas && intent.etiquetas.categoria) partes.push('categoría ' + intent.etiquetas.categoria);
    var locNombre = intent.etiquetas && intent.etiquetas.localidad;
    if (!locNombre && p.localidad_id && ctx.localidades) {
      var l = ctx.localidades.filter(function (x) { return Number(x.id) === Number(p.localidad_id); })[0];
      if (l) locNombre = l.nombre;
    }
    if (locNombre) partes.push('en ' + locNombre);
    var min = esVac ? p.salario_min : p.precio_min, max = esVac ? p.salario_max : p.precio_max;
    if (min != null && max != null) partes.push('de ' + dinero(min) + ' a ' + dinero(max));
    else if (max != null) partes.push('hasta ' + dinero(max));
    else if (min != null) partes.push('desde ' + dinero(min));
    if (p.experiencia) partes.push(p.experiencia.toLowerCase());
    if (p.contrato) partes.push('contrato ' + p.contrato.toLowerCase());
    if (p.tipo_pago) partes.push(p.tipo_pago.toLowerCase());
    var ord = { recientes: 'más recientes', cercania: 'los más cercanos', precio_asc: 'los más baratos', precio_desc: 'los más caros', salario_asc: 'menor sueldo', salario_desc: 'mejor pagados' }[p.orden];
    if (ord) partes.push(ord);
    // "electricista en Cuacuila, los más baratos" (la localidad va pegada al oficio, sin coma)
    var texto = '';
    partes.forEach(function (parte, i) {
      if (i === 0) texto = parte;
      else texto += (/^en /.test(parte) && i === 1 ? ' ' : ', ') + parte;
    });
    return texto;
  }

  return {
    interpretar: interpretar,
    preparar: preparar,
    sinAcentos: sinAcentos,
    paginaDe: paginaDe,
    urlBusqueda: urlBusqueda,
    fusionarParams: fusionarParams,
    describir: describir,
    DESTINOS: DESTINOS,
    PAGINAS: PAGINAS,
    EJEMPLOS: EJEMPLOS,
    _interno: { palabrasANumeros: palabrasANumeros, extraerDinero: extraerDinero, extraerLocalidad: extraerLocalidad, indice: indice, raiz: raiz, sing: sing, lev: lev, fon: fon, elegirContenido: elegirContenido }
  };
});
