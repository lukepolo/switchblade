<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbsplitExperimentDatasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('absplit__experiment__datas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('absplit_experiment_id');
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
		Schema::drop('absplit__experiment__datas');
	}

}
