<?php

namespace Fuel\Migrations;

class Create_products
{
	public function up()
	{
		\DBUtil::create_table('products', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'name' => array('constraint' => 255, 'type' => 'varchar'),
			'active' => array('type' => 'tinyint'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
                
                // Main Index
                \DBUtil::create_index('products', array('name'), 'name');
	}

	public function down()
	{
		\DBUtil::drop_table('products');
	}
}