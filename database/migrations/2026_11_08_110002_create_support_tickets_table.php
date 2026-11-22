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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 50)->unique();
            $table->integer('office_id');
            $table->integer('customer_id')->nullable();
            $table->integer('created_by');
            $table->foreignId('category_id')->constrained('support_ticket_categories')->onDelete('restrict');
            $table->string('subject', 255);
            $table->longText('description');
            $table->string('priority', 20)->default('medium'); // low, medium, high, urgent
            $table->string('status', 30)->default('OPEN'); // OPEN, UNDER_REVIEW, WAITING_FOR_CLIENT, QUOTATION_SENT, AWAITING_APPROVAL, APPROVED, IN_PROGRESS, RESOLVED, CLOSED, REJECTED
            $table->boolean('is_chargeable')->nullable();
            $table->decimal('estimated_amount', 12, 2)->nullable();
            $table->decimal('actual_amount', 12, 2)->nullable();
            $table->text('quotation_description')->nullable();
            $table->date('estimated_completion_date')->nullable();
            $table->string('client_approval_status', 20)->default('not_required'); // not_required, pending, approved, rejected
            $table->integer('client_approved_by')->nullable();
            $table->dateTime('client_approved_at')->nullable();
            $table->dateTime('resolved_at')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('client_approved_by')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index('office_id');
            $table->index('created_by');
            $table->index('category_id');
            $table->index('status');
            $table->index('priority');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};

