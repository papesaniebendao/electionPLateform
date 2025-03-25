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
        Schema::table('votes', function (Blueprint $table) {
            $table->dropUnique('votes_user_id_unique'); // Supprime l'ancienne contrainte
            $table->unique(['user_id', 'poste']); // Ajoute la nouvelle contrainte
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'poste']);
            $table->unique('user_id'); // Rétablit l'unicité initiale si besoin
        });
    }
};
