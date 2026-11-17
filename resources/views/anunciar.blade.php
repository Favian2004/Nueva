<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Anúnciate aquí · ¡SINTECZATE!</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700;9..144,800&family=Nunito+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="icon" href="{{ asset('img/template/home.png') }}" type="image/png" sizes="192x192">

  <style>
    :root {
      --vino: #6b1021;
      --vino-oscuro: #4a0b17;
      --ambar: #ff7a18;
      --ambar-claro: #ffb347;
      --crema: #fdf6ee;
      --tinta: #1a1a2e;
      --borde: #f0e9df;
      --ok: #16a34a;
      --error: #b91c1c;
    }

    html { scroll-behavior: smooth; }
    body { background: var(--crema); font-family: 'Nunito Sans', sans-serif; color: var(--tinta); }
    h1, h2, h3, .font-display { font-family: 'Fraunces', serif; }

    /* ===== HERO ===== */
    .anunciar-hero {
      position: relative;
      overflow: hidden;
      background: linear-gradient(135deg, var(--vino-oscuro) 0%, var(--vino) 45%, #b12d25 100%);
      color: #fff;
      padding: 64px 0 96px;
      text-align: center;
    }
    .anunciar-hero::before, .anunciar-hero::after {
      content: ''; position: absolute; border-radius: 50%; pointer-events: none;
    }
    .anunciar-hero::before { width: 380px; height: 380px; top: -160px; right: -90px; background: radial-gradient(circle, rgba(255,179,71,.32), transparent 70%); }
    .anunciar-hero::after { width: 320px; height: 320px; bottom: -170px; left: -80px; background: radial-gradient(circle, rgba(255,122,24,.28), transparent 70%); }
    .anunciar-hero .container { position: relative; z-index: 1; }
    .hero-etiqueta {
      display: inline-flex; align-items: center; gap: 8px;
      background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.25);
      padding: 6px 16px; border-radius: 30px; font-size: 13px; font-weight: 700; letter-spacing: .3px;
      margin-bottom: 18px; color: #ffe2b8;
    }
    .anunciar-hero h1 { font-weight: 800; font-size: clamp(30px, 5vw, 46px); line-height: 1.12; margin-bottom: 14px; letter-spacing: -.5px; }
    .anunciar-hero h1 span { color: #ffcf33; }
    .anunciar-hero p { font-size: 17px; opacity: .93; max-width: 600px; margin: 0 auto 26px; line-height: 1.55; }
    .hero-botones { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
    .btn-hero {
      display: inline-flex; align-items: center; gap: 8px; border-radius: 12px; padding: 13px 26px;
      font-weight: 800; font-size: 15px; text-decoration: none; transition: .18s; border: 2px solid transparent;
    }
    .btn-hero.primario { background: linear-gradient(45deg, var(--ambar), var(--ambar-claro)); color: #fff; box-shadow: 0 8px 22px rgba(255,122,24,.4); }
    .btn-hero.primario:hover { transform: translateY(-2px); color: #fff; }
    .btn-hero.secundario { background: transparent; color: #fff; border-color: rgba(255,255,255,.45); }
    .btn-hero.secundario:hover { background: rgba(255,255,255,.12); color: #fff; }

    .costo-diario {
      background: #fff; border-radius: 18px; max-width: 720px; margin: -44px auto 54px;
      box-shadow: 0 12px 34px rgba(0,0,0,.12); padding: 20px 28px; text-align: center; position: relative; z-index: 2;
      display: flex; align-items: center; justify-content: center; gap: 14px;
    }
    .costo-diario i { color: var(--ambar-claro); font-size: 30px; }
    .costo-diario p { margin: 0; font-size: 15.5px; text-align: left; }
    .costo-diario strong { color: var(--ambar); }

    .section-title { text-align: center; max-width: 640px; margin: 0 auto 30px; }
    .section-title .sobre { display: inline-block; font-size: 12px; font-weight: 800; letter-spacing: 1.4px; text-transform: uppercase; color: var(--ambar); margin-bottom: 6px; }
    .section-title h2 { font-size: clamp(25px, 4vw, 32px); font-weight: 800; color: var(--tinta); margin-bottom: 8px; letter-spacing: -.3px; }
    .section-title p { font-size: 15px; color: #6b6b6b; line-height: 1.6; margin: 0; }

    /* ===== CÓMO FUNCIONA ===== */
    .pasos-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; max-width: 940px; margin: 0 auto 60px; }
    @media (max-width: 768px) { .pasos-grid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 480px) { .pasos-grid { grid-template-columns: 1fr; } }
    .paso-card { background: #fff; border: 1px solid var(--borde); border-radius: 16px; padding: 22px 16px; text-align: center; position: relative; }
    .paso-card .num {
      width: 38px; height: 38px; border-radius: 50%; margin: 0 auto 12px; display: flex; align-items: center; justify-content: center;
      background: linear-gradient(135deg, var(--vino), #b12d25); color: #fff; font-weight: 800; font-family: 'Fraunces', serif;
    }
    .paso-card h6 { font-weight: 800; font-size: 15px; margin-bottom: 4px; }
    .paso-card p { font-size: 13px; color: #777; margin: 0; line-height: 1.5; }

    /* ===== POR QUÉ ANUNCIARTE ===== */
    .porque-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; max-width: 940px; margin: 0 auto 60px; }
    @media (max-width: 768px) { .porque-grid { grid-template-columns: 1fr; } }
    .porque-card { background: #fff; border-radius: 18px; padding: 30px 24px; border: 1px solid var(--borde); text-align: center; transition: transform .2s, box-shadow .2s; }
    .porque-card:hover { transform: translateY(-4px); box-shadow: 0 14px 30px rgba(107,16,33,.09); }
    .porque-card i {
      font-size: 26px; color: var(--ambar); background: linear-gradient(135deg, #fff2e5, #ffe4cc);
      width: 58px; height: 58px; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;
    }
    .porque-card h6 { font-family: 'Fraunces', serif; font-weight: 700; font-size: 19px; margin-bottom: 10px; line-height: 1.3; }
    .porque-card p { font-size: 14.5px; color: #6b6b6b; line-height: 1.65; margin: 0; }
    .porque-intro { max-width: 700px; margin: 0 auto 34px; text-align: center; font-size: 18px; line-height: 1.7; font-weight: 500; }
    .porque-intro strong { color: var(--vino); font-weight: 700; }

    /* ===== COMPARACIÓN ===== */
    .comparacion-wrap { max-width: 900px; margin: 0 auto 60px; }
    .comparacion-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
    @media (max-width: 768px) { .comparacion-grid { grid-template-columns: 1fr 1fr; } }
    .comparacion-card { background: #fff; border: 1px solid var(--borde); border-radius: 14px; padding: 18px 14px; text-align: center; }
    .comparacion-card.ganador { background: linear-gradient(135deg, var(--vino), #8a1a2c); border-color: var(--vino); color: #fff; transform: scale(1.03); box-shadow: 0 12px 26px rgba(107,16,33,.28); }
    .comparacion-card .alt-nombre { font-size: 12.5px; font-weight: 700; margin-bottom: 10px; min-height: 32px; }
    .comparacion-card .alt-costo { font-size: 20px; font-weight: 800; }
    .comparacion-card.ganador .alt-costo { color: #ffcf33; }
    .comparacion-card .alt-nota { font-size: 10.5px; opacity: .7; margin-top: 4px; }

    /* ===== PLANES ===== */
    #planesGrid { scroll-margin-top: 20px; }
    .planes-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; max-width: 900px; margin: 0 auto 18px; align-items: stretch; }
    @media (max-width: 800px) { .planes-grid { grid-template-columns: 1fr; max-width: 420px; } }
    .plan-card {
      background: #fff; border-radius: 20px; padding: 26px 20px 22px; border: 2px solid var(--borde);
      display: flex; flex-direction: column; position: relative; cursor: pointer; transition: transform .2s, box-shadow .2s, border-color .2s;
    }
    .plan-card:hover { transform: translateY(-5px); box-shadow: 0 16px 34px rgba(107,16,33,.12); border-color: #ffd0a0; }
    .plan-card.destacado { border-color: var(--ambar); box-shadow: 0 12px 28px rgba(255,122,24,.16); }
    .plan-card.seleccionado { border-color: var(--vino); box-shadow: 0 0 0 4px rgba(107,16,33,.14), 0 16px 34px rgba(107,16,33,.14); }
    .plan-card .badge-ahorro {
      position: absolute; top: -13px; left: 50%; transform: translateX(-50%);
      background: linear-gradient(45deg, var(--ambar), var(--ambar-claro)); color: #fff;
      font-size: 11px; font-weight: 800; padding: 5px 15px; border-radius: 20px; white-space: nowrap;
    }
    .plan-card h5 { font-weight: 800; margin-bottom: 2px; font-size: 18px; }
    .plan-card .precio { font-size: 34px; font-weight: 800; color: var(--vino); margin-top: 6px; line-height: 1.1; }
    .plan-card .precio small { font-size: 13px; font-weight: 600; color: #999; }
    .plan-card .por-dia { font-size: 12px; color: #999; margin-bottom: 16px; }
    .plan-card ul { list-style: none; padding: 0; margin: 0 0 20px; flex-grow: 1; }
    .plan-card ul li { font-size: 13.5px; font-weight: 600; color: #444; display: flex; gap: 8px; align-items: flex-start; margin-bottom: 10px; line-height: 1.4; }
    .plan-card ul li i { color: var(--ambar); margin-top: 2px; flex-shrink: 0; }
    .plan-card ul li.no-incluido { color: #b5b5b5; }
    .plan-card ul li.no-incluido i { color: #d8d8d8; }
    .btn-elegir-plan {
      margin-top: auto; width: 100%; background: #fff; border: 2px solid var(--ambar); color: var(--ambar);
      font-weight: 800; font-size: 14px; padding: 11px; border-radius: 12px; cursor: pointer; transition: .15s;
    }
    .btn-elegir-plan:hover { background: #fff7ef; }
    .btn-elegir-plan.elegido { background: var(--vino); border-color: var(--vino); color: #fff; }
    .planes-ayuda { text-align: center; font-size: 14px; color: var(--vino); font-weight: 700; margin-bottom: 36px; }
    .planes-ayuda i { color: var(--ambar); }

    /* ===== FORMULARIO (oculto hasta elegir plan) ===== */
    #seccionFormulario { display: none; scroll-margin-top: 14px; }
    #seccionFormulario.visible { display: block; animation: aparecer .45s ease-out; }
    @keyframes aparecer { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: none; } }

    .form-anunciar { max-width: 760px; margin: 0 auto 60px; }
    .bloque {
      background: #fff; border-radius: 20px; border: 1px solid var(--borde);
      box-shadow: 0 4px 22px rgba(0,0,0,.05); padding: 26px 28px; margin-bottom: 18px;
    }
    @media (max-width: 576px) { .bloque { padding: 22px 18px; } }
    .bloque-titulo { display: flex; align-items: center; gap: 12px; margin-bottom: 4px; }
    .bloque-titulo .ico {
      width: 38px; height: 38px; border-radius: 12px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;
      background: linear-gradient(135deg, #fff2e5, #ffe4cc); color: var(--ambar); font-size: 18px;
    }
    .bloque-titulo h5 { font-weight: 800; margin: 0; font-size: 18px; }
    .bloque-sub { font-size: 13px; color: #888; margin: 4px 0 20px 50px; }
    @media (max-width: 576px) { .bloque-sub { margin-left: 0; } }

    .plan-elegido-box { background: linear-gradient(135deg, #fff7ef, #fff0df); border: 1.5px solid #ffd8ae; border-radius: 16px; padding: 14px 18px; margin-bottom: 18px; }
    .plan-elegido-resumen { display: flex; justify-content: space-between; align-items: center; gap: 10px; font-size: 14.5px; }
    .plan-elegido-resumen strong { color: var(--vino); }
    .plan-elegido-cambiar { font-size: 13px; font-weight: 800; color: var(--ambar); text-decoration: none; white-space: nowrap; }
    .plan-elegido-cambiar:hover { text-decoration: underline; }

    .form-anunciar .form-label { color: #333 !important; font-weight: 700 !important; font-size: 14px !important; display: block !important; margin-bottom: 5px !important; }
    .form-anunciar .form-control { border-radius: 12px; border: 1.5px solid #e6dfd5; padding: 11px 14px; font-size: 14.5px; }
    .form-anunciar .form-control:focus { border-color: var(--ambar); box-shadow: 0 0 0 3px rgba(255,122,24,.16); }
    .form-anunciar .form-control.bloqueado { border-color: var(--error); background: #fff7f7; }
    .form-anunciar .campo-ayuda { font-size: 12px; color: #888; display: block; margin-top: 4px; line-height: 1.5; }
    .contador { float: right; font-size: 11.5px; color: #aaa; font-weight: 600; }
    .mod-msg { display: none; margin-top: 6px; font-size: 12.5px; font-weight: 700; color: var(--error); line-height: 1.4; }
    .mod-msg.visible { display: block; }
    .mod-msg.ok { color: var(--ok); }
    .mod-msg.revisando { color: #888; font-weight: 600; }

    /* ===== IMAGEN: subir o crear con IA ===== */
    .tabs-imagen { display: flex; gap: 6px; background: #f6f1ea; padding: 5px; border-radius: 14px; margin-bottom: 14px; }
    .tabs-imagen button {
      flex: 1; border: 0; background: transparent; padding: 10px 8px; border-radius: 10px; font-weight: 800; font-size: 13.5px; color: #777; cursor: pointer; transition: .15s;
    }
    .tabs-imagen button.activo { background: #fff; color: var(--vino); box-shadow: 0 2px 8px rgba(0,0,0,.08); }
    .tabs-imagen .nuevo { background: linear-gradient(45deg, var(--ambar), var(--ambar-claro)); color: #fff; font-size: 9.5px; padding: 2px 7px; border-radius: 10px; margin-left: 5px; vertical-align: middle; }
    .panel-imagen { display: none; }
    .panel-imagen.activo { display: block; }

    .estilos-ia { display: flex; flex-wrap: wrap; gap: 8px; margin: 10px 0 14px; }
    .chip-estilo {
      border: 1.5px solid #e6dfd5; background: #fff; color: #555; font-size: 12.5px; font-weight: 700;
      padding: 7px 14px; border-radius: 20px; cursor: pointer; transition: .15s;
    }
    .chip-estilo:hover { border-color: var(--ambar); }
    .chip-estilo.activo { background: var(--vino); border-color: var(--vino); color: #fff; }
    .zoomable { cursor: zoom-in; }
    .ia-creando { display: none; margin-top: 16px; text-align: center; }
    .ia-creando.visible { display: block; }
    .ia-creando .lienzo {
      width: 100%; max-width: 300px; aspect-ratio: 1/1; margin: 0 auto; border-radius: 16px; position: relative; overflow: hidden;
      background: linear-gradient(110deg, #f3ebdf 30%, #fff7ec 50%, #f3ebdf 70%); background-size: 200% 100%; animation: brillo 1.4s linear infinite;
      display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; color: var(--vino);
    }
    @keyframes brillo { to { background-position: -200% 0; } }
    .ia-creando .lienzo .chispa { font-size: 44px; animation: latido 1.2s ease-in-out infinite; }
    @keyframes latido { 0%,100% { transform: scale(1); opacity: .8; } 50% { transform: scale(1.18); opacity: 1; } }
    .ia-creando .titulo { font-weight: 800; font-size: 15px; margin-top: 12px; color: var(--vino); }
    .ia-creando .detalle { font-size: 12.5px; color: #777; margin-top: 2px; }
    .progreso { display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 700; color: #6b6b6b; }
    .progreso .barra { flex: 1; max-width: 220px; height: 8px; background: #eee3d3; border-radius: 6px; overflow: hidden; }
    .progreso .barra span { display: block; height: 100%; width: 0; background: linear-gradient(90deg, var(--ambar), var(--ambar-claro)); transition: width .15s; }
    .progreso .girando { width: 14px; height: 14px; border: 2px solid #e0d5c3; border-top-color: var(--ambar); border-radius: 50%; animation: gira .8s linear infinite; flex-shrink: 0; }
    @keyframes gira { to { transform: rotate(360deg); } }
    .imagen-elegida .mini-elegida { width: 44px; height: 44px; border-radius: 8px; object-fit: cover; box-shadow: 0 1px 5px rgba(0,0,0,.2); }
    .visor-overlay { display: none; position: fixed; inset: 0; background: rgba(15,10,10,.88); z-index: 1070; align-items: center; justify-content: center; padding: 20px; cursor: zoom-out; }
    .visor-overlay.activo { display: flex; }
    .visor-overlay img { max-width: 94vw; max-height: 90vh; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,.5); background: #fff; }
    .visor-overlay .cerrar { position: absolute; top: 14px; right: 20px; color: #fff; font-size: 34px; line-height: 1; }
    .referencia-box { border: 2px dashed #e0d5c3; background: #fbf8f3; border-radius: 14px; padding: 14px; margin-bottom: 14px; }
    .referencia-box .fila { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
    .referencia-box .miniatura { width: 72px; height: 72px; border-radius: 12px; object-fit: cover; display: none; box-shadow: 0 2px 8px rgba(0,0,0,.15); }
    .btn-referencia { background: #fff; border: 1.5px solid var(--ambar); color: var(--ambar); font-weight: 800; font-size: 13px; padding: 8px 16px; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
    .btn-referencia:hover { background: #fff7ef; }
    .btn-quitar-ref { display: none; background: none; border: 0; color: var(--error); font-size: 12px; font-weight: 800; text-decoration: underline; cursor: pointer; }
    .btn-ia {
      width: 100%; border: 0; border-radius: 12px; padding: 12px; font-weight: 800; font-size: 14.5px; color: #fff; cursor: pointer;
      background: linear-gradient(45deg, #6b1021, #b12d25); display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-ia:disabled { opacity: .6; cursor: not-allowed; }
    .ia-resultado { display: none; margin-top: 16px; text-align: center; }
    .ia-resultado.visible { display: block; }
    .ia-resultado img { width: 100%; max-width: 300px; aspect-ratio: 1/1; object-fit: cover; border-radius: 16px; box-shadow: 0 8px 24px rgba(0,0,0,.18); }
    .ia-acciones { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-top: 12px; }
    .ia-acciones button { border-radius: 10px; font-weight: 800; font-size: 13px; padding: 9px 18px; cursor: pointer; }
    .btn-usar-ia { background: var(--ok); color: #fff; border: 0; }
    .btn-otra-ia { background: #fff; color: var(--vino); border: 1.5px solid var(--vino); }
    .ia-aviso { background: #f9f7f4; border: 1px dashed #e0d5c3; border-radius: 12px; padding: 10px 14px; font-size: 12px; color: #777; line-height: 1.5; margin-top: 12px; }
    .ia-estado { font-size: 13px; text-align: center; margin-top: 10px; color: #777; font-weight: 600; }
    .ia-estado.error { color: var(--error); font-weight: 700; }
    .imagen-elegida { display: none; align-items: center; gap: 10px; background: #ecfdf3; border: 1px solid #b7ebcc; color: #166534; padding: 9px 14px; border-radius: 12px; font-size: 13px; font-weight: 700; margin-top: 12px; }
    .imagen-elegida.visible { display: flex; }
    .imagen-elegida button { margin-left: auto; background: none; border: 0; color: var(--error); font-weight: 800; font-size: 12px; text-decoration: underline; cursor: pointer; }

    /* ===== UBICACIÓN ===== */
    .ubicacion-box { background: #f9f7f4; border: 1px solid #eee0cf; border-radius: 14px; padding: 16px; }
    .ubicacion-coords { font-size: 12.5px; color: #666; margin-top: 8px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .ubicacion-coords.marcada { color: var(--ok); font-weight: 700; }
    .btn-mi-ubicacion {
      background: #fff; border: 1.5px solid var(--ambar); color: var(--ambar); font-weight: 700; font-size: 12px;
      padding: 6px 14px; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-mi-ubicacion:hover { background: #fff7ef; }

    /* ===== VISTA PREVIA ===== */
    .preview-wrap { background: #f4f0eb; border-radius: 16px; padding: 22px; }
    .preview-tabs { display: flex; gap: 6px; margin-bottom: 16px; justify-content: center; }
    .preview-tabs button { border: none; background: #fff; color: #888; font-size: 12px; font-weight: 700; padding: 6px 14px; border-radius: 20px; cursor: default; }
    .preview-tabs button.activo { background: var(--vino); color: #fff; }
    .preview-columnas { display: flex; gap: 24px; align-items: center; justify-content: center; flex-wrap: wrap; }
    .preview-mini { text-align: center; }
    .preview-mini-label { font-size: 11px; color: #999; margin-bottom: 6px; font-weight: 700; }
    #previewAdBox {
      width: 190px; height: 171px; border-radius: 12px; overflow: hidden; background: #e5e0d8; box-shadow: 0 4px 15px rgba(0,0,0,.12);
      position: relative; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: transform .15s;
    }
    #previewAdBox:hover { transform: scale(1.03); }
    #previewAdBox img { display: none; width: 100%; height: 100%; object-fit: cover; }
    #previewAdBox .click-hint {
      position: absolute; inset: 0; background: rgba(0,0,0,0); display: flex; align-items: center; justify-content: center;
      opacity: 0; transition: .15s; color: #fff; font-size: 11px; font-weight: 700;
    }
    #previewAdBox:hover .click-hint { background: rgba(0,0,0,.35); opacity: 1; }
    #previewEslogan {
      display: none; background: #fff; border-radius: 8px; padding: 6px 10px; margin-top: 8px; text-align: center;
      font-size: 11.5px; font-weight: 700; color: #333; box-shadow: 0 2px 8px rgba(0,0,0,.08); max-width: 190px; overflow-wrap: anywhere;
    }
    .flecha-preview { font-size: 22px; color: #c9c2b4; }
    @media (max-width: 576px) { .flecha-preview { transform: rotate(90deg); } }
    .preview-placeholder-txt { color: #999; font-size: 12px; text-align: center; padding: 0 14px; }

    .lightbox-overlay { display: none; position: fixed; inset: 0; background: rgba(15,10,10,.82); z-index: 1050; align-items: center; justify-content: center; padding: 20px; }
    .lightbox-overlay.activo { display: flex; }
    .lightbox-card { background: #fff; border-radius: 18px; max-width: 460px; width: 100%; overflow: hidden; text-align: center; }
    .lightbox-img-box { width: 100%; aspect-ratio: 1 / 1; background: #e5e0d8; display: flex; align-items: center; justify-content: center; overflow: hidden; }
    .lightbox-img-box img { display: none; width: 100%; height: 100%; object-fit: cover; }
    .lightbox-body { padding: 18px 20px 22px; }
    .lightbox-body h6 { font-weight: 800; color: var(--tinta); margin-bottom: 4px; font-size: 15px; overflow-wrap: anywhere; }
    .lightbox-body p { font-size: 12.5px; color: #777; margin-bottom: 14px; overflow-wrap: anywhere; }
    .lightbox-btn-link { display: inline-flex; align-items: center; gap: 6px; background: var(--vino); color: #fff; border: none; padding: 9px 18px; border-radius: 10px; font-size: 12.5px; font-weight: 700; text-decoration: none; }
    .lightbox-btn-ubicacion { display: inline-flex; align-items: center; gap: 6px; background: #fff; color: var(--vino); border: 1.5px solid var(--vino); padding: 9px 18px; border-radius: 10px; font-size: 12.5px; font-weight: 700; text-decoration: none; margin-left: 8px; }
    .lightbox-cerrar { position: absolute; top: 14px; right: 18px; color: #fff; font-size: 26px; cursor: pointer; line-height: 1; }

    /* ===== PAGO ===== */
    .pago-box { background: var(--tinta); color: #fff; border-radius: 18px; padding: 22px 24px; margin-bottom: 14px; }
    .pago-box h6 { font-weight: 800; margin-bottom: 8px; }
    .pago-box p { font-size: 13px; opacity: .9; margin: 0; }
    .pago-box .chips { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; }
    .pago-box .chips span { background: rgba(255,255,255,.12); padding: 4px 12px; border-radius: 20px; font-size: 12px; }
    .btn-enviar-anuncio {
      background: linear-gradient(45deg, var(--ambar), var(--ambar-claro)); color: #fff; border: none; padding: 15px; border-radius: 14px;
      font-weight: 800; width: 100%; font-size: 16px; box-shadow: 0 10px 24px rgba(255,122,24,.32); transition: .15s;
    }
    .btn-enviar-anuncio:hover:not(:disabled) { transform: translateY(-2px); }
    .btn-enviar-anuncio:disabled { opacity: 0.6; cursor: not-allowed; }

    .back-button {
      display: inline-flex; align-items: center; gap: 8px; background: #fff; border: 1.5px solid #ffd8ae; color: var(--ambar);
      font-weight: 600; font-size: 0.9rem; padding: 8px 18px; border-radius: 30px; text-decoration: none; transition: 0.2s;
      box-shadow: 0 2px 6px rgba(0,0,0,0.04); cursor: pointer;
    }
    .back-button:hover { background: var(--ambar); border-color: var(--ambar); color: #fff; transform: translateX(-3px); box-shadow: 0 4px 12px rgba(255,122,24,0.25); }

    #toastFormularioAnuncio, #toastAjaxAnuncio { position: fixed; top: 70px; right: 20px; max-width: 380px; z-index: 1060; box-shadow: 0 4px 12px rgba(0,0,0,0.15); animation: toastSlideInAnuncio 0.35s ease-out; }
    @keyframes toastSlideInAnuncio { from { transform: translateX(120%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @media (max-width: 480px) { #toastFormularioAnuncio, #toastAjaxAnuncio { left: 16px; right: 16px; max-width: none; } }
  </style>
</head>

<body>

  <!-- HEADER -->
  <div class="header__top">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="header__left">
        <ul class="d-flex list-unstyled mb-0">
          <li class="me-3"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
          <li class="me-3"><a href="#"><i class="fab fa-instagram"></i></a></li>
          <li><a href="#"><i class="fab fa-youtube"></i></a></li>
        </ul>
      </div>
      <div class="header__right d-flex align-items-center">
        <div><i class="bi bi-telephone"></i> Línea directa: <strong>2331014306</strong></div>
      </div>
    </div>
  </div>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-sm navbar-dark bg-custom">
    <div class="container-fluid">
      <a class="navbar-brand" href="/">
        <img src="{{ asset('img/template/logo.png') }}" width="75">
        <span class="brand-text">¡SINTECZATE!</span>
      </a>
      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="menu">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="/"><i class="bi bi-house-door me-1"></i> Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="/servicio-cliente"><i class="fas fa-headset me-1"></i> Servicio al cliente</a></li>
          <li class="nav-item"><a class="nav-link active" href="/anunciar"><i class="bi bi-megaphone me-1"></i> Anúnciate aquí</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- BOTÓN REGRESAR -->
  <div class="container mt-3">
    <a href="{{ auth()->check() ? '/usuario' : '/' }}" class="back-button" id="btnRegresar">
      <i class="bi bi-arrow-left"></i> Regresar
    </a>
  </div>

  <!-- HERO -->
  <div class="anunciar-hero mt-3">
    <div class="container">
      <span class="hero-etiqueta"><i class="bi bi-megaphone-fill"></i> Publicidad local en Zacapoaxtla</span>
      <h1>Que todo el pueblo conozca <span>tu negocio</span></h1>
      <p>Anúnciate en ¡SINTECZATE! y llega a la gente de tu municipio que ya está buscando servicios y negocios como el tuyo.</p>
      <div class="hero-botones">
        <a href="#planesGrid" class="btn-hero primario"><i class="bi bi-rocket-takeoff-fill"></i> Elegir mi plan</a>
        <a href="#comoFunciona" class="btn-hero secundario">¿Cómo funciona?</a>
      </div>
    </div>
  </div>

  <div class="container">

    <!-- COSTO POR DÍA -->
    <div class="costo-diario">
      <i class="bi bi-lightbulb-fill"></i>
      <p>Por menos de <strong>$2 pesos al día</strong>, tu negocio aparece en la página que ve toda la gente de tu municipio.</p>
    </div>

    <!-- MENSAJE DE ÉXITO O ERROR (flotante, se oculta solo) -->
    @if (session('exito'))
      <div id="toastFormularioAnuncio" class="alert alert-success" role="alert">
        <i class="bi bi-check-circle-fill"></i> {{ session('exito') }}
      </div>
    @endif
    @if ($errors->any())
      <div id="toastFormularioAnuncio" class="alert alert-danger" role="alert">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
    @if (session('exito') || $errors->any())
      <script>
        setTimeout(function () {
          const toast = document.getElementById('toastFormularioAnuncio');
          if (toast) toast.style.display = 'none';
        }, 6000);
      </script>
    @endif

    <!-- Toast para mensajes que llegan por AJAX (no recarga la página, así nunca se pierde la imagen ya seleccionada) -->
    <div id="toastAjaxAnuncio" class="alert" role="alert" style="display:none;"></div>

    <!-- CÓMO FUNCIONA -->
    <div class="section-title" id="comoFunciona">
      <span class="sobre">Es muy fácil</span>
      <h2>Tu anuncio en 4 pasos</h2>
    </div>
    <div class="pasos-grid">
      <div class="paso-card"><div class="num">1</div><h6>Elige tu plan</h6><p>Desde $29. Sin contratos ni letras chiquitas.</p></div>
      <div class="paso-card"><div class="num">2</div><h6>Llena tus datos</h6><p>Nombre, contacto y la imagen de tu negocio.</p></div>
      <div class="paso-card"><div class="num">3</div><h6>Paga seguro</h6><p>Con tarjeta, a través de Mercado Pago.</p></div>
      <div class="paso-card"><div class="num">4</div><h6>Te publicamos</h6><p>Revisamos tu anuncio y lo activamos, usualmente en menos de 24 horas.</p></div>
    </div>

    <!-- POR QUÉ ANUNCIARTE -->
    <div class="section-title">
      <span class="sobre">Resultados reales</span>
      <h2>¿Por qué anunciarte aquí?</h2>
    </div>
    <p class="porque-intro">
      ¡SINTECZATE! es la <strong>bolsa de trabajo y directorio de servicios de Zacapoaxtla</strong>: la gente entra a buscar empleo, a quién contratar para un trabajo, o qué negocios hay cerca. Tu anuncio queda justo frente a esas personas.
    </p>
    <div class="porque-grid">
      <div class="porque-card">
        <i class="bi bi-people-fill"></i>
        <h6>Gente que ya está buscando</h6>
        <p>No es un volante que se tira. Tu anuncio lo ve alguien que ya entró al sitio a buscar trabajo o servicios en tu municipio.</p>
      </div>
      <div class="porque-card">
        <i class="bi bi-graph-up-arrow"></i>
        <h6>Se paga solo, con un cliente</h6>
        <p>Con lo que te compre UN cliente nuevo que llegue por ver tu anuncio, ya recuperaste lo que pagaste ese mes. Todo lo que venda después de eso, es ganancia extra.</p>
      </div>
      <div class="porque-card">
        <i class="bi bi-clock-history"></i>
        <h6>Anuncia tu negocio, sin contratar a nadie</h6>
        <p>Es como tener a una persona parada anunciando tu negocio todo el día, todos los días — pero sin sueldo, sin que le des indicaciones, y sin que se canse. Tu anuncio hace ese trabajo solo.</p>
      </div>
    </div>

    <!-- COMPARACIÓN -->
    <div class="comparacion-wrap">
      <div class="section-title">
        <h2>Comparado con otras formas de anunciarte</h2>
        <p>Un mes de anuncio aquí cuesta menos que una sola tanda de volantes, y se ve todos los días.</p>
      </div>
      <div class="comparacion-grid">
        <div class="comparacion-card">
          <div class="alt-nombre">Volantes impresos<br>(500 pzas)</div>
          <div class="alt-costo">$400–800</div>
          <div class="alt-nota">Se acaban, se tiran</div>
        </div>
        <div class="comparacion-card">
          <div class="alt-nombre">Radio local<br>(1 mes)</div>
          <div class="alt-costo">$1,500+</div>
          <div class="alt-nota">Solo mientras suena</div>
        </div>
        <div class="comparacion-card">
          <div class="alt-nombre">Espectacular<br>/ lona</div>
          <div class="alt-costo">$2,000+</div>
          <div class="alt-nota">Un solo lugar fijo</div>
        </div>
        <div class="comparacion-card ganador">
          <div class="alt-nombre">¡SINTECZATE!<br>(1 mes)</div>
          <div class="alt-costo">$49</div>
          <div class="alt-nota">Visible cada día, en todo el sitio</div>
        </div>
      </div>
    </div>

    <!-- PLANES -->
    <div class="section-title">
      <span class="sobre">Paso 1</span>
      <h2>Elige tu plan</h2>
      <p>Los tres incluyen tu imagen en la columna de negocios destacados. Mensual y Anual además muestran un eslogan corto debajo de tu foto.</p>
    </div>
    <div class="planes-grid" id="planesGrid">
      <div class="plan-card" data-plan="basico" onclick="selectPlan('basico')">
        <h5>Básico</h5>
        <div class="precio">$29 <small>/15 días</small></div>
        <div class="por-dia">≈ $1.93 al día</div>
        <ul>
          <li><i class="bi bi-check-circle-fill"></i> Tu imagen en negocios destacados</li>
          <li><i class="bi bi-check-circle-fill"></i> Se ve en todas las páginas del sitio</li>
          <li class="no-incluido"><i class="bi bi-x-circle"></i> Sin eslogan debajo de la imagen</li>
        </ul>
        <button type="button" class="btn-elegir-plan" data-plan="basico">Elegir este plan</button>
      </div>
      <div class="plan-card destacado" data-plan="mensual" onclick="selectPlan('mensual')">
        <span class="badge-ahorro">EL MÁS ELEGIDO</span>
        <h5>Mensual</h5>
        <div class="precio">$49 <small>/mes</small></div>
        <div class="por-dia">≈ $1.63 al día</div>
        <ul>
          <li><i class="bi bi-check-circle-fill"></i> Tu imagen en negocios destacados</li>
          <li><i class="bi bi-check-circle-fill"></i> Se ve en todas las páginas del sitio</li>
          <li><i class="bi bi-check-circle-fill"></i> Eslogan debajo de tu imagen</li>
          <li><i class="bi bi-check-circle-fill"></i> Link a tu página o red social</li>
          <li><i class="bi bi-check-circle-fill"></i> Ubicación con mapa (cómo llegar)</li>
        </ul>
        <button type="button" class="btn-elegir-plan" data-plan="mensual">Elegir este plan</button>
      </div>
      <div class="plan-card" data-plan="anual" onclick="selectPlan('anual')">
        <h5>Anual</h5>
        <div class="precio">$490 <small>/año</small></div>
        <div class="por-dia">≈ $1.34 al día · 2 meses gratis</div>
        <ul>
          <li><i class="bi bi-check-circle-fill"></i> Tu imagen en negocios destacados</li>
          <li><i class="bi bi-check-circle-fill"></i> Se ve en todas las páginas del sitio</li>
          <li><i class="bi bi-check-circle-fill"></i> Eslogan debajo de tu imagen</li>
          <li><i class="bi bi-check-circle-fill"></i> Link a tu página o red social</li>
          <li><i class="bi bi-check-circle-fill"></i> Ubicación con mapa (cómo llegar)</li>
          <li><i class="bi bi-check-circle-fill"></i> Todo lo del plan Mensual, un año completo</li>
        </ul>
        <button type="button" class="btn-elegir-plan" data-plan="anual">Elegir este plan</button>
      </div>
    </div>
    <p class="planes-ayuda" id="planesAyuda"><i class="bi bi-hand-index-thumb-fill"></i> Toca un plan para llenar los datos de tu anuncio</p>

    <!-- FORMULARIO (aparece al elegir un plan) -->
    <div id="seccionFormulario">
      <div class="form-anunciar">
        <div class="section-title" style="margin-bottom:22px;">
          <span class="sobre">Paso 2</span>
          <h2>Datos de tu anuncio</h2>
          <p>Todo lo que escribes y la imagen se revisan con nuestro moderador antes de publicarse.</p>
        </div>

        <form action="{{ route('anunciar.store') }}" method="POST" enctype="multipart/form-data" id="formAnunciar" novalidate>
          @csrf

          <div class="plan-elegido-box" id="planElegidoBox">
            <div class="plan-elegido-resumen">
              <div><i class="bi bi-patch-check-fill" style="color:var(--ambar);"></i> Tu plan: <strong id="planElegidoNombre">Mensual</strong> · <span id="planElegidoPrecio">$49 / mes</span></div>
              <a href="#planesGrid" class="plan-elegido-cambiar">Cambiar plan</a>
            </div>
            <input type="hidden" name="plan" id="planSeleccionadoInput" value="{{ old('plan') }}">
          </div>

          <!-- NEGOCIO -->
          <div class="bloque">
            <div class="bloque-titulo"><span class="ico"><i class="bi bi-shop"></i></span><h5>Tu negocio</h5></div>
            <p class="bloque-sub">Así te presentamos y así podemos ubicarte.</p>

            <div class="mb-3">
              <label class="form-label">Nombre del negocio <span class="text-danger">*</span><span class="contador" data-de="nombre_negocio">0/80</span></label>
              <input type="text" name="nombre_negocio" class="form-control" value="{{ old('nombre_negocio') }}" maxlength="80" placeholder="Ej. Taquería Doña Lupe" required data-moderar>
              <div class="mod-msg"></div>
              <span class="campo-ayuda">Así aparecerá tu negocio en el sitio.</span>
            </div>

            <div class="mb-3">
              <label class="form-label">Nombre de quien atiende / encargado <span class="text-danger">*</span></label>
              <input type="text" name="nombre_encargado" class="form-control" value="{{ old('nombre_encargado') }}" maxlength="60" placeholder="Ej. María López" required data-moderar>
              <div class="mod-msg"></div>
              <span class="campo-ayuda">Solo letras. Para saber con quién coordinar si necesitamos contactarte sobre tu anuncio.</span>
            </div>

            <div class="mb-3">
              <label class="form-label">Describe tu negocio <span class="text-danger">*</span><span class="contador" data-de="descripcion">0/600</span></label>
              <textarea name="descripcion" class="form-control" rows="3" maxlength="600" placeholder="¿Qué ofreces? ¿Qué te hace diferente?" required data-moderar>{{ old('descripcion') }}</textarea>
              <div class="mod-msg"></div>
              <span class="campo-ayuda">Este texto es solo para nuestro equipo; no se muestra en el anuncio público.</span>
            </div>

            <div class="mb-1">
              <label class="form-label">Dirección <span class="text-danger">*</span></label>
              <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}" maxlength="150" placeholder="Ej. Calle Morelos #12, Col. Centro" required data-moderar>
              <div class="mod-msg"></div>
            </div>
          </div>

          <!-- CONTACTO -->
          <div class="bloque">
            <div class="bloque-titulo"><span class="ico"><i class="bi bi-telephone-fill"></i></span><h5>Contacto</h5></div>
            <p class="bloque-sub">Solo para uso interno, no se publica en tu anuncio.</p>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Teléfono <span class="text-danger">*</span></label>
                <input type="tel" inputmode="numeric" name="telefono" class="form-control" value="{{ old('telefono') }}" maxlength="10" placeholder="10 dígitos" required>
                <div class="mod-msg"></div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">WhatsApp (opcional)</label>
                <input type="tel" inputmode="numeric" name="whatsapp" class="form-control" value="{{ old('whatsapp') }}" maxlength="10" placeholder="Si es diferente al teléfono">
                <div class="mod-msg"></div>
              </div>
            </div>
            <div class="mb-1">
              <label class="form-label">Correo (opcional)</label>
              <input type="email" name="email" class="form-control" value="{{ old('email') }}" maxlength="100" placeholder="Para avisarte cuando se publique">
              <span class="campo-ayuda">Te escribimos aquí en cuanto tu anuncio quede activo.</span>
            </div>
          </div>

          <!-- TU ANUNCIO -->
          <div class="bloque">
            <div class="bloque-titulo"><span class="ico"><i class="bi bi-image-fill"></i></span><h5>Tu anuncio</h5></div>
            <p class="bloque-sub">Lo que verá la gente en el sitio.</p>

            <div class="mb-3" id="campoEslogan">
              <label class="form-label">Eslogan (aparece debajo de tu imagen) <span class="text-danger">*</span><span class="contador" data-de="eslogan">0/80</span></label>
              <input type="text" name="eslogan" class="form-control" value="{{ old('eslogan') }}" placeholder="Ej. Los mejores tacos de la región" maxlength="80" data-moderar>
              <div class="mod-msg"></div>
              <span class="campo-ayuda">Una frase corta que resuma tu negocio. Se ve reflejada abajo, en la vista previa.</span>
            </div>

            <div class="mb-3" id="campoLinkExterno">
              <label class="form-label">Link a tu página o red social (opcional)</label>
              <input type="url" name="link_externo" class="form-control" value="{{ old('link_externo') }}" maxlength="255" placeholder="https://facebook.com/tu-negocio">
              <div class="mod-msg"></div>
              <span class="campo-ayuda">Si lo agregas, tu anuncio tendrá un botón que lleva directo ahí.</span>
            </div>

            <!-- UBICACIÓN (solo Mensual y Anual) -->
            <div class="mb-4" id="campoUbicacion">
              <label class="form-label">Ubicación de tu negocio (opcional)</label>
              <span class="campo-ayuda" style="margin-bottom:8px;">Así la gente puede llegar directo con un botón de "Cómo llegar". Si no la agregas, tu anuncio simplemente no tendrá ese botón.</span>
              <div class="ubicacion-box">
                <ol style="font-size:12.5px; color:#555; padding-left:18px; margin-bottom:12px; line-height:1.8;">
                  <li>Dale clic al botón de abajo — se abre Google Maps en otra pestaña.</li>
                  <li>Busca tu negocio (si ya aparece ahí) o tu dirección, en el buscador de Google Maps.</li>
                  <li>Con el pin correcto seleccionado, dale clic a <strong>"Compartir"</strong> y luego <strong>"Copiar enlace"</strong>.</li>
                  <li>Regresa aquí y pega el enlace en el campo de abajo.</li>
                </ol>
                <a href="https://www.google.com/maps" target="_blank" rel="noopener" class="btn-mi-ubicacion" style="text-decoration:none; margin-bottom:12px;">
                  <i class="bi bi-google"></i> Buscar mi negocio en Google Maps
                </a>
                <input type="url" name="link_ubicacion" id="inputLinkUbicacion" class="form-control" value="{{ old('link_ubicacion') }}" maxlength="500" placeholder="Pega aquí el enlace que copiaste de Google Maps">
                <div class="ubicacion-coords" id="ubicacionCoordsTexto"><i class="bi bi-info-circle"></i> Aún no has pegado ningún enlace.</div>
                <div class="mod-msg"></div>
              </div>
            </div>

            <!-- IMAGEN: subir o crear con IA -->
            <label class="form-label" id="labelImagen">Imagen de tu anuncio <span class="text-danger">*</span></label>
            <div class="tabs-imagen">
              <button type="button" class="activo" data-panel="subir"><i class="bi bi-upload"></i> Subir mi imagen</button>
              <button type="button" data-panel="ia"><i class="bi bi-stars"></i> Crear con IA <span class="nuevo">NUEVO</span></button>
            </div>

            <div class="panel-imagen activo" id="panel-subir">
              <input type="file" name="imagen_negocio" id="campoImagen" class="form-control" accept="image/*">
              <span class="campo-ayuda"><i class="bi bi-aspect-ratio"></i> Tamaño: entre 400x360 px y 1200x1200 px, máximo 4 MB. Si no cumple, recórtala o comprímela con cualquier editor de tu compu o celular.</span>
              <div id="avisoTamano" class="mt-2" style="display:none; background:#fee2e2; color:#b91c1c; border-radius:8px; padding:8px 12px; font-size:12.5px;">
                <i class="bi bi-exclamation-triangle-fill"></i> <span id="avisoTamanoTexto"></span>
              </div>
              <div class="mod-msg" id="modImagen"></div>
            </div>

            <div class="panel-imagen" id="panel-ia">
              <div class="referencia-box">
                <div class="fila">
                  <img id="miniaturaReferencia" class="miniatura zoomable" alt="Tu foto de referencia" title="Toca para verla en grande">
                  <div>
                    <label class="btn-referencia"><i class="bi bi-camera-fill"></i> <span id="textoBtnReferencia">Subir una foto de referencia (opcional)</span>
                      <input type="file" id="inputReferencia" accept="image/jpeg,image/png,image/webp" style="display:none;">
                    </label>
                    <button type="button" class="btn-quitar-ref" id="btnQuitarReferencia">Quitar foto</button>
                    <span class="campo-ayuda">Sube una foto de tu producto, platillo o local y la IA la convierte en un anuncio. JPG, PNG o WebP, máximo 4 MB.</span>
                  </div>
                </div>
                <div class="mod-msg" id="modReferencia"></div>
              </div>

              <label class="form-label" for="descripcionIa">Cuéntale a la IA qué quieres mostrar</label>
              <textarea id="descripcionIa" class="form-control" rows="2" maxlength="300" placeholder="Ej. Taquería familiar con tacos al pastor, salsas y cocina a la leña"></textarea>
              <div class="mod-msg" id="modIa"></div>
              <div class="estilos-ia" id="estilosIa">
                <button type="button" class="chip-estilo activo" data-estilo="fotografia">📷 Fotografía</button>
                <button type="button" class="chip-estilo" data-estilo="ilustracion">🎨 Ilustración</button>
                <button type="button" class="chip-estilo" data-estilo="minimalista">⚪ Minimalista</button>
                <button type="button" class="chip-estilo" data-estilo="artesanal">🪅 Artesanal</button>
              </div>
              <button type="button" class="btn-ia" id="btnGenerarIa"><i class="bi bi-stars"></i> Crear mi imagen con IA</button>
              <div class="ia-estado" id="iaEstado"></div>

              <div class="ia-creando" id="iaCreando" aria-live="polite">
                <div class="lienzo"><i class="bi bi-stars chispa"></i></div>
                <div class="titulo">Creando tu imagen…</div>
                <div class="detalle">Normalmente tarda entre 20 y 60 segundos. No cierres la página. <span id="iaSegundos"></span></div>
              </div>

              <div class="ia-resultado" id="iaResultado">
                <img id="iaImagen" class="zoomable" alt="Imagen creada con IA" title="Toca para verla en grande">
                <div class="ia-acciones">
                  <button type="button" class="btn-usar-ia" id="btnUsarIa"><i class="bi bi-check-lg"></i> Usar esta imagen</button>
                  <button type="button" class="btn-otra-ia" id="btnOtraIa"><i class="bi bi-arrow-repeat"></i> Crear otra</button>
                </div>
              </div>
              <div class="ia-aviso">
                <i class="bi bi-info-circle"></i> La IA crea la imagen sin letras (tu nombre y eslogan se muestran aparte). Tienes <strong>3 intentos al día</strong>. Tu foto de referencia y la imagen creada pasan por el moderador.
              </div>
            </div>

            <div class="imagen-elegida" id="imagenElegida"><img class="mini-elegida zoomable" id="miniElegida" alt="Tu imagen" title="Toca para verla en grande"><span id="imagenElegidaTexto"></span><button type="button" id="btnQuitarImagen">Quitar</button></div>
            <input type="hidden" name="imagen_ia" id="inputImagenIa" value="">
          </div>

          <!-- VISTA PREVIA -->
          <div class="bloque">
            <div class="bloque-titulo"><span class="ico"><i class="bi bi-eye-fill"></i></span><h5>Así se vería tu anuncio</h5></div>
            <p class="bloque-sub">Haz clic en la imagen para probar cómo se ve al abrirla.</p>
            <div class="preview-wrap">
              <div class="preview-tabs"><button type="button" class="activo" id="tabPlanActivo">Plan: Mensual</button></div>
              <div class="preview-columnas">
                <div class="preview-mini">
                  <div class="preview-mini-label">EN LA COLUMNA DE ANUNCIOS</div>
                  <div id="previewAdBox">
                    <img id="previewImg" alt="Vista previa">
                    <span id="previewPlaceholder" class="preview-placeholder-txt">
                      <i class="bi bi-image" style="font-size:26px; display:block; margin-bottom:6px;"></i>
                      Sube o crea una imagen para ver la vista previa
                    </span>
                    <div class="click-hint"><i class="bi bi-arrows-fullscreen me-1"></i> Ver en grande</div>
                  </div>
                  <div id="previewEslogan"></div>
                </div>
                <i class="bi bi-arrow-right flecha-preview"></i>
                <div class="preview-mini">
                  <div class="preview-mini-label">AL DARLE CLIC</div>
                  <div style="width:130px; height:130px; border-radius:12px; background:#fff; border:2px dashed #d8d0c2; display:flex; align-items:center; justify-content:center; font-size:11px; color:#aaa; text-align:center; padding:8px;">
                    Se abre en grande, con eslogan y botón a tu link (según tu plan)
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- PAGO -->
          <div class="pago-box">
            <h6><i class="bi bi-credit-card"></i> Pago seguro con Mercado Pago</h6>
            <p>Al enviar, te llevamos directo a Mercado Pago para completar tu pago con tarjeta. En cuanto lo confirme (al instante), tu solicitud queda lista para su revisión final — usualmente en menos de 24 horas.</p>
            <div class="chips">
              @foreach (['Tarjeta de débito','Tarjeta de crédito'] as $formaPago)
                <span>{{ $formaPago }}</span>
              @endforeach
            </div>
          </div>

          <button type="submit" class="btn-enviar-anuncio" id="btnPagar">
            <i class="bi bi-credit-card-fill"></i> Pagar con Mercado Pago
          </button>
        </form>
      </div>
    </div>

  </div>

  <!-- VISOR: abre cualquier imagen en grande -->
  <div class="visor-overlay" id="visorImagen"><span class="cerrar">&times;</span><img id="visorImg" alt="Imagen en grande"></div>

  <!-- LIGHTBOX: cómo se ve al abrir en grande -->
  <div class="lightbox-overlay" id="lightboxOverlay">
    <span class="lightbox-cerrar" id="lightboxCerrar">&times;</span>
    <div class="lightbox-card">
      <div class="lightbox-img-box"><img id="lightboxImg" alt="Anuncio en grande"></div>
      <div class="lightbox-body">
        <h6 id="lightboxNombre">Tu negocio</h6>
        <p id="lightboxEslogan">Tu eslogan aparecería aquí</p>
        <a href="#" id="lightboxBoton" class="lightbox-btn-link" onclick="return false;"><i class="bi bi-box-arrow-up-right"></i> Visitar página</a>
        <a href="#" id="lightboxBotonUbicacion" class="lightbox-btn-ubicacion" style="display:none;" target="_blank" rel="noopener"><i class="bi bi-geo-alt-fill"></i> Cómo llegar</a>
      </div>
    </div>
  </div>

  <footer class="footer-pro mt-5">
    <div class="container py-5">
      <div class="row g-4">
        <div class="col-md-4 text-center text-md-start">
          <h4 class="fw-bold text-white">¡SINTECZATE!</h4>
          <p class="text-light">Conectamos personas que ofrecen y buscan servicios de forma rápida y segura.</p>
        </div>
        <div class="col-md-4 text-center">
          <h5 class="text-white mb-3">Navegación</h5>
          <ul class="list-unstyled">
            <li><a href="/">Inicio</a></li>
            <li><a href="/terminos">Términos</a></li>
            <li><a href="/acerca-de">Acerca de</a></li>
            <li><a href="/acceso">Acceso</a></li>
            <li><a href="/servicio-cliente">Servicio al cliente</a></li>
          </ul>
        </div>
        <div class="col-md-4 text-center text-md-end">
          <h5 class="text-white mb-3">Contacto</h5>
          <p><i class="bi bi-telephone"></i> 2331442834</p>
          <p><i class="bi bi-geo-alt"></i> Zacapoaxtla, Puebla</p>
          <p><i class="bi bi-envelope"></i> sinteczate.oficial@gmail.com</p>
        </div>
      </div>
      <hr class="border-light mt-4">
      <div class="text-center text-light small">© 2026 ¡SINTECZATE! | Todos los derechos reservados</div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const URL_ENVIO = '{{ route('anunciar.store') }}';
    const form = document.getElementById('formAnunciar');
    const seccionFormulario = document.getElementById('seccionFormulario');
    const campoEslogan = document.getElementById('campoEslogan');
    const campoLinkExterno = document.getElementById('campoLinkExterno');
    const campoUbicacion = document.getElementById('campoUbicacion');
    const campoImagen = document.getElementById('campoImagen');
    const avisoTamano = document.getElementById('avisoTamano');
    const avisoTamanoTexto = document.getElementById('avisoTamanoTexto');
    const previewImg = document.getElementById('previewImg');
    const previewPlaceholder = document.getElementById('previewPlaceholder');
    const previewEslogan = document.getElementById('previewEslogan');
    const previewAdBox = document.getElementById('previewAdBox');
    const tabPlanActivo = document.getElementById('tabPlanActivo');
    const btnPagar = document.getElementById('btnPagar');
    const nombreNegocioInput = form.querySelector('[name="nombre_negocio"]');
    const linkExternoInput = form.querySelector('[name="link_externo"]');
    const inputLinkUbicacion = document.getElementById('inputLinkUbicacion');
    const ubicacionCoordsTexto = document.getElementById('ubicacionCoordsTexto');
    const inputImagenIa = document.getElementById('inputImagenIa');
    const csrf = () => form.querySelector('input[name="_token"]').value;

    const ANCHO_MIN = 400, ALTO_MIN = 360, ANCHO_MAX = 1200, ALTO_MAX = 1200;
    const PESO_MAX_BYTES = 4 * 1024 * 1024;
    let imagenValida = true;      // tamaño/peso correctos (solo imagen subida)
    let imagenAprobada = true;    // aprobada por el moderador (solo imagen subida)
    let imagenCargada = false;    // hay alguna imagen lista (subida o de IA)
    let planActual = null;

    const nombresPlan = { basico: 'Básico', mensual: 'Mensual', anual: 'Anual' };
    const preciosPlan = { basico: '$29 · 15 días', mensual: '$49 / mes', anual: '$490 / año' };

    // ===== Plan: el formulario aparece hasta elegir uno =====
    function selectPlan(plan) {
      const primeraVez = planActual === null;
      planActual = plan;

      document.getElementById('planSeleccionadoInput').value = plan;
      document.getElementById('planElegidoNombre').textContent = nombresPlan[plan];
      document.getElementById('planElegidoPrecio').textContent = preciosPlan[plan];
      tabPlanActivo.textContent = 'Plan: ' + nombresPlan[plan];

      document.querySelectorAll('.plan-card').forEach(card => card.classList.toggle('seleccionado', card.dataset.plan === plan));
      document.querySelectorAll('.btn-elegir-plan').forEach(btn => {
        const esEste = btn.dataset.plan === plan;
        btn.textContent = esEste ? '✓ Plan elegido' : 'Elegir este plan';
        btn.classList.toggle('elegido', esEste);
      });

      const conExtras = plan !== 'basico'; // Básico: sin eslogan, link ni mapa
      campoEslogan.style.display = conExtras ? '' : 'none';
      form.querySelector('[name="eslogan"]').required = conExtras;
      campoLinkExterno.style.display = conExtras ? '' : 'none';
      campoUbicacion.style.display = conExtras ? '' : 'none';
      actualizarPreviewEslogan();

      document.getElementById('planesAyuda').style.display = 'none';
      seccionFormulario.classList.add('visible');
      setTimeout(() => seccionFormulario.scrollIntoView({ behavior: 'smooth', block: 'start' }), primeraVez ? 60 : 0);
    }

    // ===== Contadores y filtros de escritura =====
    function actualizarContadores() {
      document.querySelectorAll('.contador').forEach(c => {
        const campo = form.querySelector(`[name="${c.dataset.de}"]`);
        if (campo) c.textContent = campo.value.length + '/' + campo.maxLength;
      });
    }
    form.addEventListener('input', e => {
      const n = e.target.name;
      if (n === 'telefono' || n === 'whatsapp') e.target.value = e.target.value.replace(/\D/g, '').slice(0, 10);
      if (n === 'nombre_encargado') {
        e.target.value = e.target.value.replace(/[^\p{L}\p{M}\s.'’-]/gu, '').replace(/(\p{L})\1{2,}/giu, '$1$1');
      }
      if (e.target.classList.contains('bloqueado')) limpiarAviso(e.target);
      actualizarContadores();
    });

    // ===== Moderador en vivo: texto (al salir del campo) e imagen (al elegirla) =====
    function avisoDe(campo) { return campo.parentElement.querySelector('.mod-msg'); }
    function mostrarAviso(campo, texto, tipo) {
      const caja = avisoDe(campo); if (!caja) return;
      caja.textContent = texto;
      caja.className = 'mod-msg visible' + (tipo ? ' ' + tipo : '');
      campo.classList.toggle('bloqueado', tipo === undefined);
    }
    function limpiarAviso(campo) {
      const caja = avisoDe(campo); if (caja) { caja.className = 'mod-msg'; caja.textContent = ''; }
      campo.classList.remove('bloqueado');
    }
    function peticion(datos) {
      datos.append('accion', 'moderar');
      datos.append('_token', csrf());
      return fetch(URL_ENVIO, { method: 'POST', headers: { 'Accept': 'application/json' }, body: datos })
        .then(res => res.json().catch(() => ({})).then(d => ({ ok: res.ok, d })));
    }
    // Sube un archivo con barra de progreso ("Subiendo… 45%") y luego "Revisando…" mientras el moderador responde.
    function pintarProgreso(caja, texto, pct, girando) {
      caja.className = 'mod-msg visible revisando';
      caja.innerHTML = '<div class="progreso">' + (girando ? '<span class="girando"></span>' : '') + '<span>' + texto + '</span>' +
        (girando ? '' : '<span class="barra"><span style="width:' + pct + '%"></span></span>') + '</div>';
    }
    function subirYRevisar(fd, caja, etiqueta) {
      fd.append('accion', 'moderar'); fd.append('_token', csrf());
      pintarProgreso(caja, 'Subiendo ' + etiqueta + '… 0%', 0, false);
      return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', URL_ENVIO);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.upload.onprogress = e => { if (e.lengthComputable) { const pct = Math.round(e.loaded / e.total * 100); pintarProgreso(caja, 'Subiendo ' + etiqueta + '… ' + pct + '%', pct, false); } };
        xhr.upload.onload = () => pintarProgreso(caja, 'Listo, revisando ' + etiqueta + ' con el moderador…', 100, true);
        xhr.onload = () => { let d = {}; try { d = JSON.parse(xhr.responseText); } catch (e) {} resolve({ ok: xhr.status >= 200 && xhr.status < 300, d }); };
        xhr.onerror = () => reject(new Error('red'));
        xhr.send(fd);
      });
    }

    // Visor: al tocar una imagen se abre en grande
    const visor = document.getElementById('visorImagen');
    document.addEventListener('click', e => {
      const img = e.target.closest('img.zoomable');
      if (!img || !img.src) return;
      document.getElementById('visorImg').src = img.src;
      visor.classList.add('activo');
    });
    visor.addEventListener('click', () => visor.classList.remove('activo'));
    document.addEventListener('keydown', e => { if (e.key === 'Escape') visor.classList.remove('activo'); });

    function mensajeDe(d) {
      if (d && d.errors) return Object.values(d.errors).flat()[0];
      return (d && d.message) || 'No se pudo revisar. Inténtalo de nuevo.';
    }

    form.querySelectorAll('[data-moderar]').forEach(campo => {
      let ultimo = '';
      campo.addEventListener('blur', () => {
        const texto = campo.value.trim();
        if (!texto || texto === ultimo) return;
        ultimo = texto;
        const fd = new FormData(); fd.append('textos[]', texto);
        mostrarAviso(campo, 'Revisando…', 'revisando');
        peticion(fd).then(({ ok, d }) => {
          if (ok && d.ok) { limpiarAviso(campo); return; }
          ultimo = '';
          mostrarAviso(campo, '❌ ' + mensajeDe(d));
        }).catch(() => limpiarAviso(campo));
      });
    });

    // Teléfonos: aviso inmediato de 10 dígitos
    ['telefono', 'whatsapp'].forEach(nombre => {
      const campo = form.querySelector(`[name="${nombre}"]`);
      campo.addEventListener('blur', () => {
        if (campo.value && campo.value.length !== 10) mostrarAviso(campo, '❌ Debe tener exactamente 10 dígitos.');
        else limpiarAviso(campo);
      });
    });

    // ===== Eslogan en la vista previa =====
    function actualizarPreviewEslogan() {
      const texto = form.querySelector('[name="eslogan"]').value.trim();
      if (planActual && planActual !== 'basico' && texto) { previewEslogan.textContent = texto; previewEslogan.style.display = 'block'; }
      else previewEslogan.style.display = 'none';
    }
    form.querySelector('[name="eslogan"]').addEventListener('input', actualizarPreviewEslogan);

    // ===== Imagen elegida (subida o de IA) =====
    const cajaElegida = document.getElementById('imagenElegida');
    function marcarElegida(texto, miniatura) {
      document.getElementById('imagenElegidaTexto').textContent = texto;
      if (miniatura) document.getElementById('miniElegida').src = miniatura;
      cajaElegida.classList.add('visible');
    }
    function quitarImagen() {
      campoImagen.value = ''; inputImagenIa.value = '';
      previewImg.style.display = 'none'; previewPlaceholder.style.display = 'block';
      cajaElegida.classList.remove('visible');
      avisoTamano.style.display = 'none';
      document.getElementById('modImagen').className = 'mod-msg';
      imagenValida = true; imagenAprobada = true; imagenCargada = false;
    }
    document.getElementById('btnQuitarImagen').addEventListener('click', quitarImagen);

    // Pestañas: subir mi imagen / crear con IA
    document.querySelectorAll('.tabs-imagen button').forEach(b => b.addEventListener('click', () => {
      document.querySelectorAll('.tabs-imagen button').forEach(x => x.classList.toggle('activo', x === b));
      document.querySelectorAll('.panel-imagen').forEach(p => p.classList.toggle('activo', p.id === 'panel-' + b.dataset.panel));
    }));

    campoImagen.addEventListener('change', function () {
      const file = this.files[0];
      const msg = document.getElementById('modImagen');
      inputImagenIa.value = '';
      if (!file) { quitarImagen(); return; }

      if (file.size > PESO_MAX_BYTES) {
        quitarImagen();
        avisoTamanoTexto.textContent = `Tu imagen pesa ${(file.size / (1024 * 1024)).toFixed(1)} MB — el máximo permitido es 4 MB. Comprime la imagen o elige otra.`;
        avisoTamano.style.display = 'block'; imagenValida = false;
        return;
      }

      const lector = new FileReader();
      lector.onload = function (e) {
        const img = new Image();
        img.onload = function () {
          if (img.width < ANCHO_MIN || img.height < ALTO_MIN) {
            quitarImagen(); imagenValida = false;
            avisoTamanoTexto.textContent = `Tu imagen es de ${img.width} x ${img.height} px — necesitas al menos ${ANCHO_MIN} x ${ALTO_MIN} px para que se vea nítida.`;
            avisoTamano.style.display = 'block';
            return;
          }
          if (img.width > ANCHO_MAX || img.height > ALTO_MAX) {
            quitarImagen(); imagenValida = false;
            avisoTamanoTexto.textContent = `Tu imagen es de ${img.width} x ${img.height} px — el máximo permitido es ${ANCHO_MAX} x ${ALTO_MAX} px.`;
            avisoTamano.style.display = 'block';
            return;
          }
          imagenValida = true; avisoTamano.style.display = 'none';

          // Moderador de imagen: no se muestra ni se acepta hasta que la apruebe.
          imagenAprobada = false;
          const fd = new FormData(); fd.append('imagen_negocio', file);
          subirYRevisar(fd, msg, 'tu imagen').then(({ ok, d }) => {
            if (ok && d.ok) {
              imagenAprobada = true; imagenCargada = true;
              previewImg.src = e.target.result; previewImg.style.display = 'block'; previewPlaceholder.style.display = 'none';
              msg.textContent = '✓ Imagen subida y aprobada'; msg.className = 'mod-msg visible ok';
              marcarElegida('Tu imagen está lista: ' + file.name, e.target.result);
            } else {
              campoImagen.value = ''; imagenCargada = false;
              msg.textContent = '❌ ' + mensajeDe(d); msg.className = 'mod-msg visible';
            }
          }).catch(() => {
            // Si no hay conexión con el revisor, el servidor la vuelve a revisar al enviar.
            imagenAprobada = true; imagenCargada = true;
            previewImg.src = e.target.result; previewImg.style.display = 'block'; previewPlaceholder.style.display = 'none';
            msg.className = 'mod-msg';
            marcarElegida('Tu imagen está lista: ' + file.name, e.target.result);
          });
        };
        img.src = e.target.result;
      };
      lector.readAsDataURL(file);
    });

    // ===== Crear imagen con IA =====
    let estiloIa = 'fotografia';
    let rutaIaActual = '';
    document.getElementById('estilosIa').addEventListener('click', e => {
      const chip = e.target.closest('.chip-estilo'); if (!chip) return;
      estiloIa = chip.dataset.estilo;
      document.querySelectorAll('#estilosIa .chip-estilo').forEach(c => c.classList.toggle('activo', c === chip));
    });

    // Foto de referencia (opcional): la IA la toma como base
    const inputReferencia = document.getElementById('inputReferencia');
    const miniaturaReferencia = document.getElementById('miniaturaReferencia');
    const btnQuitarReferencia = document.getElementById('btnQuitarReferencia');
    const modReferencia = document.getElementById('modReferencia');
    function quitarReferencia() {
      inputReferencia.value = '';
      miniaturaReferencia.style.display = 'none'; btnQuitarReferencia.style.display = 'none';
      document.getElementById('textoBtnReferencia').textContent = 'Subir una foto de referencia (opcional)';
      document.getElementById('descripcionIa').placeholder = 'Ej. Taquería familiar con tacos al pastor, salsas y cocina a la leña';
      modReferencia.className = 'mod-msg';
    }
    btnQuitarReferencia.addEventListener('click', quitarReferencia);
    inputReferencia.addEventListener('change', function () {
      const f = this.files[0];
      if (!f) { quitarReferencia(); return; }
      if (!/^image\/(jpeg|png|webp)$/.test(f.type)) { quitarReferencia(); modReferencia.textContent = '❌ La foto debe ser JPG, PNG o WebP.'; modReferencia.className = 'mod-msg visible'; return; }
      if (f.size > PESO_MAX_BYTES) { quitarReferencia(); modReferencia.textContent = '❌ La foto pesa ' + (f.size / 1048576).toFixed(1) + ' MB; el máximo es 4 MB.'; modReferencia.className = 'mod-msg visible'; return; }
      const fd = new FormData(); fd.append('imagen_negocio', f);
      subirYRevisar(fd, modReferencia, 'tu foto').then(({ ok, d }) => {
        if (ok && d.ok) {
          miniaturaReferencia.src = URL.createObjectURL(f); miniaturaReferencia.style.display = 'block'; btnQuitarReferencia.style.display = 'inline';
          document.getElementById('textoBtnReferencia').textContent = 'Cambiar foto';
          document.getElementById('descripcionIa').placeholder = 'Ej. Que se vea más apetitosa, con fondo de cocina de leña, para anunciar mi taquería';
          modReferencia.textContent = '✓ Foto subida y aprobada'; modReferencia.className = 'mod-msg visible ok';
        } else {
          quitarReferencia(); modReferencia.textContent = '❌ ' + mensajeDe(d); modReferencia.className = 'mod-msg visible';
        }
      }).catch(() => { quitarReferencia(); modReferencia.textContent = '❌ No se pudo revisar la foto. Inténtalo de nuevo.'; modReferencia.className = 'mod-msg visible'; });
    });

    const btnIa = document.getElementById('btnGenerarIa');
    const iaEstado = document.getElementById('iaEstado');
    const iaResultado = document.getElementById('iaResultado');
    function estadoIa(texto, error) { iaEstado.textContent = texto; iaEstado.className = 'ia-estado' + (error ? ' error' : ''); }

    function generarIa() {
      const descripcion = document.getElementById('descripcionIa').value.trim();
      if (descripcion.length < 10) { estadoIa('Describe un poco más tu negocio (mínimo 10 letras).', true); return; }
      const fd = new FormData();
      fd.append('accion', 'generar_imagen'); fd.append('_token', csrf());
      fd.append('descripcion_ia', descripcion); fd.append('estilo', estiloIa);
      if (inputReferencia.files[0]) fd.append('imagen_referencia', inputReferencia.files[0]);

      btnIa.disabled = true;
      btnIa.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Creando tu imagen…';
      estadoIa('', false);
      iaResultado.classList.remove('visible');
      const cajaCreando = document.getElementById('iaCreando');
      const segundos = document.getElementById('iaSegundos');
      let s = 0; segundos.textContent = '';
      cajaCreando.classList.add('visible');
      const reloj = setInterval(() => { s++; segundos.textContent = '(' + s + ' s)'; }, 1000);
      const corte = new AbortController();
      const limite = setTimeout(() => corte.abort(), 180000);

      fetch(URL_ENVIO, { method: 'POST', headers: { 'Accept': 'application/json' }, body: fd, signal: corte.signal })
        .then(res => res.json().catch(() => ({})).then(d => ({ ok: res.ok, d })))
        .then(({ ok, d }) => {
          if (!ok || !d.ok) { estadoIa('❌ ' + mensajeDe(d) + (d && d.restantes !== undefined ? ' (Intentos restantes hoy: ' + d.restantes + ')' : ''), true); return; }
          rutaIaActual = d.ruta;
          document.getElementById('iaImagen').src = d.url;
          iaResultado.classList.add('visible');
          estadoIa('¡Lista! Intentos restantes hoy: ' + d.restantes, false);
        })
        .catch(err => estadoIa(err && err.name === 'AbortError'
          ? '❌ La IA tardó demasiado en responder. Inténtalo de nuevo.'
          : '❌ No se pudo conectar con el servidor. Inténtalo de nuevo.', true))
        .finally(() => {
          clearInterval(reloj); clearTimeout(limite); cajaCreando.classList.remove('visible');
          btnIa.disabled = false; btnIa.innerHTML = '<i class="bi bi-stars"></i> Crear mi imagen con IA';
        });
    }
    btnIa.addEventListener('click', generarIa);
    document.getElementById('btnOtraIa').addEventListener('click', generarIa);
    document.getElementById('btnUsarIa').addEventListener('click', () => {
      if (!rutaIaActual) return;
      campoImagen.value = ''; imagenValida = true; imagenAprobada = true; avisoTamano.style.display = 'none';
      document.getElementById('modImagen').className = 'mod-msg';
      inputImagenIa.value = rutaIaActual;
      previewImg.src = document.getElementById('iaImagen').src; previewImg.style.display = 'block'; previewPlaceholder.style.display = 'none';
      imagenCargada = true;
      marcarElegida('Usarás la imagen creada con IA', document.getElementById('iaImagen').src);
      iaResultado.classList.remove('visible');
      estadoIa('', false);
    });

    // ===== Ubicación: avisa si parece link de Google Maps =====
    function actualizarAvisoUbicacion() {
      const texto = inputLinkUbicacion.value.trim();
      if (!texto) { ubicacionCoordsTexto.innerHTML = '<i class="bi bi-info-circle"></i> Aún no has pegado ningún enlace.'; ubicacionCoordsTexto.classList.remove('marcada'); return; }
      if (/google\.[a-z.]+\/maps|maps\.google|goo\.gl|g\.co\//.test(texto)) {
        ubicacionCoordsTexto.innerHTML = '<i class="bi bi-check-circle-fill"></i> Listo, este link se usará para el botón "Cómo llegar".';
        ubicacionCoordsTexto.classList.add('marcada');
      } else {
        ubicacionCoordsTexto.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> Eso no parece un link de Google Maps. Pega el enlace que copiaste ahí.';
        ubicacionCoordsTexto.classList.remove('marcada');
      }
    }
    inputLinkUbicacion.addEventListener('input', actualizarAvisoUbicacion);
    actualizarAvisoUbicacion();

    // ===== Lightbox =====
    const lightboxOverlay = document.getElementById('lightboxOverlay');
    const lightboxImg = document.getElementById('lightboxImg');
    const lightboxBoton = document.getElementById('lightboxBoton');
    const lightboxBotonUbicacion = document.getElementById('lightboxBotonUbicacion');
    previewAdBox.addEventListener('click', function () {
      if (!imagenCargada) { mostrarToastAjax('Primero sube o crea una imagen para ver cómo se vería en grande.', 'error'); return; }
      lightboxImg.src = previewImg.src; lightboxImg.style.display = 'block';
      document.getElementById('lightboxNombre').textContent = nombreNegocioInput.value.trim() || 'Tu negocio';
      const lightboxEslogan = document.getElementById('lightboxEslogan');
      if (planActual === 'basico') lightboxEslogan.style.display = 'none';
      else { lightboxEslogan.textContent = form.querySelector('[name="eslogan"]').value.trim() || 'Tu eslogan aparecería aquí'; lightboxEslogan.style.display = 'block'; }

      const link = (planActual !== 'basico') ? linkExternoInput.value.trim() : '';
      if (link) { lightboxBoton.style.display = 'inline-flex'; lightboxBoton.href = link; lightboxBoton.onclick = null; lightboxBoton.target = '_blank'; lightboxBoton.rel = 'noopener'; }
      else lightboxBoton.style.display = 'none';

      if (planActual !== 'basico' && inputLinkUbicacion.value.trim()) { lightboxBotonUbicacion.style.display = 'inline-flex'; lightboxBotonUbicacion.href = inputLinkUbicacion.value.trim(); }
      else lightboxBotonUbicacion.style.display = 'none';
      lightboxOverlay.classList.add('activo');
    });
    document.getElementById('lightboxCerrar').addEventListener('click', () => lightboxOverlay.classList.remove('activo'));
    lightboxOverlay.addEventListener('click', e => { if (e.target === lightboxOverlay) lightboxOverlay.classList.remove('activo'); });

    function mostrarToastAjax(msg, tipo) {
      const toast = document.getElementById('toastAjaxAnuncio');
      toast.textContent = msg;
      toast.className = 'alert ' + (tipo === 'error' ? 'alert-danger' : 'alert-success');
      toast.style.display = 'block';
      toast.style.animation = 'none'; toast.offsetHeight; toast.style.animation = null;
      setTimeout(() => { toast.style.display = 'none'; }, 6500);
    }

    // ===== Enviar =====
    const textoOriginalBoton = btnPagar.innerHTML;
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (btnPagar.disabled) return;
      if (!planActual) { document.getElementById('planesGrid').scrollIntoView({ behavior: 'smooth' }); mostrarToastAjax('Primero elige un plan.', 'error'); return; }

      // Obligatorios (según el plan)
      const falta = ['nombre_negocio', 'nombre_encargado', 'descripcion', 'direccion', 'telefono']
        .concat(planActual !== 'basico' ? ['eslogan'] : [])
        .find(n => !form.querySelector(`[name="${n}"]`).value.trim());
      if (falta) {
        const campo = form.querySelector(`[name="${falta}"]`);
        campo.scrollIntoView({ behavior: 'smooth', block: 'center' }); campo.focus();
        mostrarToastAjax('Falta llenar un dato obligatorio (marcado con *).', 'error');
        return;
      }
      if (form.querySelector('[name="telefono"]').value.length !== 10 || (form.querySelector('[name="whatsapp"]').value && form.querySelector('[name="whatsapp"]').value.length !== 10)) {
        mostrarToastAjax('❌ Los teléfonos deben tener exactamente 10 dígitos.', 'error'); return;
      }
      if (form.querySelector('.form-control.bloqueado')) {
        form.querySelector('.form-control.bloqueado').scrollIntoView({ behavior: 'smooth', block: 'center' });
        mostrarToastAjax('❌ Corrige los campos marcados en rojo antes de continuar al pago.', 'error'); return;
      }
      if (!imagenValida) {
        avisoTamano.style.display = 'block'; avisoTamano.scrollIntoView({ behavior: 'smooth', block: 'center' });
        mostrarToastAjax('❌ El tamaño de tu imagen no es válido. Debe estar entre ' + ANCHO_MIN + 'x' + ALTO_MIN + ' px y ' + ANCHO_MAX + 'x' + ALTO_MAX + ' px.', 'error'); return;
      }
      if (!imagenAprobada) { mostrarToastAjax('Estamos revisando tu imagen, espera un segundo.', 'error'); return; }
      if (!campoImagen.files[0] && !inputImagenIa.value) {
        document.getElementById('labelImagen').scrollIntoView({ behavior: 'smooth', block: 'center' });
        mostrarToastAjax('❌ Sube la imagen de tu anuncio o créala con IA.', 'error'); return;
      }

      btnPagar.disabled = true; btnPagar.innerHTML = 'Enviando...';
      fetch(URL_ENVIO, { method: 'POST', headers: { 'Accept': 'application/json' }, body: new FormData(form) })
        .then(res => res.json().catch(() => ({})).then(data => ({ status: res.status, data })))
        .then(({ status, data }) => {
          if (status >= 200 && status < 300 && data.ok) { window.location.href = data.redirectUrl; return; }
          mostrarToastAjax('❌ ' + (data.errors ? Object.values(data.errors).flat()[0] : (data.message || 'Ocurrió un error al enviar tu solicitud. Inténtalo de nuevo.')), 'error');
          btnPagar.disabled = false; btnPagar.innerHTML = textoOriginalBoton;
        })
        .catch(() => {
          mostrarToastAjax('❌ No se pudo conectar con el servidor. Revisa tu internet e inténtalo de nuevo.', 'error');
          btnPagar.disabled = false; btnPagar.innerHTML = textoOriginalBoton;
        });
    });

    actualizarContadores();
    // Si la página regresó con datos (old), se vuelve a abrir el formulario con el plan que tenía.
    @if (old('plan'))
      selectPlan(@json(old('plan')));
    @endif
  </script>
  <script>
    // "Regresar" hace lo mismo que la flechita del navegador: vuelve a la pantalla de donde
    // vino (por ejemplo, el modo empleador). Si entró directo a esta página, va al inicio.
    (function () {
      var btn = document.getElementById('btnRegresar');
      if (!btn) return;
      btn.addEventListener('click', function (e) {
        var vinoDeAqui = false;
        try { vinoDeAqui = document.referrer && new URL(document.referrer).origin === location.origin; } catch (err) {}
        if (vinoDeAqui && history.length > 1) {
          e.preventDefault();
          history.back();
        }
      });
    })();
  </script>
</body>
</html>
