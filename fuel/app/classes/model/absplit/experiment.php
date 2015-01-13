<?php

class Model_Absplit_Experiment extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'user_id',
		'url',
                'url_pattern',
		'absplit_experiment_type_id',
                'active',
                'absplit_experiment_data_id',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'mysql_timestamp' => false,
		),
	);

	protected static $_table_name = 'absplit_experiments';
        
        protected static $_has_one = array(
            'absplit_experiment_datum'
        );

}
