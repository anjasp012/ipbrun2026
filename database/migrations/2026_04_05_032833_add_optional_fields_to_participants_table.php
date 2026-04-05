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
        Schema::table('participants', function (Blueprint $table) {
            $table->string('running_community')->nullable()->after('address');
            $table->text('previous_events')->nullable()->after('running_community');
            $table->string('best_time')->nullable()->after('previous_events');
            $table->string('shuttle_bus')->nullable()->after('best_time');
            $table->string('other_race_interest')->nullable()->after('shuttle_bus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn([
                'running_community',
                'previous_events',
                'best_time',
                'shuttle_bus',
                'other_race_interest'
            ]);
        });
    }
};
