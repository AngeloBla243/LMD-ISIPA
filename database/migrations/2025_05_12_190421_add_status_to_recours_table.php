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
        Schema::table('recours', function (Blueprint $table) {
            $table->boolean('status')->default(false); // false = Non traité, true = Traité
        });
    }




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recours', function (Blueprint $table) {
            //
        });
    }
};
