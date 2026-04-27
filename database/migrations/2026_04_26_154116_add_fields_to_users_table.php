<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('name');
        $table->string('nom')->default('')->after('id');
        $table->string('prenom')->default('')->after('nom');
        $table->enum('role', ['admin', 'gestionnaire', 'consultant'])
              ->default('gestionnaire')->after('email');
        $table->boolean('is_active')->default(true)->after('role');
    });
}

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nom', 'prenom', 'role', 'is_active']);
            // 👇 nullable() pour éviter l'erreur
            $table->string('name')->nullable()->after('id');
        });
    }
};