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
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('office_id')->nullable()->index();
            $table->string('action', 64)->index();
            $table->string('entity_type', 128)->nullable()->index();
            $table->unsignedBigInteger('entity_id')->nullable()->index();
            $table->string('description', 255);
            $table->json('changes')->nullable();
            $table->string('ip_address', 45)->nullable()->index();
            $table->string('user_agent', 255)->nullable();
            $table->text('request_url')->nullable();
            $table->string('request_method', 10)->nullable();
            $table->enum('severity', ['info', 'warning', 'critical', 'security'])->default('info')->index();
            $table->boolean('is_suspicious')->default(false)->index();
            $table->timestamp('created_at')->useCurrent()->index();

            $table->index(['user_id', 'created_at']);
            $table->index(['action', 'created_at']);
            $table->index(['is_suspicious', 'created_at']);
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
