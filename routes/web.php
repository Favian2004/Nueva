<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/acceso', [App\Http\Controllers\HomeController::class, 'acceso']);
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/forgot-password', [App\Http\Controllers\AuthController::class, 'sendResetLink']);
Route::get('/reset-password/{token}', [App\Http\Controllers\AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\AuthController::class, 'resetPassword']);

Route::get('/email/verify', [App\Http\Controllers\AuthController::class, 'verifyEmailNotice'])
    ->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\AuthController::class, 'verifyEmail'])
    ->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', [App\Http\Controllers\AuthController::class, 'resendVerification'])
    ->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/auth/google/redirect', [App\Http\Controllers\AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [App\Http\Controllers\AuthController::class, 'handleGoogleCallback']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
Route::get('/acerca-de', [App\Http\Controllers\HomeController::class, 'acercaDe']);
Route::get('/servicio-cliente', [App\Http\Controllers\HomeController::class, 'servicioCliente']);
Route::get('/terminos', [App\Http\Controllers\HomeController::class, 'terminos']);
Route::get('/anunciar', [App\Http\Controllers\SolicitudAnuncioController::class, 'create'])->name('anunciar');
Route::post('/anunciar', [App\Http\Controllers\SolicitudAnuncioController::class, 'store'])->name('anunciar.store');
Route::get('/api/anuncios', [App\Http\Controllers\Api\AnuncioApiController::class, 'index']);


// =====================================================================
// PANEL DE ADMINISTRADOR (requiere sesión iniciada Y rol = admin)
// =====================================================================
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin', [App\Http\Controllers\AdminDashboardController::class, 'index']);
    Route::get('/admin/anuncios', [App\Http\Controllers\AdminAnuncioController::class, 'index']);
    Route::patch('/admin/anuncios/{id}/toggle', [App\Http\Controllers\AdminAnuncioController::class, 'toggle']);
    Route::post('/admin/anuncios/{id}/imagenes', [App\Http\Controllers\AdminAnuncioController::class, 'storeImagen']);
    Route::delete('/admin/anuncio-imagenes/{id}', [App\Http\Controllers\AdminAnuncioController::class, 'destroyImagen']);
    Route::get('/admin/categorias', [App\Http\Controllers\AdminCategoriaController::class, 'index']);
    Route::post('/admin/categorias', [App\Http\Controllers\AdminCategoriaController::class, 'storeCategoria']);
    Route::delete('/admin/categorias/{id}', [App\Http\Controllers\AdminCategoriaController::class, 'destroyCategoria']);
    Route::post('/admin/subcategorias', [App\Http\Controllers\AdminCategoriaController::class, 'storeSubcategoria']);
    Route::delete('/admin/subcategorias/{id}', [App\Http\Controllers\AdminCategoriaController::class, 'destroySubcategoria']);
    Route::get('/admin/contrataciones', [App\Http\Controllers\AdminContratacionController::class, 'index']);
    Route::get('/admin/municipios', [App\Http\Controllers\AdminMunicipioController::class, 'index']);
    Route::post('/admin/municipios', [App\Http\Controllers\AdminMunicipioController::class, 'storeMunicipio']);
    Route::delete('/admin/municipios/{id}', [App\Http\Controllers\AdminMunicipioController::class, 'destroyMunicipio']);
    Route::post('/admin/localidades', [App\Http\Controllers\AdminMunicipioController::class, 'storeLocalidad']);
    Route::delete('/admin/localidades/{id}', [App\Http\Controllers\AdminMunicipioController::class, 'destroyLocalidad']);
    Route::get('/admin/postulaciones', [App\Http\Controllers\AdminPostulacionController::class, 'index']);
    Route::patch('/admin/postulaciones/{id}', [App\Http\Controllers\AdminPostulacionController::class, 'cambiarEstado']);
    Route::get('/admin/profile', [App\Http\Controllers\AdminProfileController::class, 'index']);
    Route::patch('/admin/profile', [App\Http\Controllers\AdminProfileController::class, 'update']);
    Route::patch('/admin/profile/password', [App\Http\Controllers\AdminProfileController::class, 'updatePassword']);
    Route::get('/admin/reportes', [App\Http\Controllers\AdminReporteController::class, 'index']);
    Route::patch('/admin/reportes/{id}', [App\Http\Controllers\AdminReporteController::class, 'cambiarEstado']);
    Route::patch('/admin/reportes/{id}/suspender-usuario', [App\Http\Controllers\AdminReporteController::class, 'suspenderUsuario']);
    Route::get('/admin/servicios', [App\Http\Controllers\AdminServicioController::class, 'index']);
    Route::patch('/admin/servicios/{id}/toggle', [App\Http\Controllers\AdminServicioController::class, 'toggle']);
    Route::delete('/admin/servicios/{id}', [App\Http\Controllers\AdminServicioController::class, 'destroy']);
    Route::get('/admin/usuarios', [App\Http\Controllers\AdminUsuarioController::class, 'index']);
    Route::patch('/admin/usuarios/{id}/suspender', [App\Http\Controllers\AdminUsuarioController::class, 'suspender']);
    Route::patch('/admin/usuarios/{id}/reactivar', [App\Http\Controllers\AdminUsuarioController::class, 'reactivar']);
    Route::patch('/admin/usuarios/{id}/verificacion', [App\Http\Controllers\AdminUsuarioController::class, 'resolverVerificacion']);
    Route::post('/admin/usuarios/crear-admin', [App\Http\Controllers\AdminUsuarioController::class, 'crearAdmin']);
    Route::get('/admin/vacantes', [App\Http\Controllers\AdminVacanteController::class, 'index']);
    Route::patch('/admin/vacantes/{id}/cerrar', [App\Http\Controllers\AdminVacanteController::class, 'cerrar']);
    Route::patch('/admin/vacantes/{id}/reactivar', [App\Http\Controllers\AdminVacanteController::class, 'reactivar']);
    Route::delete('/admin/vacantes/{id}', [App\Http\Controllers\AdminVacanteController::class, 'destroy']);
});


// =====================================================================
// DASHBOARD DE USUARIO (requiere sesión iniciada, cualquier rol)
// =====================================================================
Route::middleware(['auth', 'verified', 'not-admin'])->group(function () {

    Route::get('/usuario', [App\Http\Controllers\UsuarioDashboardController::class, 'index']);
    Route::get('/usuario/buscar-talento', [App\Http\Controllers\UsuarioEmpleadorController::class, 'buscarTalento']);
    Route::get('/usuario/empleador', [App\Http\Controllers\UsuarioEmpleadorController::class, 'index']);
    Route::post('/usuario/empleador/postularse/{vacanteId}', [App\Http\Controllers\UsuarioEmpleadorController::class, 'postularse']);
    Route::get('/usuario/mis-vacantes', [App\Http\Controllers\UsuarioVacanteController::class, 'misVacantes']);
    Route::get('/usuario/mis-vacantes/{id}/editar', [App\Http\Controllers\UsuarioVacanteController::class, 'edit']);
    Route::patch('/usuario/mis-vacantes/{id}/cerrar', [App\Http\Controllers\UsuarioVacanteController::class, 'cerrar']);
    Route::patch('/usuario/mis-vacantes/{id}/reactivar', [App\Http\Controllers\UsuarioVacanteController::class, 'reactivar']);
    Route::patch('/usuario/mis-vacantes/{id}', [App\Http\Controllers\UsuarioVacanteController::class, 'update']);
    Route::delete('/usuario/mis-vacantes/{id}', [App\Http\Controllers\UsuarioVacanteController::class, 'destroy']);
    Route::get('/usuario/misEmpleos', [App\Http\Controllers\UsuarioServicioController::class, 'misEmpleos']);
    Route::get('/usuario/misEmpleos/{id}/editar', [App\Http\Controllers\UsuarioServicioController::class, 'edit']);
    Route::patch('/usuario/misEmpleos/{id}/toggle', [App\Http\Controllers\UsuarioServicioController::class, 'toggle']);
    Route::patch('/usuario/misEmpleos/{id}', [App\Http\Controllers\UsuarioServicioController::class, 'update']);
    Route::delete('/usuario/misEmpleos/{id}', [App\Http\Controllers\UsuarioServicioController::class, 'destroy']);
    Route::get('/usuario/postulantes', [App\Http\Controllers\UsuarioPostulanteController::class, 'index']);
    Route::patch('/usuario/postulantes/{id}', [App\Http\Controllers\UsuarioPostulanteController::class, 'cambiarEstado']);
    Route::get('/usuario/profile', [App\Http\Controllers\UsuarioProfileController::class, 'index']);
    Route::patch('/usuario/profile', [App\Http\Controllers\UsuarioProfileController::class, 'update']);
    Route::patch('/usuario/profile/password', [App\Http\Controllers\UsuarioProfileController::class, 'updatePassword']);
    Route::post('/usuario/profile/documento/{tipo}', [App\Http\Controllers\UsuarioProfileController::class, 'uploadDocumento']);
    Route::get('/usuario/publicar-vacante', [App\Http\Controllers\UsuarioVacanteController::class, 'create']);
    Route::post('/usuario/publicar-vacante', [App\Http\Controllers\UsuarioVacanteController::class, 'store']);
    Route::get('/usuario/publicarEmpleo', [App\Http\Controllers\UsuarioServicioController::class, 'create']);
    Route::post('/usuario/publicarEmpleo', [App\Http\Controllers\UsuarioServicioController::class, 'store']);
    Route::get('/usuario/verEmpleos', [App\Http\Controllers\UsuarioDashboardController::class, 'verEmpleos']);
    Route::get('/usuario/ver_servicio/{id}', [App\Http\Controllers\UsuarioServicioController::class, 'show']);
    Route::post('/usuario/ver_servicio/{id}/solicitar', [App\Http\Controllers\UsuarioServicioController::class, 'solicitar']);
    Route::get('/usuario/solicitudes', [App\Http\Controllers\UsuarioSolicitudController::class, 'index']);
    Route::patch('/usuario/solicitudes/{id}', [App\Http\Controllers\UsuarioSolicitudController::class, 'cambiarEstado']);
    Route::get('/usuario/comentarios/{tipo}/{id}', [App\Http\Controllers\UsuarioComentarioController::class, 'index']);
    Route::post('/usuario/comentarios/{tipo}/{id}', [App\Http\Controllers\UsuarioComentarioController::class, 'store']);
    Route::delete('/usuario/comentarios/{id}', [App\Http\Controllers\UsuarioComentarioController::class, 'destroy']);
    Route::patch('/usuario/comentarios/{id}', [App\Http\Controllers\UsuarioComentarioController::class, 'update']);
    Route::post('/usuario/reportar/{tipo}/{id}', [App\Http\Controllers\UsuarioReporteController::class, 'store']);
    Route::get('/usuario/calificaciones/{servicioId}', [App\Http\Controllers\UsuarioCalificacionController::class, 'index']);
    Route::post('/usuario/calificaciones/{servicioId}', [App\Http\Controllers\UsuarioCalificacionController::class, 'store']);
    Route::delete('/usuario/calificaciones/{id}', [App\Http\Controllers\UsuarioCalificacionController::class, 'destroy']);
});
