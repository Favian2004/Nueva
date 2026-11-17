<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Lo que el empleador le explica a la persona al rechazar su postulacion (opcional).
    public function up(): void
    {
        if (! Schema::hasColumn('postulaciones', 'motivo_rechazo')) {
            Schema::table('postulaciones', function (Blueprint $table) {
                $table->string('motivo_rechazo', 300)->nullable()->after('estado');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('postulaciones', 'motivo_rechazo')) {
            Schema::table('postulaciones', function (Blueprint $table) {
                $table->dropColumn('motivo_rechazo');
            });
        }
    }
};
