<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBatchesTable extends Migration 
{
	public function up()
	{
		Schema::create('batches', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->unsignedInteger('engineering_id');
            $table->unsignedInteger('company_id');
            $table->decimal('range', 10, 2);
            $table->decimal('longitude', 10, 2);
            $table->decimal('latitude', 10, 2);
            $table->decimal('safe_distance', 10, 2);
			$table->timestamp('start_at');
            $table->timestamp('finish_at')->nullable();
            $table->text('description');
            $table->text('groups');
            $table->text('materials');
            $table->boolean('finished')->default(0);
            $table->unsignedInteger('user_id');
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::drop('batches');
	}
}
