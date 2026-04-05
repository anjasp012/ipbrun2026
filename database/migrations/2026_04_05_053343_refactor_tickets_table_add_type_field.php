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
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('type')->default('umum')->after('category_id');
            $table->string('name')->nullable()->change();
        });

        // Seed current types from current names
        foreach (\App\Models\Ticket::all() as $ticket) {
            $nameStr = strtoupper($ticket->getRawOriginal('name') ?: 'UMUM');
            if (str_contains($nameStr, 'IPB')) {
                $ticket->update(['type' => 'ipb', 'name' => null]);
            } elseif (str_contains($nameStr, 'UMUM')) {
                $ticket->update(['type' => 'umum', 'name' => null]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->string('name')->nullable(false)->change();
        });
    }
};
