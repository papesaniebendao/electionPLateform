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
        Schema::create('candidats', function (Blueprint $table) {
            $table->id();
            $table->string('code_etudiant');
            $table->foreign('code_etudiant')->references('code_etudiant')->on('users')->onDelete('cascade');
            $table->enum('type_candidat', ['departement', 'conseil']);
            $table->foreignId('list_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->integer('votes_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidats');
    }
};
