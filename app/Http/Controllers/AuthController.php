<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/acceso')->with('error', 'No se pudo iniciar sesión con Google. Intenta de nuevo.');
        }

        // Busca primero por google_id (ya se registró antes con Google),
        // si no, busca por email (por si ya tenía cuenta creada de otra forma).
        $usuario = Usuario::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($usuario) {
            // Si ya existía por email pero sin google_id, lo vinculamos.
            if (!$usuario->google_id) {
                $usuario->google_id = $googleUser->getId();
                $usuario->save();
            }
        } else {
            // Usuario nuevo: se crea con datos básicos.
            // TODO: cuando haya más de una localidad para elegir en el registro,
            // mandarlo a completar su perfil en vez de fijar localidad_id = 1.
            $usuario = Usuario::create([
                'nombre' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Usuario de Google',
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'foto_perfil' => $googleUser->getAvatar(),
                'localidad_id' => 1,
                'rol' => 'usuario',
                'estado' => 'activo',
                'verificacion_estado' => 'pendiente',
                'email_verified_at' => now(),
            ]);
        }

        // Google ya confirmó que este correo es real, así que si por alguna
        // razón la cuenta existente aún no estaba verificada, la marcamos ahora.
        if (!$usuario->hasVerifiedEmail()) {
            $usuario->markEmailAsVerified();
        }

        if ($usuario->estado === 'suspendido') {
            return redirect('/acceso')->with('error', 'Tu cuenta está suspendida. Motivo: ' . ($usuario->motivo_suspension ?? 'no especificado'));
        }

        Auth::login($usuario, true);

        return redirect($usuario->rol === 'admin' ? '/admin' : '/usuario');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $usuario = Usuario::where('email', $request->input('email'))->first();

        if (!$usuario || !$usuario->password || !\Illuminate\Support\Facades\Hash::check($request->input('password'), $usuario->password)) {
            return redirect('/acceso')->with('error', 'Correo o contraseña incorrectos.')->withInput();
        }

        if ($usuario->estado === 'suspendido') {
            return redirect('/acceso')->with('error', 'Tu cuenta está suspendida. Motivo: ' . ($usuario->motivo_suspension ?? 'no especificado'));
        }

        Auth::login($usuario, true);

        if (!$usuario->hasVerifiedEmail()) {
            return redirect('/email/verify');
        }

        return redirect($usuario->rol === 'admin' ? '/admin' : '/usuario');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:usuarios,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // TODO: cuando haya más de una localidad para elegir en el registro,
        // mandarlo a completar su perfil en vez de fijar localidad_id = 1.
        $usuario = Usuario::create([
            'nombre' => $request->input('nombre'),
            'email' => $request->input('email'),
            'password' => \Illuminate\Support\Facades\Hash::make($request->input('password')),
            'localidad_id' => 1,
            'rol' => 'usuario',
            'estado' => 'activo',
            'verificacion_estado' => 'pendiente',
        ]);

        Auth::login($usuario, true);

        $usuario->sendEmailVerificationNotification();

        return redirect('/email/verify')->with('status', 'Te creamos la cuenta. Revisa tu correo para confirmarlo antes de continuar.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function verifyEmailNotice()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect(Auth::user()->rol === 'admin' ? '/admin' : '/usuario');
        }

        return view('auth.verify-email');
    }

    public function verifyEmail(Request $request)
    {
        $usuario = \App\Models\Usuario::findOrFail($request->route('id'));

        if (!hash_equals((string) $request->route('hash'), sha1($usuario->getEmailForVerification()))) {
            abort(403, 'Link de verificación inválido.');
        }

        if (!$usuario->hasVerifiedEmail()) {
            $usuario->markEmailAsVerified();
        }

        Auth::login($usuario, true);

        return redirect(($usuario->rol === 'admin' ? '/admin' : '/usuario') . '?verificado=1');
    }

    public function resendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/usuario');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Te mandamos otro correo de verificación.');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = \Illuminate\Support\Facades\Password::sendResetLink(
            $request->only('email')
        );

        if ($status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT) {
            return redirect('/acceso')->with('status', 'Te mandamos un correo con un link para restablecer tu contraseña.');
        }

        return redirect('/acceso')->with('error', 'No encontramos ninguna cuenta con ese correo.');
    }

    public function showResetForm($token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => request('email'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = \Illuminate\Support\Facades\Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($usuario, $password) {
                $usuario->password = \Illuminate\Support\Facades\Hash::make($password);
                $usuario->save();
            }
        );

        if ($status === \Illuminate\Support\Facades\Password::PASSWORD_RESET) {
            return redirect('/acceso')->with('status', 'Tu contraseña se actualizó correctamente. Ya puedes iniciar sesión.');
        }

        return back()->withErrors(['email' => 'Ese link ya no es válido o expiró. Solicita uno nuevo.']);
    }
}
