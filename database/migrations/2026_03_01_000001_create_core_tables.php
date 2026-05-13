<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── DEPORTES ────────────────────────────────────────────────────────
        Schema::create('deportes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 120)->unique();
            $table->string('slug', 120)->unique();
            $table->text('descripcion')->nullable();
            $table->string('icono', 100)->nullable();   // nombre de ícono o ruta
            $table->boolean('es_equipo')->default(true); // true = deporte colectivo
            $table->unsignedTinyInteger('min_jugadores')->default(1);
            $table->unsignedTinyInteger('max_jugadores')->nullable();
            $table->json('reglas')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ─── CAMPEONATOS ─────────────────────────────────────────────────────
        Schema::create('campeonatos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 250);
            $table->string('slug', 250)->unique();
            $table->foreignId('deporte_id')->constrained('deportes')->restrictOnDelete();
            $table->foreignId('organizador_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('creado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->text('descripcion')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            // Estado referenciando la tabla lookup
            $table->foreignId('estado_id')->nullable()->constrained('lookup_championship_states')->nullOnDelete();
            $table->json('reglas')->nullable();
            $table->unsignedTinyInteger('puntos_ganado')->default(3);
            $table->unsignedTinyInteger('puntos_empate')->default(1);
            $table->unsignedTinyInteger('puntos_perdida')->default(0);
            $table->unsignedSmallInteger('max_equipos')->nullable();
            $table->string('formato', 100)->nullable(); // liga, copa, grupos+eliminatoria…
            $table->string('lugar', 255)->nullable();
            $table->string('logo_path')->nullable();
            $table->string('banner_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });

        // ─── EQUIPOS ─────────────────────────────────────────────────────────
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 200);
            $table->string('tag', 10)->nullable();       // abreviatura, ej. "BOG"
            $table->foreignId('campeonato_id')->constrained('campeonatos')->cascadeOnDelete();
            $table->foreignId('entrenador_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('descripcion')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('color_primario', 20)->nullable();
            $table->string('color_secundario', 20)->nullable();
            $table->string('ciudad', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Nombre único por campeonato
            $table->unique(['campeonato_id', 'nombre']);
        });

        // ─── JUGADORES ───────────────────────────────────────────────────────
        Schema::create('jugadores', function (Blueprint $table) {
            $table->id();
            // Puede estar vinculado a un User del sistema o ser solo un registro
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('equipo_id')->nullable()->constrained('equipos')->nullOnDelete();
            $table->string('nombre', 200);
            $table->string('apellido', 200)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('genero', 30)->nullable();
            $table->string('nacionalidad', 80)->nullable();
            $table->string('documento_tipo', 30)->nullable();
            $table->string('documento_numero', 60)->nullable();
            $table->unsignedTinyInteger('dorsal')->nullable();
            // Lookup de posición
            $table->foreignId('posicion_id')->nullable()->constrained('lookup_positions')->nullOnDelete();
            // Lookup de pie dominante
            $table->foreignId('pie_dominante_id')->nullable()->constrained('lookup_dominant_feet')->nullOnDelete();
            // Lookup estado médico
            $table->foreignId('estado_medico_id')->nullable()->constrained('lookup_medical_states')->nullOnDelete();
            $table->string('foto_path')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Dorsal único dentro del equipo (si se especifica)
            $table->unique(['equipo_id', 'dorsal']);
        });

        // ─── PARTIDOS ────────────────────────────────────────────────────────
        Schema::create('partidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campeonato_id')->constrained('campeonatos')->cascadeOnDelete();
            $table->unsignedSmallInteger('jornada')->nullable();
            $table->string('fase', 100)->nullable();    // "Fase de grupos", "Semifinal"…
            $table->foreignId('equipo_local_id')->constrained('equipos')->restrictOnDelete();
            $table->foreignId('equipo_visitante_id')->constrained('equipos')->restrictOnDelete();
            $table->timestamp('fecha_hora')->nullable();
            $table->string('lugar', 255)->nullable();
            // Lookup estado del partido
            $table->foreignId('estado_id')->nullable()->constrained('lookup_match_states')->nullOnDelete();
            $table->unsignedSmallInteger('duracion_minutos')->nullable();
            $table->text('notas')->nullable();
            $table->foreignId('creado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Un equipo no puede jugar contra sí mismo (check a nivel app, aquí índice)
            $table->index(['campeonato_id', 'fecha_hora']);
            $table->index(['equipo_local_id', 'equipo_visitante_id']);
        });

        // ─── RESULTADOS ──────────────────────────────────────────────────────
        Schema::create('resultados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partido_id')->unique()->constrained('partidos')->cascadeOnDelete();
            $table->unsignedSmallInteger('goles_local')->default(0);
            $table->unsignedSmallInteger('goles_visitante')->default(0);
            // Para deportes con sets (voleibol, tenis…)
            $table->json('sets_local')->nullable();
            $table->json('sets_visitante')->nullable();
            // Equipo ganador (null = empate)
            $table->foreignId('ganador_id')->nullable()->constrained('equipos')->nullOnDelete();
            $table->boolean('es_oficial')->default(false);
            $table->text('observaciones')->nullable();
            $table->foreignId('registrado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // ─── NOVEDADES / EVENTOS DE PARTIDO ──────────────────────────────────
        Schema::create('novedades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partido_id')->constrained('partidos')->cascadeOnDelete();
            $table->foreignId('jugador_id')->nullable()->constrained('jugadores')->nullOnDelete();
            $table->foreignId('equipo_id')->nullable()->constrained('equipos')->nullOnDelete();
            $table->foreignId('tipo_id')->nullable()->constrained('lookup_event_types')->nullOnDelete();
            $table->unsignedSmallInteger('minuto')->nullable();
            $table->text('descripcion')->nullable();
            $table->foreignId('creado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['partido_id', 'tipo_id']);
        });

        // ─── INSCRIPCIONES ───────────────────────────────────────────────────
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campeonato_id')->constrained('campeonatos')->cascadeOnDelete();
            $table->foreignId('equipo_id')->constrained('equipos')->cascadeOnDelete();
            $table->foreignId('estado_id')->nullable()->constrained('lookup_registration_states')->nullOnDelete();
            $table->timestamp('fecha_inscripcion')->useCurrent();
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->unique(['campeonato_id', 'equipo_id']);
        });

        // ─── PUNTUACIONES (tabla de posiciones) ──────────────────────────────
        Schema::create('puntuaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campeonato_id')->constrained('campeonatos')->cascadeOnDelete();
            $table->foreignId('equipo_id')->constrained('equipos')->cascadeOnDelete();
            $table->unsignedSmallInteger('partidos_jugados')->default(0);
            $table->unsignedSmallInteger('ganados')->default(0);
            $table->unsignedSmallInteger('empatados')->default(0);
            $table->unsignedSmallInteger('perdidos')->default(0);
            $table->unsignedSmallInteger('goles_favor')->default(0);
            $table->unsignedSmallInteger('goles_contra')->default(0);
            $table->smallInteger('diferencia')->default(0);
            $table->unsignedSmallInteger('puntos')->default(0);
            $table->timestamps();

            $table->unique(['campeonato_id', 'equipo_id']);
            $table->index(['campeonato_id', 'puntos']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('puntuaciones');
        Schema::dropIfExists('inscripciones');
        Schema::dropIfExists('novedades');
        Schema::dropIfExists('resultados');
        Schema::dropIfExists('partidos');
        Schema::dropIfExists('jugadores');
        Schema::dropIfExists('equipos');
        Schema::dropIfExists('campeonatos');
        Schema::dropIfExists('deportes');
    }
};
