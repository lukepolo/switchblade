<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbsplitExperimentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('absplit__experiments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->string('url');
			$table->string('url_pattern');
			$table->integer('absplit_experiment_type_id');
			$table->integer('absplit_experiment_settings_id');
			$table->integer('absplit_experiment_data_id');
			$table->boolean('active');
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
		Schema::drop('absplit__experiments');
	}

}
