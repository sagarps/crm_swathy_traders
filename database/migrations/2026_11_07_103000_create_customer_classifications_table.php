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
        if (!Schema::hasTable('customer_classifications')) {
            Schema::create('customer_classifications', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->unique();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('customers') && !Schema::hasColumn('customers', 'customer_classification_id')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->unsignedBigInteger('customer_classification_id')->nullable()->after('customer_type_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('customers') && Schema::hasColumn('customers', 'customer_classification_id')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('customer_classification_id');
            });
        }

        Schema::dropIfExists('customer_classifications');
    }
};

