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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hostel_id')->constrained()->onDelete('cascade');
            $table->string('room_number');
            $table->integer('capacity');
            $table->integer('current_occupancy')->default(0);
            $table->integer('floor');
            $table->enum('status', ['available', 'full', 'maintenance'])->default('available');
            $table->timestamps();

            $table->index(['hostel_id', 'status']);
            $table->unique(['hostel_id', 'room_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
