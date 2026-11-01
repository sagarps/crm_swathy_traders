<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessAssociateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_associate', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('associate_type');
            $table->string('associate_name');
            $table->string('contact_person');
            $table->string('contact_no');
            $table->integer('place_id');
            $table->string('address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_associate');
    }
}
