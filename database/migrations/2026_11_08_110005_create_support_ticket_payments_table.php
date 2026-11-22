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
        Schema::create('support_ticket_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('support_tickets')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->date('payment_date');
            $table->string('payment_method', 50);
            $table->string('reference_number', 100)->nullable();
            $table->text('remarks')->nullable();
            $table->integer('recorded_by');
            $table->timestamps();

            $table->foreign('recorded_by')->references('id')->on('users')->onDelete('cascade');
            $table->index('ticket_id');
            $table->index('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_ticket_payments');
    }
};

