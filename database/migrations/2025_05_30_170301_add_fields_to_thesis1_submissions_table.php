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
        Schema::table('thesis1_submissions', function (Blueprint $table) {
            $table->tinyInteger('type')->default(1)->comment('1=Mémoire, 2=Projet');
            $table->unsignedBigInteger('directeur_id')->nullable()->comment('Directeur (Prof) pour mémoire');
            $table->unsignedBigInteger('encadreur_id')->nullable()->comment('Encadreur (Prof ou Ct) pour projet');
            $table->unsignedBigInteger('class_id')->nullable()->comment('Classe de l’étudiant');
            $table->string('project_name')->nullable()->comment('Nom du projet, si type=2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thesis1_submissions', function (Blueprint $table) {
            //
        });
    }
};
