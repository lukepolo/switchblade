<?php

namespace Fuel\Migrations;

class Create_absplit_experiment_data
{
	public function up()
	{
		\DBUtil::create_table('absplit_experiment_data', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'absplit_experiment_id' => array('constraint' => 11, 'type' => 'int'),
			'js' => array('type' => 'text', 'null' => true),
			'user_css' => array('type' => 'text', 'null' => true),
			'user_js' => array('type' => 'text', 'null' => true),
                        'history' => array('type' => 'text', 'null' => true),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('absplit_experiment_data');
	}
}