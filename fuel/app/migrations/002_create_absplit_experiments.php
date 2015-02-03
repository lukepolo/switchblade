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
                        'url_pattern' => array('constraint' => 255, 'type' => 'varchar'),
                        'absplit_experiment_type_id' => array('constraint' => 11, 'type' => 'int'),
                        'absplit_experiment_settings_id' => array('constraint' => 11, 'type' => 'int'),
                        'active' => array('constraint' => 1, 'type' => 'tinyint'),
                        'absplit_experiment_data_id' => array('constraint' => 11, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
                
                // Main Index
                \DBUtil::create_index('absplit_experiments', array('user_id', 'url', 'active'), 'user_id & url & active');
                // JOIN Index
                \DBUtil::create_index('absplit_experiments', array('absplit_experiment_data_id'), 'absplit_experiment_data_id');
	}

	public function down()
	{
		\DBUtil::drop_table('absplit_experiments');
	}
}