<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('assign_class_teacher', function (Blueprint $table) {
            // Ajouter la colonne academic_year_id (nullable temporairement)
            $table->unsignedBigInteger('academic_year_id')
                ->nullable()
                ->after('id');

            // Clé étrangère vers academic_years
            $table->foreign('academic_year_id')
                ->references('id')
                ->on('academic_years')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('assign_class_teacher', function (Blueprint $table) {
            // Supprimer la clé étrangère et la colonne
            $table->dropForeign(['academic_year_id']);
            $table->dropColumn('academic_year_id');
        });
    }
};
