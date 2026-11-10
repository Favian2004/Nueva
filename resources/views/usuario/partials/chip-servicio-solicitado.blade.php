{{-- Línea "Servicio solicitado" de las tarjetas de Solicitudes. Recibe $servicio (puede ser null si lo borraron). --}}
@if ($servicio)
  <a href="/usuario/ver_servicio/{{ $servicio->id }}" title="Ver el servicio"
     style="display:flex; align-items:center; gap:8px; margin:2px 0 8px; padding:7px 10px; background:#fff8f1; border:1px solid #fde4c8; border-radius:10px; text-decoration:none; color:#444; font-size:12px; line-height:1.35;">
    <span style="flex:0 0 26px; width:26px; height:26px; border-radius:8px; background:#ffe8d1; color:#ea580c; display:flex; align-items:center; justify-content:center; font-size:15px;"><i class="mdi mdi-briefcase-outline"></i></span>
    <span style="flex:1; min-width:0;">
      <span style="display:block; font-size:10px; font-weight:800; letter-spacing:.4px; color:#c2410c;">SERVICIO SOLICITADO</span>
      <strong style="display:block; color:#1f2937; overflow-wrap:anywhere;">{{ $servicio->titulo }}</strong>
    </span>
    @if ($servicio->precio !== null && (float) $servicio->precio > 0)
      <span style="flex:0 0 auto; font-weight:700; color:#16a34a;">${{ number_format((float) $servicio->precio, 0) }} MXN</span>
    @endif
    <i class="mdi mdi-chevron-right" style="flex:0 0 auto; color:#c3c8cf; font-size:18px;"></i>
  </a>
@else
  <div style="margin:2px 0 8px; padding:7px 10px; background:#f3f4f6; border-radius:10px; font-size:12px; color:#6b7280;"><i class="mdi mdi-briefcase-remove-outline"></i> El servicio ya no está disponible.</div>
@endif
