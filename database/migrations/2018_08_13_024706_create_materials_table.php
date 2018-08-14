<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialsTable extends Migration 
{
	public function up()
	{
		Schema::create('materials', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
			$table->unsignedInteger('company_id');
			$table->unsignedInteger('user_id');
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::drop('materials');
	}
}
