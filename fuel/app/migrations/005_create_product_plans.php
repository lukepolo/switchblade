<?php

namespace Fuel\Migrations;

class Create_product_plans
{
	public function up()
	{
		\DBUtil::create_table('product_plans', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'stripe_id' => array('constraint' => 255, 'type' => 'varchar'),
			'product_id' => array('constraint' => 11, 'type' => 'int', 'null' => true),
                        'interval' => array('constraint' => 255, 'type' => 'varchar'),
                        'interval_count' => array('constraint' => 2, 'type' => 'int'),
			'description' => array('constraint' => 255, 'type' => 'varchar'),
			'price' => array('constraint' => 255, 'type' => 'varchar'),
                        'active' => array('type' => 'tinyint'),
                        'order' => array('constraint' => 2, 'type' => 'int', 'null' => true),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
                        'deleted_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
                
                //Main Index
                \DBUtil::create_index('product_plans', array('stripe_id'), 'stripe_id');
                // JOIN Index
                \DBUtil::create_index('product_plans', array('product_id'), 'product_id');
                
	}

	public function down()
	{
		\DBUtil::drop_table('product_plans');
	}
}