<?php

namespace Fuel\Migrations;

class Create_absplit_experiments
{
	public function up()
	{
		\DBUtil::create_table('absplit_experiments', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'url' => array('constraint' => 255, 'type' => 'varchar'),
                        'absplit_experiment_type_id' => array('constraint' => 11, 'type' => 'int'),
                        'active' => array('constraint' => 1, 'type' => 'tinyint'),
                        'absplit_experiment_data_id' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('absplit_experiments');
	}
}