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
        Schema::create('support_ticket_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('support_tickets')->onDelete('cascade');
            $table->string('old_status', 50)->nullable();
            $table->string('new_status', 50);
            $table->integer('changed_by');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('changed_by')->references('id')->on('users')->onDelete('cascade');
            $table->index('ticket_id');
            $table->index('new_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_ticket_status_histories');
    }
};

