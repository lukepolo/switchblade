<?php

namespace Fuel\Migrations;

class Create_payments
{
	public function up()
	{
		\DBUtil::create_table('payments', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'charge_id' => array('constraint' => 255, 'type' => 'varchar'),
                        'amount' => array('constraint' => 255, 'type' => 'varchar'),
                        'refund' => array('constraint' => 255, 'type' => 'varchar'),
                        'product_id' => array('constraint' => 11, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('payments');
	}
}