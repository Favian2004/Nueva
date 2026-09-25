<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>¡Tu anuncio ya está publicado!</title>
</head>
<body style="margin:0; padding:0; background:#f4f4f4; font-family: Arial, Helvetica, sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4; padding:30px 0;">
    <tr>
      <td align="center">
        <table width="480" cellpadding="0" cellspacing="0" style="background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,.08);">

          <tr>
            <td style="background:linear-gradient(90deg,#6b1021,#8f1d2f,#b12d25); padding:26px 30px; text-align:center;">
              <h1 style="color:#fff; margin:0; font-size:22px;">¡SINTECZATE!</h1>
            </td>
          </tr>

          <tr>
            <td style="padding:32px 30px;">
              <div style="width:64px; height:64px; border-radius:50%; background:linear-gradient(135deg,#16a34a,#4ade80); display:flex; align-items:center; justify-content:center; margin:0 auto 20px;">
                <span style="color:#fff; font-size:30px; line-height:64px;">✓</span>
              </div>

              <h2 style="text-align:center; color:#1a1a2e; font-size:19px; margin:0 0 8px;">¡Tu anuncio ya está publicado!</h2>
              <p style="text-align:center; color:#666; font-size:14px; margin:0 0 24px;">
                El anuncio de <strong>{{ $solicitud->nombre_negocio }}</strong> ya se está mostrando en ¡SINTECZATE!
              </p>

              <table width="100%" cellpadding="0" cellspacing="0" style="background:#f9fafb; border-radius:12px; padding:18px; margin-bottom:20px;">
                <tr>
                  <td style="padding:6px 0; font-size:13.5px; color:#555;">
                    <strong>Empieza a mostrarse:</strong><br>
                    {{ \Carbon\Carbon::parse($fechaInicio)->translatedFormat('d \d\e F \d\e Y') }}
                  </td>
                </tr>
                <tr>
                  <td style="padding:6px 0; font-size:13.5px; color:#555;">
                    <strong>Se muestra hasta:</strong><br>
                    {{ \Carbon\Carbon::parse($fechaVencimiento)->translatedFormat('d \d\e F \d\e Y') }}
                  </td>
                </tr>
              </table>

              <p style="text-align:center; color:#888; font-size:12.5px; margin:0;">
                Gracias por confiar en ¡SINTECZATE! para promocionar tu negocio.
              </p>
            </td>
          </tr>

          <tr>
            <td style="background:#faf9f7; padding:16px 30px; text-align:center;">
              <p style="color:#999; font-size:11.5px; margin:0;">¡SINTECZATE! · Zacapoaxtla, Puebla</p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
