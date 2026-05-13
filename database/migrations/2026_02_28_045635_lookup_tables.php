<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lookup_championship_states', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('color_hex', 7)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
        });

        Schema::create('lookup_match_states', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('color_hex', 7)->nullable();
            $table->boolean('is_terminal')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
        });

        Schema::create('lookup_event_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->boolean('affects_score')->default(false);
            $table->boolean('is_sanction')->default(false);
            $table->boolean('requires_player')->default(true);
            $table->string('color_hex', 7)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
        });

        Schema::create('lookup_phase_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
        });

        Schema::create('lookup_sanction_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->boolean('triggers_suspension')->default(false);
            $table->unsignedTinyInteger('yellows_to_accumulate')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
        });

        Schema::create('lookup_referee_roles', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
        });

        Schema::create('lookup_positions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->string('abbreviation', 10)->nullable();
            $table->string('zone', 50)->nullable(); // goalkeeper, defense, midfield, attack
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
        });

        Schema::create('lookup_registration_states', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('color_hex', 7)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
        });

        Schema::create('lookup_dominant_feet', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 50);
            $table->boolean('is_active')->default(true);
        });

        Schema::create('lookup_medical_states', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->boolean('can_play')->default(true);
            $table->string('color_hex', 7)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lookup_medical_states');
        Schema::dropIfExists('lookup_dominant_feet');
        Schema::dropIfExists('lookup_registration_states');
        Schema::dropIfExists('lookup_positions');
        Schema::dropIfExists('lookup_referee_roles');
        Schema::dropIfExists('lookup_sanction_types');
        Schema::dropIfExists('lookup_phase_types');
        Schema::dropIfExists('lookup_event_types');
        Schema::dropIfExists('lookup_match_states');
        Schema::dropIfExists('lookup_championship_states');
    }
};
