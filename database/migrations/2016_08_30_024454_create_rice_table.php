<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rice', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->index();
            $table->string('name', 100);
            $table->string('email', 255);
            $table->decimal('volume', 3, 1);
            $table->string('comment', 255)->nullable();
            $table->boolean('ricer')->index();
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
        Schema::drop('rice');
    }
}
