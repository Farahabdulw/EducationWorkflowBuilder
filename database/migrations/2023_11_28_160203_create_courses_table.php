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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code');
            $table->string('program');
            $table->integer('department_id');
            $table->integer('college_id');
            $table->string('institution');

            $table->string('essential_references')->nullalbe();
            $table->string('supportive_references')->nullalbe();
            $table->string('electronic_references')->nullalbe();
            $table->string('other_references')->nullalbe();

            $table->string('approved_by')->nullalbe();
            $table->string('approval_number')->nullalbe();
            $table->string('approval_date')->nullalbe();

            $table->string('level')->nullalbe();

            $table->integer('credit')->nullalbe();
            $table->integer('Tatorial')->nullalbe();

            $table->json('type')->nullalbe();
            $table->json('enrollment')->nullalbe();
            
            $table->text('description')->nullalbe();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
