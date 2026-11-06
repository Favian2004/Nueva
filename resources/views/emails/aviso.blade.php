<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $titulo }}</title>
</head>
<body style="margin:0; padding:0; background:#f4f4f4; font-family: Arial, Helvetica, sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4; padding:30px 0;">
    <tr>
      <td align="center">
        <table width="480" cellpadding="0" cellspacing="0" style="background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,.08); max-width:100%;">

          <tr>
            <td style="background:linear-gradient(90deg,#6b1021,#8f1d2f,#b12d25); padding:26px 30px; text-align:center;">
              <h1 style="color:#fff; margin:0; font-size:22px;">¡SINTECZATE!</h1>
            </td>
          </tr>

          <tr>
            <td style="padding:32px 30px;">
              <h2 style="text-align:center; color:#1a1a2e; font-size:20px; margin:0 0 12px;">{{ $titulo }}</h2>
              <p style="text-align:center; color:#444; font-size:15px; line-height:1.5; margin:0 0 20px;">{{ $texto }}</p>

              @if (!empty($detalle))
                <table width="100%" cellpadding="0" cellspacing="0" style="background:#f9fafb; border-radius:12px; margin-bottom:22px;">
                  <tr>
                    <td style="padding:14px 18px; font-size:13.5px; line-height:1.5; color:#555;">{{ $detalle }}</td>
                  </tr>
                </table>
              @endif

              <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td align="center">
                    <a href="{{ $url }}" style="display:inline-block; background:#e8590c; color:#ffffff; text-decoration:none; font-weight:bold; font-size:14.5px; padding:12px 26px; border-radius:10px;">{{ $boton }}</a>
                  </td>
                </tr>
              </table>
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
