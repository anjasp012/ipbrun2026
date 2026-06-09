<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('race_results', function (Blueprint $table) {
            $table->id();
            $table->string('item')->nullable();          // e.g. "10K UMUM", "5K IPB"
            $table->string('bib')->nullable();           // BIB number
            $table->string('name')->nullable();          // Participant name
            $table->string('gender')->nullable(); // M / F
            $table->string('gun_time')->nullable();
            $table->string('net_time')->nullable();
            $table->string('start_time')->nullable();
            $table->string('cp1')->nullable();
            $table->string('cp2')->nullable();
            $table->string('status')->nullable(); // Finished, DNF, etc.
            $table->timestamps();

            $table->unique(['bib', 'item']); // prevent duplicates on re-import
            $table->index('item');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('race_results');
    }
};
