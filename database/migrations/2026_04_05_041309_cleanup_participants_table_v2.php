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
            $table->dropColumn([
                'ticket_id', 'bib_number', 'scanned_at', 'scanned_by',
                'status', 'order_code', 'snap_token', 'payment_url', 
                'total_price', 'donation_scholarship', 'donation_event'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->foreignUuid('ticket_id')->nullable()->constrained();
            $table->string('bib_number')->nullable();
            $table->timestamp('scanned_at')->nullable();
            $table->foreignUuid('scanned_by')->nullable()->constrained('users');
            $table->string('status')->default('pending');
            $table->string('order_code')->nullable();
            $table->string('snap_token')->nullable();
            $table->string('payment_url')->nullable();
            $table->integer('total_price')->default(0);
            $table->integer('donation_scholarship')->default(0);
            $table->integer('donation_event')->default(0);
        });
    }
};
