<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string("title");
            $table->unsignedBigInteger("listt_id")->index("listt_id");
            $table->integer("position")->nullable(false);

            $table->string("details")->nullable();
            $table->dateTime("due_date")->nullable();
            $table->dateTime("done_date")->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards');
    }
}
