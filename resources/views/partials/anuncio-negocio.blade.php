{{--
    Banner "¿Tienes un negocio?" — compartido por TODO el sitio.

    Antes este bloque de HTML estaba copiado y pegado en 16 páginas
    distintas (con pequeñas diferencias entre ellas, como el nombre de
    la marca desactualizado en el dashboard). Ahora vive aquí, en un
    solo lugar: para cambiar el texto, el botón o las imágenes, se edita
    nada más este archivo y se refleja en todo el sitio.

    El estilo vive en public/css/anuncio-negocio.css (hay que enlazarlo
    una vez en el <head> de cada página que use este partial).

    Uso:
        <div class="my-4">
            @include('partials.anuncio-negocio')
        </div>
--}}
<div class="banner-negocio">

    <div class="banner-texto">
        <h2>¿TIENES UN NEGOCIO?</h2>
        <p>Promociona tu negocio y llega a más clientes en tu municipio</p>
    </div>

    <div class="banner-icono">
        <img src="{{ asset('img/anuncios/tienda.png') }}" alt="Tienda">
    </div>

    <div class="banner-destaca">
        <h2>DESTACA TU NEGOCIO</h2>
        <h1>EN ¡SINTECZATE!</h1>
    </div>

    <div class="banner-boton">
        <a href="/anunciar" class="btn-anunciar">
            ¡ANÚNCIATE AQUÍ!
        </a>
        <small>Más información →</small>
    </div>

    <div class="banner-persona">
        <img src="{{ asset('img/anuncios/persona.png') }}" alt="Anunciante">
    </div>

</div>
