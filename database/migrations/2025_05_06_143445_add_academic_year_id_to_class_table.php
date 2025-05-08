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
        Schema::table('class', function (Blueprint $table) {
            $table->foreignId('academic_year_id')
                ->nullable()
                ->constrained('academic_years')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('class', function (Blueprint $table) {
            $table->dropForeign(['academic_year_id']);
            $table->dropColumn('academic_year_id');
        });
    }
};
