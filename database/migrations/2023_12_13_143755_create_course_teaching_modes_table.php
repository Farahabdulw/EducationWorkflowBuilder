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
        Schema::create('course_teaching_modes', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id');
            $table->integer('percentage');
            $table->string('mode_of_instruction')->nullable();
            $table->string('contact_hours')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_teaching_modes');
    }
};
