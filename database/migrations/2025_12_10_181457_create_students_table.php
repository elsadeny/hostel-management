<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('student_id')->unique();
            $table->string('full_name');
            $table->enum('gender', ['male', 'female']);
            $table->enum('study_level', ['undergraduate', 'postgraduate', 'diploma']);
            $table->string('department');
            $table->integer('year');
            $table->string('phone');
            $table->string('email')->unique();
            $table->timestamps();

            $table->index(['gender', 'study_level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
