<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('cathegory');
            $table->string('title');
            $table->integer('year')->unsigned();
            $table->integer('width')->unsigned();
            $table->integer('height')->unsigned();
            $table->integer('depth')->unsigned()->nullable();
            $table->text('description');
            $table->text('condition');
            $table->string('origin');
            $table->string('company');
            $table->string('product_image_path');
            $table->integer('min_price')->unsigned();
            $table->integer('max_price')->unsigned();
            $table->integer('buyout_price')->unsigned()->nullable();
            $table->date('end_date');
            $table->string('status')->default('active');
            $table->string('customer_name');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auctions');
    }
}
