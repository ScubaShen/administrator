<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupervisionsTable extends Migration 
{
	public function up()
	{
		Schema::create('supervisions', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->text('description');
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::dropIfExists('supervisions');
	}
}
