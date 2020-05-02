<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('status');
            $table->unsignedBigInteger('productname_id');
            $table->string('product_name');
            $table->unsignedBigInteger('department_id');
            $table->string('department_name');
            $table->unsignedBigInteger('shiftwork_id');
            $table->string('shift_name');
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->string('author');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
