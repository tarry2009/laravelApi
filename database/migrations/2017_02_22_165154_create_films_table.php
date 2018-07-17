<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('films', function (Blueprint $table) {
            $table->increments('id'); 
            $table->string('name', 100);
			$table->text('description')->nullable();
			$table->dateTime('realease_date')->index()->nullable();
			$table->integer('rating')->nullable();
			$table->decimal('ticket_price');
			$table->string('country',50)->nullable();
			$table->string('genre',11)->nullable();
			$table->string('photo',100)->nullable();
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
        Schema::dropIfExists('films');
    }
}
