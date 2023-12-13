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
        Schema::create('course_values', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id');
            $table->string('learning_outcome')->nullable();
            $table->string('CLO_code')->nullable();
            $table->string('teaching_strategies')->nullable();
            $table->string('assessment_methods')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_values');
    }
};
