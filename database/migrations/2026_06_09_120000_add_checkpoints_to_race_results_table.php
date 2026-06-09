<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('race_results', function (Blueprint $table) {
            // Keep cp1 & cp2, just ADD named checkpoint columns after cp2
            $table->string('cp_3km')->nullable()->after('cp2');     // 3KM  — FM, HM, 10K, 5K
            $table->string('cp_6_4km')->nullable()->after('cp_3km');    // 6.4KM — HM
            $table->string('cp_8_9km')->nullable()->after('cp_6_4km'); // 8.9KM — FM, HM
            $table->string('cp_10km')->nullable()->after('cp_8_9km');  // 10KM  — 10K / FM
            $table->string('cp_16_1km')->nullable()->after('cp_10km'); // 16.1KM — FM, HM
            $table->string('cp_19km')->nullable()->after('cp_16_1km'); // 19KM  — FM, HM
            $table->string('cp_26_1km')->nullable()->after('cp_19km'); // 26.1KM — FM
            $table->string('cp_29km')->nullable()->after('cp_26_1km'); // 29KM  — FM
            $table->string('cp_36km')->nullable()->after('cp_29km');   // 36KM  — FM
            $table->string('cp_38_5km')->nullable()->after('cp_36km'); // 38.5KM — FM
        });
    }

    public function down(): void
    {
        Schema::table('race_results', function (Blueprint $table) {
            $table->dropColumn([
                'cp_3km',
                'cp_6_4km',
                'cp_8_9km',
                'cp_10km',
                'cp_16_1km',
                'cp_19km',
                'cp_26_1km',
                'cp_29km',
                'cp_36km',
                'cp_38_5km',
            ]);
        });
    }
};
