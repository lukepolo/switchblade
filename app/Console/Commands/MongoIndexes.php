<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MongoIndexes extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:MongoIndexes';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Builds the MongoIndexes [Warning Can Only Be Ran Once]';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	    parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
	    $mongodb = \Mongo_Db::instance();
	    // GLOBALS
	    // USER UNIQUE
	    $mongodb->add_index('user_domains', array(
		'user_id' => 1,
	    ),array(
		'unique' => true
	    ));
	    // BY Users and Api Key
	    $mongodb->add_index('user_domains', array(
		'user_id' => 1,
		'api_key' => 1
	    ));
	    // UNIQUE
	    $mongodb->add_index('user_domains', array(
		'user_id' => 1,
		'domain' => 1
	    ), array(
		'unique' => true
	    ));
	    // Screenshots API / Module
	    // SCREENSHOTS UNIQUE
	    $mongodb->add_index('screenshots', array(
		'api_key' => 1,
	    ));
	    $mongodb->add_index('screenshot_images', array(
		'checksum' => 1,
	    ));
	    // BY ID
	    $mongodb->add_index('screenshots', array(
		'_id' => 1,
	    ));
	    // HEAT MAP MODULE
	    $mongodb->add_index('heatmap_users', array(
		'user_id' => 1,
	    ));
	    $mongodb->add_index('heatmap', array(
		'user_id' => 1,
		'user' => 1
	    ));
	    // ANALYTICS MODULE
	    $mongodb->add_index('analytics', array(
		'user_id' => 1,
		'domain' => 1
	    ));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
	}
	protected function getOptions()
	{

	}
}
