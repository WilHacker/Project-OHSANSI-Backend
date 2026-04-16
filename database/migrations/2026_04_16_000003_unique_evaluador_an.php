<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluador_an', function (Blueprint $table) {
            // Evitar que un mismo usuario sea asignado dos veces al mismo área-nivel
            $table->unique(['id_usuario', 'id_area_nivel'], 'uq_evaluador_usuario_area_nivel');
        });
    }

    public function down(): void
    {
        Schema::table('evaluador_an', function (Blueprint $table) {
            $table->dropUnique('uq_evaluador_usuario_area_nivel');
        });
    }
};
