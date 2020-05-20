<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderfeedersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderfeeders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('order_number');
            $table->unsignedBigInteger('machine_id');
            $table->string('machine_name');
            $table->string('product_name');
            $table->string('department_name');
            $table->string('shift_name');
            $table->unsignedBigInteger('part_id');
            $table->string('own_partnumber');
            $table->string('vendor_partnumber');
            $table->string('description');
            $table->string('value');
            $table->unsignedInteger('feeder_number');
            $table->string('position');
            $table->unsignedInteger('qty');
            $table->string('author');
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
        Schema::dropIfExists('orderfeeders');
    }
}
