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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
            $table->text('bio')->nullable()->after('username');
            $table->string('avatar_path')->nullable()->after('password');
            $table->string('phone')->nullable()->after('avatar_path');
            $table->boolean('is_active')->default(true)->after('phone');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->date('birth_date')->nullable()->after('last_login_ip');
            $table->foreignId('created_by')->nullable()->after('birth_date')
                ->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn([
                'username',
                'bio',
                'avatar_path',
                'phone',
                'is_active',
                'last_login_at',
                'last_login_ip',
                'birth_date',
                'created_by',
            ]);
        });
    }
};
