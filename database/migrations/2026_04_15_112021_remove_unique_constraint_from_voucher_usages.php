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
        Schema::table('voucher_usages', function (Blueprint $table) {
            // Add separate indexes so foreign keys remain satisfied
            $table->index('voucher_id', 'voucher_usages_voucher_id_index');
            $table->index('participant_id', 'voucher_usages_participant_id_index');
            
            // Drop the unique constraint
            $table->dropUnique('voucher_usages_voucher_id_participant_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voucher_usages', function (Blueprint $table) {
            $table->unique(['voucher_id', 'participant_id'], 'voucher_usages_voucher_id_participant_id_unique');
            $table->dropIndex('voucher_usages_voucher_id_index');
            $table->dropIndex('voucher_usages_participant_id_index');
        });
    }
};
