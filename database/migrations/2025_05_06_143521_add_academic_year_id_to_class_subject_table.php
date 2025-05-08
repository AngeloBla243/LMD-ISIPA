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
        Schema::table('class_subject', function (Blueprint $table) {
            $table->foreignId('academic_year_id')
                ->nullable()
                ->constrained('academic_years')
                ->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_subject', function (Blueprint $table) {
            //
        });
    }
};
