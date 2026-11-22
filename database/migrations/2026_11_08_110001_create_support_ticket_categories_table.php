<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('support_ticket_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed default categories
        $now = now();
        DB::table('support_ticket_categories')->insert([
            ['name' => 'Bug / Error', 'description' => 'Application bugs and error reports', 'is_active' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Correction', 'description' => 'Existing functionality corrections and minor fixes', 'is_active' => true, 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Customization', 'description' => 'Customization requests for existing workflows', 'is_active' => true, 'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'New Feature', 'description' => 'Brand new feature or module requests', 'is_active' => true, 'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Data Correction', 'description' => 'Database records and data correction requests', 'is_active' => true, 'sort_order' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'General Support', 'description' => 'General application support and guidance', 'is_active' => true, 'sort_order' => 6, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_ticket_categories');
    }
};

