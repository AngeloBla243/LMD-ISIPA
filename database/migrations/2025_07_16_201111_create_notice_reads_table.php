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
        Schema::create('notice_reads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notice_board_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('read_at')->nullable();
            $table->unique(['notice_board_id', 'user_id']); // Un utilisateur ne lit chaque notice qu'une fois
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notice_reads');
    }
};
