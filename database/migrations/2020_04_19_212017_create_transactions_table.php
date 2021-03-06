<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('scanned_result');
            $table->string('scanned_type')->nullable();
            // $table->string('scanned_time');
            $table->unsignedBigInteger('order_id');
            $table->string('order_number');
            // $table->index('order_number');
            $table->unsignedBigInteger('machine_id');
            $table->string('machine_name');
            $table->string('product_name');
            $table->string('department_name');
            $table->string('shift_name');
            $table->unsignedBigInteger('feeder_number');
            $table->string('position');
            $table->unsignedBigInteger('part_id');
            $table->string('own_partnumber');
            $table->string('vendor_partnumber');
            $table->string('value');
            $table->string('scanned_own_partnumber');
            $table->string('scanned_vendor_partnumber');
            $table->string('scanned_value');
            $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('transactions');
    }
}
