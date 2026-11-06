<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega la categoría a cada vacante (para el pronóstico de demanda).
     * Es nullable porque las vacantes viejas no tienen categoría todavía.
     * Si la columna ya existe (por ejemplo, porque corriste el SQL a mano
     * en phpMyAdmin), no hace nada.
     */
    public function up(): void
    {
        if (Schema::hasTable('vacantes') && !Schema::hasColumn('vacantes', 'categoria_id')) {
            Schema::table('vacantes', function (Blueprint $table) {
                $table->unsignedBigInteger('categoria_id')->nullable()->after('empleador_id');
                $table->index('categoria_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('vacantes') && Schema::hasColumn('vacantes', 'categoria_id')) {
            Schema::table('vacantes', function (Blueprint $table) {
                $table->dropIndex(['categoria_id']);
                $table->dropColumn('categoria_id');
            });
        }
    }
};
