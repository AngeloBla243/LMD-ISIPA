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
        Schema::table('student_add_fees', function (Blueprint $table) {
            $table->unsignedBigInteger('fee_type_id')->after('class_id');
        });
    }
    public function down()
    {
        Schema::table('student_add_fees', function (Blueprint $table) {
            $table->dropColumn('fee_type_id');
        });
    }
};
