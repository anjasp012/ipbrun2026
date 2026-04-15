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
        // Migration kept for rollback compatibility — columns removed in separate steps
    }

    public function down(): void
    {
        // Nothing to reverse
    }
};
