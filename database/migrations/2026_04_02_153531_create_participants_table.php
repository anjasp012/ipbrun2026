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
        Schema::create('participants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('order_code')->unique();
            $table->foreignUuid('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained()->nullOnDelete();
            
            // Personal Data
            $table->string('name');
            $table->string('email');
            $table->string('phone_number');
            $table->string('nik', 16);
            $table->string('date_birth');
            $table->enum('sex', ['male', 'female']);
            $table->enum('blood_type', ['A', 'B', 'AB', 'O', '-']);
            $table->enum('jersey_size', ['S', 'M', 'L', 'XL', 'XXL']);
            $table->string('nim_nrp')->nullable(); // Optional for IPB
            $table->string('nationality')->default('WNI');
            $table->text('address');
            
            // Emergency Contact
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_phone_number');
            $table->string('emergency_contact_relationship');
            
            // Donations
            $table->integer('donation_event')->default(0);
            $table->integer('donation_scholarship')->default(0);
            
            // Total & Payment
            $table->integer('total_price');
            $table->string('snap_token')->nullable();
            $table->string('payment_url')->nullable();
            $table->enum('status', ['pending', 'paid', 'failed', 'expired'])->default('pending');
            
            // Racepack & Tracking
            $table->string('bib_number')->nullable()->unique();
            $table->timestamp('qr_sent')->nullable();
            $table->timestamp('notif_sent')->nullable();
            $table->foreignUuid('scanned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('scanned_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
