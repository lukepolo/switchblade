<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbsplitExperimentDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('absplit__experiment_data', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('absplit__experiments_id');
			$table->binary('js');
			$table->binary('css');
			$table->binary('history');
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
		Schema::drop('absplit__experiment_data');
	}

}
