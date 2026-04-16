<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluacion', function (Blueprint $table) {
            // JOIN frecuente: cargar evaluaciones por competidor
            $table->index('id_competidor', 'idx_evaluacion_id_competidor');
        });

        Schema::table('examen', function (Blueprint $table) {
            // JOIN frecuente: cargar exámenes por competencia
            $table->index('id_competencia', 'idx_examen_id_competencia');
        });

        Schema::table('competencia', function (Blueprint $table) {
            // JOIN frecuente: filtrar competencias por área-nivel
            $table->index('id_area_nivel', 'idx_competencia_id_area_nivel');
        });
    }

    public function down(): void
    {
        Schema::table('evaluacion', function (Blueprint $table) {
            $table->dropIndex('idx_evaluacion_id_competidor');
        });

        Schema::table('examen', function (Blueprint $table) {
            $table->dropIndex('idx_examen_id_competencia');
        });

        Schema::table('competencia', function (Blueprint $table) {
            $table->dropIndex('idx_competencia_id_area_nivel');
        });
    }
};
