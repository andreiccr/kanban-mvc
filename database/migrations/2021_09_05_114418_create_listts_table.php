<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string("name");
            $table->unsignedBigInteger("workboard_id")->index('workboard_id');
            $table->integer("position")->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listts');
    }
}
