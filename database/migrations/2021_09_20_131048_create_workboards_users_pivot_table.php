<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkboardsUsersPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('WorkboardsUsers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger("workboard_id");
            $table->unsignedBigInteger("user_id");
            $table->unsignedInteger("role");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('WorkboardsUsers');
    }
}
