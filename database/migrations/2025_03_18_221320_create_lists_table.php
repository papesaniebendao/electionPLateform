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
        Schema::create('lists', function (Blueprint $table) {
            $table->id();
            $table->string('nom_liste');
            $table->text('description');
            
            // 🎓 Délégués Départementaux
            $table->string('code_etu_info_licence');   // Informatique Licence
            $table->string('code_etu_info_master');    // Informatique Master
            $table->string('code_etu_math_licence');   // Mathématique Licence
            $table->string('code_etu_math_master');    // Mathématique Master
            $table->string('code_etu_phys_licence');   // Physique Licence
            $table->string('code_etu_phys_master');    // Physique Master
            
            // 🏛 Délégués Conseil
            $table->string('code_etu_conseil_licence');  // Conseil Licence
            $table->string('code_etu_conseil_master');   // Conseil Master

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lists');
    }
};
