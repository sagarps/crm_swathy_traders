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
        if (!Schema::hasTable('security_alerts')) {
            Schema::create('security_alerts', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('alert_type', 64)->index();
                $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium')->index();
                $table->string('title', 255);
                $table->text('description');
                $table->string('ip_address', 45)->nullable()->index();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->json('metadata')->nullable();
                $table->enum('status', ['open', 'investigating', 'resolved', 'false_positive'])->default('open')->index();
                $table->unsignedBigInteger('resolved_by')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->text('resolution_notes')->nullable();
                $table->timestamps();

                $table->index(['status', 'severity']);
                $table->index(['created_at', 'severity']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_alerts');
    }
};
