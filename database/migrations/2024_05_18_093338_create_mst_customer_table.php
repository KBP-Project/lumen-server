<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMstCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_customer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customer_code', 20);
            $table->string('customer_name', 100);
            $table->string('customer_address', 512);
            $table->string('customer_pos_code', 20)->nullable();
            $table->string('customer_longitude')->nullable();
            $table->string('customer_latitude')->nullable();
            $table->string('pic1')->nullable();
            $table->string('pic1_phone')->nullable();
            $table->string('pic1_email')->nullable();
            $table->string('pic2')->nullable();
            $table->string('pic2_phone')->nullable();
            $table->string('pic2_email')->nullable();
            $table->string('start_contract')->nullable();
            $table->string('end_contract')->nullable();

            // audit management
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_customer');
    }
}
