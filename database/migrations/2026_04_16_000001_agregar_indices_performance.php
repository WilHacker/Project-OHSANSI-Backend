<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega índices de performance en las columnas más consultadas.
 *
 * Estas columnas aparecen frecuentemente en cláusulas WHERE, JOIN y ORDER BY
 * pero no tenían índice, lo que causaba full table scans con volumen de datos.
 */
return new class extends Migration
{
    public function up(): void
    {
        // --- Tabla: evaluacion ---
        // bloqueado_por: filtros de semáforo (quién tiene bloqueada la ficha)
        // estado_participacion: clasificado / no clasificado / descalificado
        // resultado_calculado: filtros de ranking y medallero
        Schema::table('evaluacion', function (Blueprint $table) {
            $table->index('bloqueado_por', 'idx_evaluacion_bloqueado_por');
            $table->index('estado_participacion', 'idx_evaluacion_estado_participacion');
            $table->index('resultado_calculado', 'idx_evaluacion_resultado_calculado');
        });

        // --- Tabla: competencia ---
        // id_fase_global: filtros por fase en el dashboard del responsable
        // id_usuario_aval: trazabilidad del usuario que avaló
        // estado: filtro principal en listados (borrador/publicada/iniciada/cerrada)
        Schema::table('competencia', function (Blueprint $table) {
            $table->index('id_fase_global', 'idx_competencia_id_fase_global');
            $table->index('id_usuario_aval', 'idx_competencia_id_usuario_aval');
            $table->index('estado', 'idx_competencia_estado');
        });

        // --- Tabla: area_nivel ---
        // id_area_olimpiada: join frecuente para obtener áreas de la olimpiada activa
        Schema::table('area_nivel', function (Blueprint $table) {
            $table->index('id_area_olimpiada', 'idx_area_nivel_id_area_olimpiada');
        });

        // --- Tabla: usuario_rol ---
        // id_olimpiada: filtro para obtener roles del usuario en la gestión activa
        Schema::table('usuario_rol', function (Blueprint $table) {
            $table->index('id_olimpiada', 'idx_usuario_rol_id_olimpiada');
        });

        // --- Tabla: parametro ---
        // id_area_nivel: lookup del parámetro de clasificación por área-nivel
        Schema::table('parametro', function (Blueprint $table) {
            $table->index('id_area_nivel', 'idx_parametro_id_area_nivel');
        });

        // --- Tabla: evaluador_an ---
        // Índice compuesto (id_area_nivel, estado): consulta principal del dashboard de evaluadores
        Schema::table('evaluador_an', function (Blueprint $table) {
            $table->index(['id_area_nivel', 'estado'], 'idx_evaluador_an_area_nivel_estado');
        });
    }

    public function down(): void
    {
        Schema::table('evaluacion', function (Blueprint $table) {
            $table->dropIndex('idx_evaluacion_bloqueado_por');
            $table->dropIndex('idx_evaluacion_estado_participacion');
            $table->dropIndex('idx_evaluacion_resultado_calculado');
        });

        Schema::table('competencia', function (Blueprint $table) {
            $table->dropIndex('idx_competencia_id_fase_global');
            $table->dropIndex('idx_competencia_id_usuario_aval');
            $table->dropIndex('idx_competencia_estado');
        });

        Schema::table('area_nivel', function (Blueprint $table) {
            $table->dropIndex('idx_area_nivel_id_area_olimpiada');
        });

        Schema::table('usuario_rol', function (Blueprint $table) {
            $table->dropIndex('idx_usuario_rol_id_olimpiada');
        });

        Schema::table('parametro', function (Blueprint $table) {
            $table->dropIndex('idx_parametro_id_area_nivel');
        });

        Schema::table('evaluador_an', function (Blueprint $table) {
            $table->dropIndex('idx_evaluador_an_area_nivel_estado');
        });
    }
};
