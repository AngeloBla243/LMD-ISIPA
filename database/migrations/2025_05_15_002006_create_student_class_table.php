<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Dans la migration
    public function up()
    {
        Schema::create('student_class', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('class_id')->constrained('class');
            $table->foreignId('academic_year_id')->constrained('academic_years');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_class');
    }
};
