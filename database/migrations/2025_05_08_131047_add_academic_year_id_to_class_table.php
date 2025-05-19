<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */



    public function down()
    {
        Schema::table('assign_class_teacher', function (Blueprint $table) {
            // Supprimer la clé étrangère et la colonne
            $table->dropForeign(['academic_year_id']);
            $table->dropColumn('academic_year_id');
        });
    }
};
