<?php

class Model_Absplit_Experiment_Datum extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'absplit_experiment_id',
		'js',
		'user_css',
		'user_js',
                'history',
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

	protected static $_table_name = 'absplit_experiment_data';
        
        protected static $_belongs_to = array(
            'absplit_experiments'
        );
}
