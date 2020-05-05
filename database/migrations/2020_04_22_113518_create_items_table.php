<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['Phone', 'Pet', 'Jewellery']);
            $table->enum('colour', ['Black', 'White', 'Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Pink', 'Orange', 'None']);
            $table->string('name', 32);
            $table->string('description', 500)->nullable();
            $table->date('date_found');
            $table->string('location_found', 256);
            $table->bigInteger('found_by');
            $table->string('image', 256)->nullable();
            $table->enum('state', ['Open', 'Requested', 'Claimed'])->default('Open');
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
        Schema::dropIfExists('items');
    }
}
