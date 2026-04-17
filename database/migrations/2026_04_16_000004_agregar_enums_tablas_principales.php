<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE competencia
            MODIFY estado_fase ENUM(
                'borrador','publicada','en_proceso','concluida','avalada'
            ) NOT NULL DEFAULT 'borrador'
        ");

        DB::statement("
            ALTER TABLE examen
            MODIFY estado_ejecucion ENUM(
                'no_iniciada','en_curso','finalizada'
            ) NOT NULL DEFAULT 'no_iniciada'
        ");

        DB::statement("
            ALTER TABLE evaluacion
            MODIFY estado_participacion ENUM(
                'presente','ausente','descalificado','normal'
            ) NOT NULL DEFAULT 'presente'
        ");

        DB::statement("
            ALTER TABLE fase_global
            MODIFY codigo ENUM(
                'CONFIGURACION','EVALUACION','FINAL'
            ) NOT NULL
        ");

        DB::statement("
            ALTER TABLE medallero
            MODIFY medalla ENUM(
                'ORO','PLATA','BRONCE','MENCION'
            ) NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE competencia  MODIFY estado_fase          VARCHAR(50) NOT NULL DEFAULT 'borrador'");
        DB::statement("ALTER TABLE examen        MODIFY estado_ejecucion     VARCHAR(50) NOT NULL DEFAULT 'no_iniciada'");
        DB::statement("ALTER TABLE evaluacion    MODIFY estado_participacion VARCHAR(50) NOT NULL DEFAULT 'presente'");
        DB::statement("ALTER TABLE fase_global   MODIFY codigo               VARCHAR(50) NOT NULL");
        DB::statement("ALTER TABLE medallero     MODIFY medalla              VARCHAR(20) NOT NULL");
    }
};
