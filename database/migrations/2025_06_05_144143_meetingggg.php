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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained();
            $table->foreignId('teacher_id')->constrained('users');
            $table->foreignId('academic_year_id')->constrained();
            $table->string('zoom_meeting_id');
            $table->string('topic');
            $table->datetime('start_time');
            $table->integer('duration');
            $table->text('agenda')->nullable();
            $table->string('join_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
