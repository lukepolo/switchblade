<?php

namespace Fuel\Migrations;

class Create_product_subscriptions
{
	public function up()
	{
		\DBUtil::create_table('product_subscriptions', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'stripe_id' => array('constraint' => 255, 'type' => 'varchar'),
			'product_id' => array('constraint' => 11, 'type' => 'int'),
			'description' => array('constraint' => 255, 'type' => 'varchar'),
			'price' => array('constraint' => 255, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('product_subscriptions');
	}
}