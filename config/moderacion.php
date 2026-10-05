<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Palabras y frases prohibidas
    |--------------------------------------------------------------------------
    |
    | Lista local de palabras/frases que se bloquean de inmediato, sin llamar
    | a la API de OpenAI. Esto cubre groserías, insultos y palabras sexuales
    | explícitas que a veces el modelo de IA no marca (por ejemplo "sexy" o
    | "wey" no son lo bastante graves para OpenAI, pero aquí sí se bloquean).
    |
    | No hace falta escribir cada variante con números o letras separadas
    | (p3ndejo, p.o.r.n.o, etc). ModeracionService ya normaliza el texto
    | (quita acentos, cambia 0->o, 3->e, 1->i, 4->a, 5->s, 7->t, 8->b, v->u,
    | y junta letras sueltas separadas por espacios, guiones o puntos) antes
    | de comparar, así que con la palabra "normal" (pendejo, porno, etc.)
    | ya se detectan casi todas sus variantes disfrazadas.
    |
    | Los plurales regulares (gordo/gordos, puta/putas) se detectan solos,
    | no hace falta agregarlos aparte.
    |
    */

    'palabras_prohibidas' => [

        // =========================
        // INSULTOS
        // =========================
        'pendejo', 'pendeja', 'cabron', 'cabrona', 'wey', 'buey',
        'estupido', 'estupida', 'idiota', 'imbecil', 'menso', 'mensa',
        'tarado', 'tarada', 'baboso', 'babosa', 'naco', 'naca',
        'gonorrea', 'marica', 'maricon', 'puñal', 'joto',
        'verga', 'pinche', 'culero', 'culera', 'culo', 'mamon', 'mamona',
        'cerdo', 'cerda', 'perra', 'zorra', 'chingar', 'chingada',
        'chingadera', 'chingon', 'chingona', 'hijo de puta', 'hija de puta',
        'malparido', 'malparida', 'gilipollas', 'pendejada',
        'puto', 'puta', 'mierda',

        // =========================
        // SEXUAL EXPLÍCITO
        // =========================
        'porno', 'pornografia', 'pornografico', 'xxx', 'sexo', 'sexy', 'sexi',
        'pene', 'vagina', 'semen', 'tetas', 'nalgas', 'nudes','polla',
        'desnudo', 'desnuda', 'prostituta', 'prostitucion', 'escort',
        'hentai', 'picha', 'coger', 'follar', 'orgasmo', 'masturbar',
        'masturbacion', 'eyacular', 'penetracion', 'lubricante sexual',
        'anal', 'vaginal', 'sexo oral', 'sexo anal', 'erotico', 'erotica',
        'gigolo', 'ninfomana', 'garganta profunda', 'masaje erotico',
        'placer sexual', 'servicio sexual', 'servicios sexuales',
        'servicio anal', 'servicio oral', 'sexo casual', 'encuentro sexual',
        'sexual', 'contenido sexual', 'contenido para adultos',
        'contenido +18', 'contenido 18+', 'contenido erotico',
        'contenido intimo', 'video sexual', 'videos sexuales',
        'video intimo', 'videos intimos', 'foto intima', 'fotos intimas',
        'trabajo sexual', 'chica webcam', 'modelo webcam', 'camgirl',
        'onlyfans', 'fansly', 'creadora de contenido adulto',
        'creador de contenido adulto', 'sexting', 'chat erotico',
        'videollamada erotica', 'stripper', 'table dance', 'bailarina exotica',
        'nude',

        // =========================
        // EXPLOTACIÓN / ABUSO
        // =========================
        'pornografiainfantil', 'pedofilia', 'pedofilo', 'pedofila',
        'abuso sexual', 'trata de personas', 'explotacion sexual',
        'prostitucion infantil', 'contenido infantil sexual',

        // =========================
        // APARIENCIA FÍSICA (despectivo)
        // =========================
        'gordo', 'gorda', 'flaco', 'flaca', 'enano', 'enana',
        'chaparro', 'chaparra', 'feo', 'fea', 'calvo', 'calva',

        // =========================
        // AMENAZAS
        // =========================
        'te voy a matar', 'voy a matarte', 'te voy a golpear',
        'te voy a secuestrar', 'sicario', 'amenaza de muerte',
        'te voy a violar', 'te voy a lastimar',

        // =========================
        // DROGAS
        // =========================
        'cocaina', 'marihuana', 'metanfetamina', 'fentanilo', 'heroina',
        'cristal', 'crack', 'extasis', 'mota', 'weed', 'droga', 'narcotico',
        'narcomenudeo',

        // =========================
        // ARMAS / VIOLENCIA
        // =========================
        'pistola', 'rifle', 'fusil', 'ametralladora', 'explosivo',
        'municiones', 'granada', 'bomba', 'arma de fuego',

        // =========================
        // ESTAFAS / FRAUDE / CONTENIDO ILEGAL
        // =========================
        'hackear', 'hackeo', 'tarjeta clonada', 'dinero falso',
        'documento falso', 'cuenta robada', 'estafa', 'fraude',
        'piramide', 'dinero facil', 'prestamo sin garantia', 'phishing',
        'robo de identidad', 'lavado de dinero', 'clonar tarjetas',

        // =========================
        // SPAM / PUBLICIDAD NO RELACIONADA
        // =========================
        'gana dinero rapido', 'dinero facil trabajando', 'inversion garantizada',
        'haz clic aqui', 'compra seguidores', 'gana criptomonedas gratis',

        // =========================
        // DATOS DE CONTACTO SOSPECHOSOS
        // =========================
        'manda tu numero por privado', 'contactame por privado solamente',
        'solo por whatsapp sin llamadas',

    ],

];
