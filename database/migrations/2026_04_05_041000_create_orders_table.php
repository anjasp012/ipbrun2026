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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('participant_id')->constrained()->cascadeOnDelete();
            
            $table->string('order_code')->unique();
            $table->string('status')->default('pending');
            $table->string('snap_token')->nullable();
            $table->string('payment_url')->nullable();
            
            $table->integer('total_price')->default(0);
            $table->integer('admin_fee')->default(0);
            $table->integer('donation_scholarship')->default(0);
            $table->integer('donation_event')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
